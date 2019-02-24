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
hdhr_user="admin"
hdhr_wrap_arm7="hdhr_wrapper_arm7"
hdhr_wrap_arm8="hdhr_wrapper_aarch64"
hdhr_wrap_x86_64="hdhr_wrapper_x86_64"
hdhr_wrap_x86="hdhr_wrapper_x86"
arch=`uname -m`
userid=`id -u`
hdhr_arch_log=/tmp/hdhr_arch_choice


case "$1" in
  start)
  	if [[ $userid -ne 0 ]]; then
  		$hdhr_bin_path/$hdhr_bin start --conf $hdhr_etc_path/$hdhr_conf
  	else
  		if [[ -z $(echo "${arch}" | sed "/armv7/d" ) ]] ; then
  			echo "Determined Platform is ARM from $arch" >> $hdhr_arch_log
  			$hdhr_bin_path/$hdhr_wrap_arm -u $hdhr_user -b $hdhr_bin_path/$hdhr_bin -- start --conf $hdhr_etc_path/$hdhr_conf
  		elif [[ -z $(echo "${arch}" | sed "/aarch64/d" ) ]] ; then
  			echo "Determined Platform is ARM 64bit from $arch" >> $hdhr_arch_log
  			echo "ARM 64Bit wrappers not supported just yet - defaulting to no wrapper" >> $hdhr_arch_log
  			$hdhr_bin_path/$hdhr_bin start --conf $hdhr_etc_path/$hdhr_conf
  		elif [[ -z $(echo "${arch}" | sed "/x86_64/d" ) ]] ; then
  			echo "Determined Platform is x86_64 from $arch" >> $hdhr_arch_log
  			$hdhr_bin_path/$hdhr_wrap_x86_64 -u $hdhr_user -b $hdhr_bin_path/$hdhr_bin -- start --conf $hdhr_etc_path/$hdhr_conf
  		elif [[ -z $(echo "${arch}" | sed "/x86/d" ) ]] ; then
  			echo "Determined Platform is x86 from $arch" >> $hdhr_arch_log
  			$hdhr_bin_path/$hdhr_wrap_x86 -u $hdhr_user -b $hdhr_bin_path/$hdhr_bin -- start --conf $hdhr_etc_path/$hdhr_conf
  		else
  			echo "Unable to determine the platform - will default to no wrapper" >> $hdhr_arch_log
  			$hdhr_bin_path/$hdhr_bin start --conf $hdhr_etc_path/$hdhr_conf
  		fi
  	fi
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