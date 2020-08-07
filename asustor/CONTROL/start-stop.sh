#!/bin/sh
hdhr_log="/tmp/hdhomerundvr_install.log"
hdhr_conf="HDHomeRunDVR.conf"
hdhr_user="hdhomerundvr"
http_grp="administrators"
LogPrefix="HDHomeRunDVR: "
hdhr_www_path="${APKG_PKG_DIR}/www"
hdhr_etc_path="${APKG_PKG_DIR}/etc"
CURR_USER=`id -u`

# Download URLs from Silicondust - Shouldn't change much
DownloadURL=https://download.silicondust.com/hdhomerun/hdhomerun_record_linux
BetaURL=https://download.silicondust.com/hdhomerun/hdhomerun_record_linux_beta

# Some additional params you can change
DVRBin=hdhomerun_record


###########################
# Verifies the config file exists and ensure
# is writable so Engine can update the StorageID
#
validate_config_file()
{
   echo "$LogPrefix ** Validating the Config File is available and set up correctly" >> $hdhr_log
   if [[ -e $hdhr_etc_path/$hdhr_conf ]] ; then
      echo "$LogPrefix Config File exists and is writable - is record path and port correct" >> $hdhr_log
      .  $hdhr_etc_path/$hdhr_conf
      # TODO: Validate RecordPath
      # TODO: Validate Port
   else
      # config file is missing
      echo "$LogPrefix Config is missing" >> $hdhr_log
      exit 1
   fi
}

###########################
# Get latest Record Engine(s) from SiliconDust, and delete any previous
# Will get Beta (if enabled in conf) and released engine and compare dates
# and select the newest amnd make it the default
#
update_engine()
{
	echo "$LogPrefix ** Installing the HDHomeRunDVR Record Engine" >> $hdhr_log
	echo "$LogPrefix Lets remove any existing engine - we're going to take the latest always.... " >> $hdhr_log
	rm -f  ${RecordPath}/${DVRBin}
	echo "$LogPrefix Checking it was deleted - if we can't remove it we can't update" >> $hdhr_log
	# TODO: check file was deleted - warn if not
	echo "$LogPrefix Downloading latest release" >> $hdhr_log
	wget -qO ${RecordPath}/${DVRBin}_rel ${DownloadURL}
	if [ "$BetaEngine" -eq "1" ]; then
		echo "$LogPrefix Downloading latest beta" >> $hdhr_log
		wget -qO ${RecordPath}/${DVRBin}_beta ${BetaURL}
		echo "$LogPrefix Comparing which is newest" >> $hdhr_log
		if [[ ${RecordPath}/${DVRBin}_rel -nt  ${RecordPath}/${DVRBin}_beta ]] ; then
			echo "$LogPrefix Release version is newer - selecting as record engine" >> $hdhr_log
			mv ${RecordPath}/${DVRBin}_rel ${RecordPath}/${DVRBin}
			rm ${RecordPath}/${RecordPath}_beta
		elif [[ ${RecordPath}/${DVRBin}_rel -ot  ${RecordPath}/${DVRBin}_beta ]]; then
			echo "$LogPrefix Beta version is newer - selecting as record engine" >> $hdhr_log
			mv ${RecordPath}/${DVRBin}_beta ${RecordPath}/${DVRBin}
			rm ${RecordPath}/${DVRBin}_rel
		else
			echo "$LogPrefix Both versions are same - using the Release version" >> $hdhr_log
			mv ${RecordPath}/${DVRBin}_rel ${RecordPath}/${DVRBin}
			rm ${RecordPath}/${DVRBin}_beta
		fi
	else
			echo "$LogPrefix Skipping check for beta - using the Release version" >> $hdhr_log
			mv ${RecordPath}/${DVRBin}_rel ${RecordPath}/${DVRBin}
	fi
	if [ ! -z "${RunAs}" ] ; then
		echo "$LogPrefix Changing binary owner to ${RunAs}" >> $hdhr_log
		chown ${RunAs} ${RecordPath}/${DVRBin}
		chgrp ${http_grp} ${RecordPath}/${DVRBin}
	fi
	chmod 750 ${RecordPath}/${DVRBin}
}

start_engine() {
  echo "$LogPrefix ==========================="  >> $hdhr_log
  echo "$LogPrefix Starting Engine: $currentTS"  >> $hdhr_log
  validate_config_file
  update_engine
	if [ ! -z "${RunAs}" ] ; then
		echo "$LogPrefix Starting the recordengine as ${RunAs}" >> $hdhr_log
		sudo -u ${RunAs} ${RecordPath}/${DVRBin} start --conf $hdhr_etc_path/$hdhr_conf
	else
		echo "$LogPrefix No RunAs user - running as user_id(${CURR_USER})" >> $hdhr_log
		${RecordPath}/${DVRBin} start --conf $hdhr_etc_path/$hdhr_conf
	fi
}

stop_engine() {
  echo "$LogPrefix Stopping Engine: "  >> $hdhr_log
	.  $hdhr_etc_path/$hdhr_conf
	${RecordPath}/${DVRBin} stop
}

status_engine() {
  echo "$LogPrefix Requesting Engine Status: "  >> $hdhr_log
	.  $hdhr_etc_path/$hdhr_conf
	${RecordPath}/${DVRBin} version
	${RecordPath}/${DVRBin} status
}


case "$1" in
  start)
  	start_engine
    ;;

  stop)
  	stop_engine
    ;;

  restart)
  	stop_engine
  	start_engine
    ;;

  status)
  	status_engine
    ;;

  *)
    echo "Usage: $0 {start|stop|status|restart}"
    exit 1
    ;;
esac
exit 0