#!/bin/sh

hdhr_bin_path=$APKG_PKG_DIR
hdhr_bin


hdhr_web_path="$APKG_PKG_DIR/web"
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


start_engine() {
	$hdhr_bin_path/$hdhr_bin start --conf $hdhr_etc_path/$hdhr_conf
}

stop_engine() {
	$hdhr_bin_path/$hdhr_bin stop
}

status_engine() {
	$hdhr_bin_path/$hdhr_bin version
	$hdhr_bin_path/$hdhr_bin status
}