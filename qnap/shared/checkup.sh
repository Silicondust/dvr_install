#!/bin/sh
QPKG_CONF=/etc/config/qpkg.conf
QPKG_NAME=HDHomeRunDVR
QPKG_ROOT=`/sbin/getcfg $QPKG_NAME Install_Path -f $QPKG_CONF`
HDHOMERUN_CONF=HDHomeRunDVR.conf
DVRBin=hdhomerun_record

MyUser=`whoami`
# Is the package installed 
if [ -z "$QPKG_NAME" ] ; then
	echo "$QPKG_NAME is not installed - eiting"
	exit 1
fi
# Can we access the QPKG files
echo "Checking package $QPKG_NAME root folder $QPKG_ROOT"
if [ -d $QPKG_ROOT ]; then
  echo "$QPKG_ROOT is available and writable"
fi
# Check we have HDHomeRunDVR.conf
# Is it writable by user, group, http
# Does recordpath exist
# Is it writable by user, group, http
