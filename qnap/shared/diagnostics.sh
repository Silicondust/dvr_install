#!/bin/sh
# Simple script to gather diagnostic information to determine
# v1.0

QPKG_CONF=/etc/config/qpkg.conf
CFG_BIN=getcfg
HDHR_QPKG_CFG=HDHomeRunDVR
HDHR_PATH=`$CFG_BIN -f $QPKG_CONF $HDHR_QPKG_CFG Install_path`
HDHR_SH=`$CFG_BIN -f $QPKG_CONF $HDHR_QPKG_CFG Shell`
HDHR_VER=`$CFG_BIN -f $QPKG_CONF $HDHR_QPKG_CFG Version`
HDHR_CONF=HDHomeRunDVR.conf
WEB_SHARE_PATH=/share/Web
WEB_UI_NAME=HDHomeRunDVR

# dump some default stuff
echo "QPKG installed to - $HDHR_PATH"
echo "QPKG Version Installed - $HDHR_VER"

# Check that there is a directory for the Install_path
echo -n "Checking $HDHR_PATH exists - "
if [ -d $HDHR_PATH ] ; then
	echo 'TRUE'
fi

# Checking Web Dir exists
echo -n "Checking $WEB_SHARE_PATH exists - "
if [ -d $WEB_SHARE_PATH ] ; then
	echo 'TRUE'
fi
if [ -L $WEB_SHARE_PATH ] ; then
	WEB_DIRNAME=$(dirname "$WEB_SHARE_PATH")
	SYM_WEB_PATH=$(readlink "$WEB_SHARE_PATH")
	TRUE_WEB_PATH="$WEB_DIRNAME/$SYM_WEB_PATH"
	echo -n "$WEB_SHARE_PATH points to $TRUE_WEB_PATH "
	if [ -d $TRUE_WEB_PATH ] ; then
		echo 'and EXISTS'
	fi
fi

# Check the HDHomeRun UI link exists in the Web folder
echo -n "Checking the UI link exists in Web Share - "
if [ -d $WEB_SHARE_PATH/$WEB_UI_NAME ] ; then
	echo "True"
	SYM_UI_PATH=$(readlink "$WEB_SHARE_PATH/$WEB_UI_NAME")
	echo -n "$WEB_SHARE_PATH/$WEB_UI_NAME points to $SYM_UI_PATH "
	if [ -d $SYM_UI_PATH ] ; then
		echo "and EXISTS"
		# Check for the style folder and permissions
		STYLE_PERMISSIONS=$(stat -c '%a' $WEB_SHARE_PATH/$WEB_UI_NAME/style)
		echo "$WEB_UI_NAME/style set $STYLE_PERMISSIONS"
		if [ -f $WEB_SHARE_PATH/$WEB_UI_NAME/style/style.css ] ; then
			CSS_PERMISSIONS=$(stat -c '%a' $WEB_SHARE_PATH/$WEB_UI_NAME/style/style.css)
			echo "CSS file exists and is set as $CSS_PERMISSIONS"
		fi
		# Check UI Vars
		echo -n "Checking UI Vars file exists - "
		if [ -f $WEB_SHARE_PATH/$WEB_UI_NAME/vars.php ] ; then
			echo "TRUE"
			echo "====== CONTENTS ======="
			cat $WEB_SHARE_PATH/$WEB_UI_NAME/vars.php
			echo "====== CONTENTS ======="
		fi
	fi
fi

# Check we have configfile and dump contents if available
echo -n "Checking record engine configfile exists "
if [ -f $HDHR_PATH/$HDHR_CONF ] ; then
	echo "TRUE"
	echo "====== CONTENTS ======="
	cat $HDHR_PATH/$HDHR_CONF
	echo "====== CONTENTS ======="
fi

# Check the record path in the Conf file
if [ -f $HDHR_PATH/$HDHR_CONF ] ; then
	source $HDHR_PATH/$HDHR_CONF
	echo -n "Checking $RecordPath exists "
	if [ -d $RecordPath ] ; then
		echo "TRUE"
	fi
fi

# Check we have a record engine and it's executable
# TODO