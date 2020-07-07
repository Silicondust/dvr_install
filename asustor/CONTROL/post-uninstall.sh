#!/bin/sh

hdhr_log="/tmp/hdhomerundvr_install.log"
hdhr_conf="HDHomeRunDVR.conf"
hdhr_user="hdhomerundvr"
LogPrefix="HDHomeRunDVR: "
CURR_USER=`id -u`
CMD_DELUSER=/bin/deluser

echo "$LogPrefix UnInstalling From: $APKG_PKG_DIR" >> $hdhr_log
echo "$LogPrefix Temp Folder: $APKG_TEMP_DIR" >> $hdhr_log


#delete_web_ui
echo "$LogPrefix ** Remove Web UI " >> $hdhr_log
echo "$LogPrefix Handled by Installer" >> $hdhr_log

#delete_recordings_path
echo "$LogPrefix ** Removing Recording Path Share" >> $hdhr_log
echo "$LogPrefix !!! Does Nothing - leave removing the dir to the user" >> $hdhr_log

#delete_dvr_user
echo "$LogPrefix ** Removing DVR User" >> $hdhr_log
echo "$LogPrefix Checking if $hdhr_user exists" >> $hdhr_log
UserCheck=`id $hdhr_user`
if [ ! -z "$UserCheck" ]; then
	echo "$LogPrefix User $hdhr_user exists" >> $hdhr_log
	echo "$LogPrefix Removing user" >> $hdhr_log
	$CMD_DELUSER $hdhr_user
fi
