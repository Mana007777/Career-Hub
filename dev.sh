#!/usr/bin/env bash

# ─────────────────────────────────────────────
# Laravel Dev Runner (Stable + Conflict Safe)
# ─────────────────────────────────────────────

set -euo pipefail

# ── Colors ───────────────────────────────────
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
CYAN='\033[0;36m'
BOLD='\033[1m'
RESET='\033[0m'

log()  { echo -e "${CYAN}[dev]${RESET} $*"; }
ok()   { echo -e "${GREEN}[✔]${RESET} $*"; }
warn() { echo -e "${YELLOW}[!]${RESET} $*"; }
err()  { echo -e "${RED}[✘]${RESET} $*"; }

# ── Must run in project root ─────────────────
if [[ ! -f "artisan" ]]; then
  err "Run this inside Laravel project root"
  exit 1
fi

# ── Prevent tmux nesting ─────────────────────
if [[ -n "${TMUX:-}" ]]; then
  err "You are already inside tmux. Exit or detach first."
  exit 1
fi

# ── Ports ────────────────────────────────────
OCTANE_PORT=8000
VITE_PORT=5173

# ── Kill old services (IMPORTANT FIX) ────────
log "Cleaning old Laravel dev processes..."

pkill -f "octane:start" 2>/dev/null || true
pkill -f "horizon" 2>/dev/null || true
pkill -f "reverb:start" 2>/dev/null || true
pkill -f "schedule:work" 2>/dev/null || true
pkill -f "vite" 2>/dev/null || true

# Free ports if stuck
fuser -k $OCTANE_PORT/tcp 2>/dev/null || true
fuser -k $VITE_PORT/tcp 2>/dev/null || true

# ── Services ─────────────────────────────────
declare -A SERVICES=(
  ["octane"]="php artisan octane:start --watch --host=0.0.0.0 --port=$OCTANE_PORT"
  ["horizon"]="php artisan horizon"
  ["schedule"]="php artisan schedule:work"
  ["vite"]="npm run dev"
  ["reverb"]="php artisan reverb:start"
)

ORDER=(octane horizon schedule vite reverb)

# ── Logs ─────────────────────────────────────
LOG_DIR="storage/logs/dev"
mkdir -p "$LOG_DIR"

PID_FILE=".dev_pids"
> "$PID_FILE"

# ── Cleanup ──────────────────────────────────
cleanup() {
  echo ""
  warn "Stopping services..."

  if [[ -f "$PID_FILE" ]]; then
    while read -r pid; do
      kill "$pid" 2>/dev/null || true
    done < "$PID_FILE"
    rm -f "$PID_FILE"
  fi

  ok "Stopped all services"
}
trap cleanup EXIT INT TERM

# ─────────────────────────────────────────────
# TMUX MODE (preferred)
# ─────────────────────────────────────────────
start_tmux() {
  local session="laravel-dev"

  tmux kill-session -t "$session" 2>/dev/null || true

  log "Starting tmux session: $session"

  for name in "${ORDER[@]}"; do
    local cmd="${SERVICES[$name]}"

    if [[ "$name" == "octane" ]]; then
      tmux new-session -d -s "$session" -n "$name" bash -lc "cd $(pwd) && $cmd"
    else
      tmux new-window -t "$session" -n "$name" bash -lc "cd $(pwd) && $cmd"
    fi

    ok "$name → $cmd"
  done

  tmux select-window -t "${session}:octane"

  echo ""
  log "Attach: tmux attach -t $session"
  echo ""

  tmux attach -t "$session"
}

# ─────────────────────────────────────────────
# FALLBACK MODE
# ─────────────────────────────────────────────
start_plain() {
  warn "tmux not found → running background mode"

  for name in "${ORDER[@]}"; do
    cmd="${SERVICES[$name]}"
    logfile="${LOG_DIR}/${name}.log"

    bash -lc "cd $(pwd) && $cmd" >> "$logfile" 2>&1 &
    pid=$!

    echo "$pid" >> "$PID_FILE"

    ok "$name started (PID $pid)"
  done

  echo ""
  log "Running in background. Logs: $LOG_DIR"
  wait
}

# ─────────────────────────────────────────────
# ENTRY
# ─────────────────────────────────────────────
echo ""
echo -e "${BOLD}${CYAN}Laravel Dev Runner 🚀${RESET}"
echo ""

log "Project: $(pwd)"
echo ""

if command -v tmux &>/dev/null; then
  start_tmux
else
  start_plain
fi