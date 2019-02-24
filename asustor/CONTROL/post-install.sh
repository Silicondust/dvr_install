#!/bin/sh

hdhr_bin_path="${APKG_PKG_DIR}/bin"
hdhr_etc_path="${APKG_PKG_DIR}/etc"
hdhr_www_path="${APKG_PKG_DIR}/www"
hdhr_tmp_path="${APKG_TEMP_DIR}"
hdhr_conf="HDHomeRunDVR.conf"


# Backup the Config file from previous if exists
if [[ -e $hdhr_tmp_path/$hdhr_conf ]] ; then
	cp $hdhr_tmp_path/$hdhr_conf $hdhr_etc_path
fi

# Patch the version number
sed -i "s!\(DVRUI_version\s*=\).*!\1\"$APKG_PKG_VER\";!" $hdhr_www_path/vars.php
# Patch the App storage location
sed -i "s!\(DVR_pkgPath\s*=\).*!\1\"$APKG_PKG_DIR\";!" $hdhr_www_path/vars.php


