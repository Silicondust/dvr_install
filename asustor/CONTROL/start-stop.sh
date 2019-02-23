#!/bin/sh

hdhr_bin_path=$APKG_PKG_DIR
hdhr_web_path="$APKG_PKG_DIR/www"
hdhr_bin_path="$APKG_PKG_DIR/bin"
hdhr_etc_path="$APKG_PKG_DIR/etc"
hdhr_bin="hdhomerun_record_linux"
hdhr_conf="HDHomeRunDVR.conf"
http_user="http:http"
hdhr_user="http"
hdhr_wrap_arm7="hdhr_wrapper_arm7"
hdhr_wrap_arm8="hdhr_wrapper_arm8"
hdhr_wrap_i686="hdhr_wrapper_i686"
hdhr_wrap_x86_64="hdhr_wrapper_x86_64"
arch=`uname -m`
hdhr_arch_log=/tmp/hdhr_arch_choice

case "$1" in
  start)
  	echo "---starting engine---" >> $hdhr_arch_log
    ;;

  stop)
  	echo "---stopping engine---" >> $hdhr_arch_log
    ;;

  restart)
    $0 stop
    $0 start
    ;;

  status)
  	echo "---query engine---" >> $hdhr_arch_log
    ;;

  *)
    echo "Usage: $0 {start|stop|status|restart}"
    exit 1
    ;;
esac
exit 0