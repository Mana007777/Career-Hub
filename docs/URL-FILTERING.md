# URL Query Filtering Guide

This document describes all URL query parameters used for filtering across the Career Hub application.

---

## Posts Feed (`/posts`)

**Route:** `GET /posts`  
**Component:** `Livewire\Post`

| Parameter | Alias | Values | Default | Description |
|-----------|-------|--------|---------|-------------|
| `feed` | - | `new`, `popular`, `following` | `new` | Feed mode: new posts, popular (by stars), or from followed users |
| `sort` | - | `asc`, `desc` | `desc` | Sort order: oldest first (asc) or newest first (desc) |
| `tags` | - | Tag ID (e.g. `1`, `2`) | - | Filter by tag. Single ID. |
| `specialties` | - | Specialty ID (e.g. `1`, `2`) | - | Filter by specialty. Single ID. |
| `job` | - | `full-time`, `part-time`, `contract`, `freelance`, `internship`, `remote` | - | Filter by job type |
| `page` | - | Integer | `1` | Pagination (auto-managed by Livewire) |

**Example URLs:**
```
/posts
/posts?feed=popular
/posts?feed=following&sort=asc
/posts?tags=1&specialties=2
/posts?job=remote&feed=popular
/posts?feed=new&sort=asc&tags=3&specialties=5&job=full-time&page=2
```

---

## Search (`/search`)

**Route:** `GET /search`  
**Component:** `Livewire\Search`

| Parameter | Alias | Values | Default | Description |
|-----------|-------|--------|---------|-------------|
| `q` | - | String | - | Search query (searches posts and users) |
| `type` | - | `all`, `users`, `posts` | `all` | Result type: both, users only, or posts only |
| `search` | - | `true`, `1` | - | Opens the search modal when present |

**Example URLs:**
```
/search
/search?q=john
/search?q=laravel&type=posts
/search?q=john&type=users
/search?search=1&q=developer&type=users
```

---

## Explore Users (`/explore/users`)

**Route:** `GET /explore/users`  
**Component:** `Livewire\ExploreUsers`

| Parameter | Alias | Values | Default | Description |
|-----------|-------|--------|---------|-------------|
| `q` | - | String | - | Search by name or username |
| `role` | - | `seeker`, `employer`, `company`, `admin` | - | Filter by user role |
| `sort` | - | `newest`, `name`, `username`, `followers` | `newest` | Sort order: newest first, name A-Z, username A-Z, or most followers |
| `page` | - | Integer | `1` | Pagination |

**Example URLs:**
```
/explore/users
/explore/users?q=john
/explore/users?role=seeker
/explore/users?sort=followers
/explore/users?q=developer&role=company&sort=name
/explore/users?role=employer&sort=followers&page=2
```

---

## Quick Reference

| Page | Key Params |
|------|------------|
| Posts | `feed`, `sort`, `tags`, `specialties`, `job` |
| Search | `q`, `type`, `search` |
| Explore Users | `q`, `role`, `sort` |
