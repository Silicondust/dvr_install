#!/bin/sh

hdhr_bin_path="${APKG_PKG_DIR}/bin"
hdhr_etc_path="${APKG_PKG_DIR}/etc"
hdhr_tmp_path="${APKG_TEMP_DIR}"
hdhr_conf="HDHomeRunDVR.conf"


# Backup the Config file from previous
if [[ -e $hdhr_etc_path/$hdhr_conf ]] ; then
	cp $hdhr_etc_path/$hdhr_conf $hdhr_tmp_path
fi

