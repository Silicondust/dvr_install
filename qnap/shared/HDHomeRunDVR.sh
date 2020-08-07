#!/bin/sh
CONF=/etc/config/qpkg.conf
QPKG_NAME="HDHomeRunDVR"
QPKG_ROOT=`/sbin/getcfg $QPKG_NAME Install_Path -f ${CONF}`
HDHOMERUN_CONF=HDHomeRunDVR.conf
CURR_USER='id -u'
hdhr_log=/tmp/hdhomerundvr_install.log
hdhr_grp=http

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
	echo "Validating the Config File is available and set up correctly"
	if	[[ -e	${QPKG_ROOT}/${HDHOMERUN_CONF} ]]	;	then
		echo "Config File exists and is writable - is record path and port correct"	
		.	 ${QPKG_ROOT}/${HDHOMERUN_CONF}
		#	TODO:	Validate RecordPath
		#	TODO:	Validate Port
	else
		#	config file	is missing
		echo "Config is	missing" 
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
	echo "-- Installing the HDHomeRunDVR Record Engine"
	echo "Lets remove any existing engine - we're going to take the latest always.... "
	rm -f  ${RecordPath}/${DVRBin}
	echo "Checking it was deleted - if we can't remove it we can't update"
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
			echo "$LogPrefix Not checking for Beta versions using the Release version" >> $hdhr_log
			mv ${RecordPath}/${DVRBin}_rel ${RecordPath}/${DVRBin}
			rm ${RecordPath}/${DVRBin}_beta
	fi
	chmod 755 ${RecordPath}/${DVRBin}
	if [ ! -z "${RunAs}" ] ; then
		echo "Changing binary owner to ${RunAs}"
		chown ${RunAs} ${RecordPath}/${DVRBin}
	fi
}

case "$1" in
	start)
		echo "Checking $QPKG_NAME is enabled"
		ENABLED=$(/sbin/getcfg $QPKG_NAME Enable -u -d FALSE -f $CONF)
		if [ "$ENABLED" != "TRUE" ]; then
				echo "$QPKG_NAME is disabled."
				exit 1
		fi
		validate_config_file
		update_engine
		if [ ! -z "${RunAs}" ] ; then
			 echo "Starting the recordengine as ${RunAs}"
			 sudo -u ${RunAs} ${RecordPath}/${DVRBin} start --conf $QPKG_ROOT/$HDHOMERUN_CONF
		else
			 echo "No RunAs user - running as user_id(${CURR_USER})"
			 ${RecordPath}/${DVRBin} start --conf $QPKG_ROOT/$HDHOMERUN_CONF
		fi
		;;
	stop)
		. ${QPKG_ROOT}/${HDHOMERUN_CONF}
		${RecordPath}/${DVRBin} stop
		;;

	restart)
		$0 stop
		$0 start
		;;

	status)
		. ${QPKG_ROOT}/${HDHOMERUN_CONF}
		${RecordPath}/${DVRBin} version
		${RecordPath}/${DVRBin} status
		;;

	*)
		echo "Usage: $0 {start|stop|status|restart}"
		exit 1
esac

exit 0
