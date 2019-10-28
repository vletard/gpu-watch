#!/bin/bash
### BEGIN INIT INFO
# Provides:          gpu-watch
# Required-Start:    $local_fs $network
# Required-Stop:     $local_fs
# Default-Start:     2 3 4 5
# Default-Stop:      0 1 6
# Short-Description: GPU availability daemon
# Description:       Periodically checks the GPU availability and notifies the status to the web server.
### END INIT INFO

NAME=gpu-watch
PIDFILE=/run/$NAME.pid
LOGFILE=/run/$NAME.log
DELAY=1m

function is_gpu_free() {
  if (( $(nvidia-smi --query-compute-apps=used_memory --format=csv,noheader | wc -l) == 0 ))
  then
    echo true
  else
    echo false
  fi
}

function notifier_daemon() {
  while sleep $DELAY
  do
    status=0
    if $(is_gpu_free)
    then
      status=0
    else
      status=1
    fi
    request="https://factoriel5.duckdns.org/gpu-update.php?hostname=$(hostname)&status=$status"
    curl -s --max-time 60 $request
    status=$?
    if $(($status != 0))
    then
      echo "curl exited with error code $status" >&2
    fi
  done
}

start() {
  notifier_daemon 2> $LOGFILE &
  echo $! > $PIDFILE
}

stop() {
  if test -f $PIDFILE
  then
    kill $(cat $PIDFILE)
  fi
  rm -f $PIDFILE
}

case "$1" in 
    start)
       start
       ;;
    stop)
       stop
       ;;
    restart)
       stop
       start
       ;;
    *)
       echo "Usage: $0 {start|stop|restart}"
esac

exit 0
