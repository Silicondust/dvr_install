#!/bin/sh

hdhr_log="/tmp/hdhomerundvr_install.log"
hdhr_www_path="${APKG_PKG_DIR}/www"
hdhr_etc_path="${APKG_PKG_DIR}/etc"
hdhr_conf="HDHomeRunDVR.conf"
hdhr_user="hdhomerundvr"
http_grp="administrators"
LogPrefix="HDHomeRunDVR: "
CURR_USER=`id -u`
# Some additional params you can change
DVRBin=hdhomerun_record


echo "$LogPrefix PostInstall For: $APKG_PKG_DIR" >> $hdhr_log
echo "$LogPrefix Temp Folder: $APKG_TEMP_DIR" >> $hdhr_log

#create_web_ui
echo "$LogPrefix ** Create and Copy Web UI " >> $hdhr_log
echo "$LogPrefix Skip, handled automatically by installer " >> $hdhr_log

#update_vars_file
echo "$LogPrefix ** Update vars.php File " >> $hdhr_log
sed -i "s!\(DVRUI_version\s*=\).*!\1\"$APKG_PKG_VER\";!" $hdhr_www_path/vars.php
sed -i "s!\(DVR_pkgPath\s*=\).*!\1\"$APKG_PKG_DIR\";!" $hdhr_www_path/vars.php
sed -i "s!\(DVR_bin\s*=\).*!\1\"$DVRBin\";!" $hdhr_www_path/vars.php
sed -i "s!\(DVR_config\s*=\).*!\1\"etc/$hdhr_conf\";!" $hdhr_www_path/vars.php

#create_conf_file
echo "$LogPrefix ** Create First Conf File " >> $hdhr_log
echo "$LogPrefix check if we have existing config file" >> $hdhr_log
if [ ! -e  "$hdhr_etc_path/$hdhr_conf" ]; then
  echo "$LogPrefix check if we have backup config file" >> $hdhr_log
	if [ ! -e "$APKG_TEMP_DIR/$hdhr_conf" ]; then
	  echo "$LogPrefix Creating new Config File" >> $hdhr_log
		touch $hdhr_etc_path/$hdhr_conf 
		echo "RecordPath=/share/HDHomeRunDVR" >> $hdhr_etc_path/$hdhr_conf 
		echo "Port=59090" >> $hdhr_etc_path/$hdhr_conf 
		echo "RecordStreamsMax=16" >> $hdhr_etc_path/$hdhr_conf 
		echo "BetaEngine=1" >> $hdhr_etc_path/$hdhr_conf 
		echo "RunAs=$hdhr_user" >>  $hdhr_etc_path/$hdhr_conf 
	fi
fi

#restore_conf_file
echo "$LogPrefix ** Restore Config File " >> $hdhr_log
if [-e "$APKG_TEMP_DIR/$hdhr_conf" ]; then
	cp $APKG_TEMP_DIR/$hdhr_conf $hdhr_etc_path
fi

#adjust_permissions
echo "$LogPrefix ** Adjust Config File Permissions" >> $hdhr_log
chown $hdhr_user $hdhr_etc_path/$hdhr_conf
chgrp $http_grp $hdhr_etc_path/$hdhr_conf
chmod 664 $hdhr_etc_path/$hdhr_conf
