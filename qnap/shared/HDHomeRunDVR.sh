#!/bin/sh
CONF=/etc/config/qpkg.conf
QPKG_NAME="HDHomeRunDVR"
QPKG_ROOT=`/sbin/getcfg $QPKG_NAME Install_Path -f ${CONF}`
HDHOMERUN_BIN=hdhomerun_record_linux
HDHOMERUN_WRAP_ARMv7=hdhr_wrapper_arm7
HDHOMERUN_WRAP_i686=hdhr_wrapper_i686
HDHOMERUN_WRAP_X86_64=hdhr_wrapper_x86_64
HDHOMERUN_USER=httpdusr
HDHOMERUN_CONF=HDHomeRunDVR.conf
ARCH=`uname -m`


case "$1" in
  start)
    ENABLED=$(/sbin/getcfg $QPKG_NAME Enable -u -d FALSE -f $CONF)
    if [ "$ENABLED" != "TRUE" ]; then
        echo "$QPKG_NAME is disabled."
        exit 1
    fi
    : ADD START ACTIONS HERE
    if [[ $EUID -ne 0 ]]; then
			$QPKG_ROOT/$HDHOMERUN_BIN start --conf $QPKG_ROOT/$HDHOMERUN_CONF
		else
			if [[ "$ARCH" =~ "armv7"* ]]; then
				echo "Determined Platform is ARM from $ARCH"
				$QPKG_ROOT/$HDHOMERUN_WRAP_ARMv7 -u $HDHOMERUN_USER -b $QPKG_ROOT/$HDHOMERUN_BIN -- start --conf $QPKG_ROOT/$HDHOMERUN_CONF
			elif [[ "$ARCH" =~ "x86_64"* ]]; then
				echo "Determined Platform is x86_64 from $ARCH"
				$QPKG_ROOT/$HDHOMERUN_WRAP_X86_64 -u $HDHOMERUN_USER -b $QPKG_ROOT/$HDHOMERUN_BIN -- start --conf $QPKG_ROOT/$HDHOMERUN_CONF
			elif [[ "$ARCH" =~ "i686"* ]]; then
				echo "Determined Platform is i686 from $ARCH"
				$QPKG_ROOT/$HDHOMERUN_WRAP_i686 -u $HDHOMERUN_USER -b $QPKG_ROOT/$HDHOMERUN_BIN -- start --conf $QPKG_ROOT/$HDHOMERUN_CONF
			else
				echo "Unable to determine the platform - will default to no wrapper"
				$QPKG_ROOT/$HDHOMERUN_BIN start --conf $QPKG_ROOT/$HDHOMERUN_CONF
			fi
		fi
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
