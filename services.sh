#!/usr/bin/env bash
# On/Off MySQL + phpMyAdmin (native via mise, tanpa sudo/docker).
# artisan serve TIDAK termasuk di sini — jalankan manual terpisah.
# Pakai: ./services.sh {start|stop|restart|status}  atau  ./services.sh {up|down}
set -u

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
MYSQL_BIN="$HOME/.local/share/mise/installs/mysql/8.0.46/bin/mysqld"
MYSQL_CLIENT="$HOME/.local/share/mise/installs/mysql/8.0.46/bin/mysql"
DATADIR="$HOME/mysql-data"
SOCK="$DATADIR/mysql.sock"
LOGDIR="/tmp/opencode"
MYSQL_LOG="$LOGDIR/mysql.log"
PMA_LOG="$LOGDIR/phpmyadmin.log"

export PATH="$HOME/.local/bin:$HOME/.local/share/mise/shims:$PATH"

start_mysql() {
  if pgrep -f "mysqld --datadir=$DATADIR" >/dev/null; then
    echo "MySQL: sudah jalan"
  else
    mkdir -p "$LOGDIR"
    echo "MySQL: starting..."
    LD_LIBRARY_PATH="$HOME/.local/lib" nohup "$MYSQL_BIN" \
      --datadir="$DATADIR" --socket="$SOCK" --port=3306 \
      --bind-address=127.0.0.1 --mysqlx=OFF --user="$(whoami)" \
      > "$MYSQL_LOG" 2>&1 &
    for _ in $(seq 1 30); do
      if LD_LIBRARY_PATH="$HOME/.local/lib" "$MYSQL_CLIENT" -h127.0.0.1 -uroot -proot -e "SELECT 1;" >/dev/null 2>&1; then
        echo "MySQL: OK (127.0.0.1:3306)"
        return 0
      fi
      sleep 1
    done
    echo "MySQL: GAGAL start — cek $MYSQL_LOG"
    tail -n 15 "$MYSQL_LOG"
    return 1
  fi
}

start_pma() {
  if pgrep -f "php -S 127.0.0.1:8081" >/dev/null; then
    echo "phpMyAdmin: sudah jalan (http://localhost:8081)"
  else
    mkdir -p "$LOGDIR"
    echo "phpMyAdmin: starting..."
    cd "$ROOT" || exit 1
    nohup php -S 127.0.0.1:8081 -t phpmyadmin > "$PMA_LOG" 2>&1 &
    sleep 2
    code=$(curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:8081 || echo 000)
    echo "phpMyAdmin: HTTP $code (http://localhost:8081)"
  fi
}

stop_all() {
  echo "Stopping..."
  pkill -f "php -S 127.0.0.1:8081" 2>/dev/null && echo "phpMyAdmin: stopped" || echo "phpMyAdmin: sudah mati"
  pkill -f "mysqld --datadir=$DATADIR" 2>/dev/null && echo "MySQL: stopped" || echo "MySQL: sudah mati"
}

status_all() {
  echo "--- status ---"
  pgrep -af "mysqld --datadir=$DATADIR" >/dev/null \
    && echo "MySQL: JALAN (127.0.0.1:3306)" \
    || echo "MySQL: MATI"
  code=$(curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:8081 2>/dev/null || echo 000)
  if pgrep -f "php -S 127.0.0.1:8081" >/dev/null; then
    echo "phpMyAdmin: JALAN (http://localhost:8081, HTTP $code)"
  else
    echo "phpMyAdmin: MATI"
  fi
  echo "(artisan serve tidak dikelola script ini — cek manual: curl -I http://127.0.0.1:8000)"
}

case "${1:-status}" in
  start|up) start_mysql && start_pma ;;
  stop|down|off) stop_all ;;
  restart) stop_all; sleep 2; start_mysql && start_pma ;;
  status) status_all ;;
  *) echo "Pakai: $0 {start|stop|restart|status}"; exit 1 ;;
esac
