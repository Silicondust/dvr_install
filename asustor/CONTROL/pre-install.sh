#!/bin/sh

hdhr_log="/tmp/hdhomerundvr_install.log"
hdhr_etc_path="${APKG_PKG_DIR}/etc"
hdhr_conf="HDHomeRunDVR.conf"
hdhr_user="hdhomerundvr"
LogPrefix="HDHomeRunDVR: "
CURR_USER=`id -u`
CMD_ADDUSER=/bin/adduser

echo "$LogPrefix PreInstall To: $APKG_PKG_DIR" >> $hdhr_log
echo "$LogPrefix Temp Folder: $APKG_TEMP_DIR" >> $hdhr_log

#create_dvr_user
echo "$LogPrefix ** Create DVR User " >> $hdhr_log
echo "$LogPrefix Checking if $hdhr_user exists" >> $hdhr_log
UserCheck=`id $hdhr_user`
if [ -z "$UserCheck" ]; then
	echo "$LogPrefix User $hdhr_user doesn't exist" >> $hdhr_log
	echo "$LogPrefix Creating user" >> $hdhr_log
	$CMD_ADDUSER -HD $hdhr_user
fi

#create_recordings_path
echo "$LogPrefix ** Create Recording Path Share" >> $hdhr_log
echo "$LogPrefix No need to create - handled automatically by installer" >> $hdhr_log


#backup_conf_file
echo "$LogPrefix ** Backup Config File " >> $hdhr_log
if [ -e "$hdhr_etc_path/$hdhr_conf" ] ; then
	echo "$LogPrefix Existing config file found" >> $hdhr_log
	echo "$LogPrefix Copying file to preserve during update" >> $hdhr_log
	cp $hdhr_etc_path/$hdhr_conf $APKG_TEMP_DIR/$hdhr_conf
fi


