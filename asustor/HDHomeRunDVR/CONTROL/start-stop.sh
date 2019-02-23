#!/bin/sh

package_path=$(dirname "$0")
. $package_path/common.sh

case "$1" in
  start)
  	start_engine
    ;;

  stop)
  	stop_engine
    ;;

  restart)
    $0 stop
    $0 start
    ;;

  status)
  	status_engine
    ;;

  *)
    echo "Usage: $0 {start|stop|status|restart}"
    exit 1
esac

exit 0