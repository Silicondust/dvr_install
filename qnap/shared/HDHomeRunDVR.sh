#!/bin/sh
CONF=/etc/config/qpkg.conf
QPKG_NAME="HDHomeRunDVR"
QPKG_ROOT=`/sbin/getcfg $QPKG_NAME Install_Path -f ${CONF}`
HDHOMERUN_BIN=hdhomerun_record_linux
HDHOMERUN_CONF=HDHomeRunDVR.conf

case "$1" in
  start)
    ENABLED=$(/sbin/getcfg $QPKG_NAME Enable -u -d FALSE -f $CONF)
    if [ "$ENABLED" != "TRUE" ]; then
        echo "$QPKG_NAME is disabled."
        exit 1
    fi
    : ADD START ACTIONS HERE
    $QPKG_ROOT/$HDHOMERUN_BIN start --conf $QPKG_ROOT/$HDHOMERUN_CONF
    ;;

  stop)
    : ADD STOP ACTIONS HERE
    $QPKG_ROOT/$HDHOMERUN_BIN stop
    ;;

  restart)
    $0 stop
    $0 start
    ;;

  status)
    $QPKG_ROOT/$HDHOMERUN_BIN version
    $QPKG_ROOT/$HDHOMERUN_BIN status
    ;;

  *)
    echo "Usage: $0 {start|stop|status|restart}"
    exit 1
esac

exit 0
