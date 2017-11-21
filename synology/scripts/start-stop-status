#!/bin/sh
SPK_PATH=/var/package/HDHomeRunDVR
HDHOMERUN_BIN=$SPK_PATH/target/bin/hdhomerun_record_linux
HDHOMERUN_CONF=$SPK_PATH/etc/HDHomeRunDVR.conf

case "$1" in
  start)
    : ADD START ACTIONS HERE
    $HDHOMERUN_BIN start --conf $HDHOMERUN_CONF
    ;;

  stop)
    : ADD STOP ACTIONS HERE
    $HDHOMERUN_BIN stop
    ;;

  restart)
    $0 stop
    $0 start
    ;;

  status)
    $HDHOMERUN_BIN version
    $HDHOMERUN_BIN status
    ;;

  *)
    echo "Usage: $0 {start|stop|status|restart}"
    exit 1
esac

exit 0
