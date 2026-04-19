#!/usr/bin/env bash

# ─────────────────────────────────────────────
#  Laravel Dev Runner
#  Starts all services in parallel, each in its
#  own named tmux window (falls back to plain
#  background processes if tmux is unavailable).
# ─────────────────────────────────────────────

set -euo pipefail

# ── Colour helpers ────────────────────────────
RED='\033[0;31m'; GREEN='\033[0;32m'; YELLOW='\033[1;33m'
CYAN='\033[0;36m'; BOLD='\033[1m'; RESET='\033[0m'

log()  { echo -e "${CYAN}${BOLD}[dev]${RESET} $*"; }
ok()   { echo -e "${GREEN}${BOLD}[✔]${RESET}  $*"; }
warn() { echo -e "${YELLOW}${BOLD}[!]${RESET}  $*"; }
err()  { echo -e "${RED}${BOLD}[✘]${RESET}  $*"; }

# ── Verify we are inside a Laravel project ────
if [[ ! -f "artisan" ]]; then
  err "No 'artisan' file found. Run this script from your Laravel project root."
  exit 1
fi

# ── Services to start ─────────────────────────
declare -A SERVICES=(
  ["octane"]="php artisan octane:start --watch"
  ["horizon"]="php artisan horizon"
  ["schedule"]="php artisan schedule:work"
  ["vite"]="npm run dev"
  ["reverb"]="php artisan reverb:start"
)

# Preferred display order
ORDER=(octane horizon schedule vite reverb)

# Log directory
LOG_DIR="storage/logs/dev"
mkdir -p "$LOG_DIR"

# ── PID tracking file ─────────────────────────
PID_FILE=".dev_pids"
> "$PID_FILE"   # truncate / create

# ── Cleanup on EXIT / SIGINT / SIGTERM ────────
cleanup() {
  echo ""
  warn "Shutting down all services…"
  if [[ -f "$PID_FILE" ]]; then
    while IFS= read -r pid; do
      if kill -0 "$pid" 2>/dev/null; then
        kill "$pid" 2>/dev/null && ok "Stopped PID $pid"
      fi
    done < "$PID_FILE"
    rm -f "$PID_FILE"
  fi
  ok "All services stopped. Goodbye!"
}
trap cleanup EXIT INT TERM

# ── tmux mode (preferred) ─────────────────────
start_with_tmux() {
  local session="laravel-dev"

  # Kill any stale session
  tmux kill-session -t "$session" 2>/dev/null || true

  log "Starting tmux session '${BOLD}${session}${RESET}'"

  local first=true
  for name in "${ORDER[@]}"; do
    local cmd="${SERVICES[$name]}"
    if $first; then
      tmux new-session  -d -s "$session" -n "$name" "bash -c '${cmd}; read'"
      first=false
    else
      tmux new-window   -t "$session"    -n "$name" "bash -c '${cmd}; read'"
    fi
    ok "  ${BOLD}${name}${RESET}  →  ${cmd}"
  done

  # Focus the first window
  tmux select-window -t "${session}:octane"

  echo ""
  log "All services running inside tmux session ${BOLD}${session}${RESET}."
  echo -e "  ${YELLOW}Attach   :${RESET}  tmux attach -t ${session}"
  echo -e "  ${YELLOW}Kill all :${RESET}  tmux kill-session -t ${session}"
  echo ""
  warn "This script will now keep the session alive. Press Ctrl-C to stop everything."

  # Keep the script alive so the trap fires on Ctrl-C
  tmux attach-session -t "$session"
}

# ── Plain background mode (fallback) ──────────
start_plain() {
  warn "tmux not found — starting services as background processes."
  warn "Logs → ${LOG_DIR}/<service>.log"
  echo ""

  for name in "${ORDER[@]}"; do
    local cmd="${SERVICES[$name]}"
    local logfile="${LOG_DIR}/${name}.log"

    # shellcheck disable=SC2086
    bash -c "$cmd" >> "$logfile" 2>&1 &
    local pid=$!
    echo "$pid" >> "$PID_FILE"
    ok "  ${BOLD}${name}${RESET}  (PID ${pid})  →  ${cmd}"
    ok "       log: ${logfile}"
  done

  echo ""
  log "All ${#ORDER[@]} services started. Press ${BOLD}Ctrl-C${RESET} to stop them all."

  # Wait so the trap can clean up properly
  wait
}

# ── Entry point ───────────────────────────────
echo ""
echo -e "${BOLD}${CYAN}╔══════════════════════════════════╗${RESET}"
echo -e "${BOLD}${CYAN}║      Laravel Dev Runner  🚀       ║${RESET}"
echo -e "${BOLD}${CYAN}╚══════════════════════════════════╝${RESET}"
echo ""

log "Project root: $(pwd)"
echo ""

if command -v tmux &>/dev/null; then
  start_with_tmux
else
  start_plain
fi