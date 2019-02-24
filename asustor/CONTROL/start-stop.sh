#!/bin/sh

if [[ -z "${APKG_PKG_DIR}" ]] ; then
	current_path=$(dirname "$0")
	hdhr_bin_path="${current_path}/../bin"
	hdhr_etc_path="${current_path}/../etc"
else
	hdhr_bin_path="${APKG_PKG_DIR}/bin"
	hdhr_etc_path="${APKG_PKG_DIR}/etc"
fi

hdhr_bin="hdhomerun_record_linux"
hdhr_conf="HDHomeRunDVR.conf"

case "$1" in
  start)
  	$hdhr_bin_path/$hdhr_bin start --conf $hdhr_etc_path/$hdhr_conf
    ;;

  stop)
  	$hdhr_bin_path/$hdhr_bin stop
    ;;

  restart)
    $0 stop
    $0 start
    ;;

  status)
		$hdhr_bin_path/$hdhr_bin status

    ;;

  *)
    echo "Usage: $0 {start|stop|status|restart}"
    exit 1
    ;;
esac
exit 0