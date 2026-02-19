# Laravel Octane Performance Audit — `/posts` (Target ≤0.30s)

This document summarizes optimizations applied and additional recommendations to achieve **≤0.30s** average response time for `/posts` under high concurrency (e.g. 1000 concurrent users with Siege).

---

## 1. Octane Runtime Optimization

### 1.1 Workers and concurrency (FrankenPHP / Swoole)

- **Workers:** Set to **CPU cores × 2** (e.g. 8 cores → 16 workers). Avoid more than 2× cores to reduce context switching.
- **Max requests per worker:** Restart workers after **500–1000** requests to avoid memory bloat. With FrankenPHP/Swoole you can set this in the server config or use Octane’s `max_execution_time` and monitor memory.
- **Task workers (Swoole):** If using Swoole, use task workers for heavy non-request work (e.g. clearing caches, sending notifications) so HTTP workers stay fast.

**Example (FrankenPHP):**

```bash
php artisan octane:start --server=frankenphp --workers=16 --max-requests=500
```

**Example (Swoole):**

```bash
php artisan octane:start --server=swoole --workers=16 --task-workers=4 --max-requests=500
```

### 1.2 Memory and state (safe for long-running workers)

- **No request/session in singletons:** AppServiceProvider and app code avoid binding request/session/auth as singletons. Resolve them per request (e.g. `auth()->id()`, `request()` inside methods).
- **Warm bindings (applied):** `config/octane.php` now warms `PostQueries`, `PostRepository`, `PostService`, `CacheManager`, `DatabaseManager` so the first request in each worker doesn’t pay full resolution cost.
- **Garbage collection:** `garbage` set to **32** MB so GC runs earlier and keeps worker memory lower under load.

### 1.3 Warm boot

- **Database:** Connections are warmed via `DatabaseManager` in `warm`. Ensure `config/database.php` uses a persistent connection if your driver supports it (e.g. `options` for PDO).
- **Redis:** If using Redis for cache/sessions, ensure the Redis connection is used during warm so it’s established once per worker.

---

## 2. Laravel Application Optimizations (Applied)

### 2.1 Query optimizations

| Change | Why it helps |
|--------|------------------|
| **No full `comments` / `stars` load on feed** | Replaced with `withCount(['stars', 'comments'])` and optional `with(['stars' => fn ($q) => $q->where('user_id', $userId)])` so we load at most one star per post for “did I star” and only counts for comments. Cuts rows and memory per request. |
| **Cached excluded user IDs** | `PostRepository::getAll()` and `PostQueries` use `PostQueries::getExcludedUserIds($userId)`, which is cached (5 min) per user. Avoids repeated `blocks` table hits. |
| **Single source for excluded IDs** | `PostQueries::getExcludedUserIds()` is public and reused so block-list logic and cache are in one place. |
| **Lightweight eager load** | Feed only loads `user`, `specialties` (with `subSpecialties`), `tags`, and filtered `stars` for current user. No `suspension` relation load on feed (we only use `whereDoesntHave('suspension')`). |

### 2.2 Caching (Redis recommended)

| What | TTL | Invalidation |
|------|-----|----------------|
| **Blocked / blocked-by user IDs** | 5 min | Per user key; acceptable delay when user blocks/unblocks. |
| **Tags for filter dropdown** | 1 hour | `Cache::forget('posts:filter:tags')` when tags are updated. |
| **Specialties for filter dropdown** | 1 hour | `Cache::forget('posts:filter:specialties')` when specialties change. |
| **Saved post IDs (per user)** | 5 min | `Cache::forget("user:{$userId}:saved_post_ids")` in `togglePostSave`. |

Use **Redis** as cache driver (`CACHE_STORE=redis`) for Octane so cache is shared across workers and fast.

### 2.3 Pagination and payload

- Feed already uses `paginate(9)`. No change.
- Blade uses `stars_count` / `comments_count` and relation-loaded `stars` only for “did I star” to keep payload and queries minimal.

---

## 3. Database Performance

### 3.1 Indexes (migration added)

- **`posts (user_id, created_at)`:** Speeds up “following” feed and any “posts by user, ordered by date”.
- **`posts (job_type)`:** Speeds up filter by job type.
- **`blocks (blocked_id)`:** Speeds up “users who blocked me” (blocked_id lookup). Primary key already covers (blocker_id, blocked_id).

Run:

```bash
php artisan migrate
```

### 3.2 Slow query detection

- Enable MySQL slow query log (e.g. `long_query_time = 1`) and inspect for full table scans or missing index usage.
- Optionally use Laravel Telescope (see below) or a DB APM to log queries above a threshold.

---

## 4. Octane-Specific Features

- **`Octane::concurrently()`:** For a single request that needs multiple independent queries (e.g. posts + sidebar data), you can run them in parallel. The current feed is a single paginated query plus cached dropdown/saved data; concurrency here is optional.
- **Tasks:** Use Octane tasks for cache invalidation or notifications after a response is sent so the response time is not increased.
- **Broadcasting:** Ensure Reverb/Redis are used and event listeners are not doing heavy work on the same worker; offload to queue/task workers if needed.

---

## 5. Payload and Response

- **Gzip:** Ensure the web server (or FrankenPHP/Swoole) compresses responses (e.g. `Content-Encoding: gzip`) for HTML/JSON.
- **Livewire:** The `/posts` page is Livewire; the initial HTML is small and then Livewire fetches the feed. Keeping the feed query and render fast (as above) is the main lever. Avoid large hidden payloads in the component.

---

## 6. Benchmark and Monitoring

### 6.1 Siege

**Baseline (after optimizations):**

```bash
# Install siege if needed (e.g. apt install siege)

# 1000 concurrent users, 1 minute, auth cookie if required
siege -c 1000 -t 1M -H "Cookie: ..." https://your-app.test/posts

# Or 100 concurrent, 30 seconds
siege -c 100 -t 30S https://your-app.test/posts
```

**Target:** Average response time ≤ 0.30s, 0% failed requests.

**With auth (e.g. session cookie):** Export cookie from browser and pass `-H "Cookie: name=value"` so Siege hits the same authenticated feed your users do.

### 6.2 Telescope / Pulse and Octane

- **Telescope:** Safe with Octane if you run it in a separate process or enable it only in non-production. In production, disable or use sampling to avoid storing every request in the same app.
- **Laravel Pulse:** Lightweight; can monitor slow requests and queue. Ensure storage (e.g. database) is not on the critical path of `/posts`.
- **Logging:** Use a dedicated log channel and avoid writing huge stacks on every request. For Octane, ensure log handlers are flushed (Octane’s `CloseMonologHandlers` on WorkerStopping helps).

### 6.3 Memory and worker health

- **Memory:** Monitor worker memory (e.g. `memory_get_peak_usage(true)` in a request-terminated listener or via the server’s metrics). If it grows over time, reduce `max_requests` or find leaks (e.g. static/singleton holding references).
- **Health endpoint:** Add a simple `/up` or `/health` that returns 200 and optionally checks DB/Redis. Use it for load balancer and monitoring.

### 6.4 Profiling

- **Blackfire / Tideways:** Profile a single request to `/posts` (with auth) to see where time is spent (DB, view, Livewire).
- **Laravel Debugbar:** Disable in production; use only in local/dev to inspect query count and duplicate queries.

---

## 7. Summary of Code and Config Changes

| Area | Change |
|------|--------|
| **PostRepository** | Uses `PostQueries::getExcludedUserIds()`, `withCount(['stars','comments'])`, minimal `with()` and optional `stars` for current user only. |
| **PostQueries** | `getExcludedUserIds()` made public; `getFollowingForUser` and `getPopular` use `withCount` and minimal `stars` like repository. |
| **Post (Livewire)** | Tags/specialties and saved post IDs loaded via Cache (Redis-friendly). Cache invalidation on save toggle. |
| **Blade (post)** | Uses `stars_count` / `comments_count` and `stars->isNotEmpty()` for “did I star”. |
| **Octane config** | Warm: PostQueries, PostRepository, PostService, CacheManager, DatabaseManager. Garbage 32 MB. |
| **Migrations** | Indexes: `posts (user_id, created_at)`, `posts (job_type)`, `blocks (blocked_id)`. |

---

## 8. Checklist Before Load Test

- [ ] `php artisan migrate` (indexes).
- [ ] `CACHE_STORE=redis` and Redis running.
- [ ] Octane started with worker/max-requests (e.g. `--workers=16 --max-requests=500`).
- [ ] No Telescope/Debugbar in production or they are disabled/sampled.
- [ ] Gzip enabled at server or Octane layer.
- [ ] Run Siege with auth cookie if the feed requires login.

After these, re-run Siege and compare average response time and failure rate to the ≤0.30s target.
