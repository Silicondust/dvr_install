#!/bin/sh
####################
#
# Simple script to fetch the DVR backend and make it executable ahead
# of the QDK build
#
####################

# QDK Parameters
QDK_ROOT=$PWD
QDK_SHARED_PATH=$QDK_ROOT/shared

# DVR Parameters - update if SiliconDust changes the values
DVR_BIN=hdhomerun_record_linux
DVR_LINK=http://download.silicondust.com/hdhomerun/hdhomerun_record_linux

# Update this with any additional WGET parameters you need to use.. or place in local .wgetrc
WGET_OPTS=-q

######################
######################
# SCRIPT STARTS HERE #
######################
######################

echo "--- Moving to shared binary folder...."
cd $QDK_SHARED_PATH

echo "--- Removing previous $DVR_BIN if it exists..."
if [ -f $DVR_BIN ] ; then
  echo "--- Exists, deleting..."
  rm -f $DVR_BIN
fi

echo "--- Fetching $DVR_BIN from SiliconDust $DVR_LINK ..."
wget $WGET_OPTS $DVR_LINK

echo "--- Making $DVR_BIN executable..."
chmod a+x $DVR_BIN

echo "--- Done, returning to $QDK_ROOT."
cd $QDK_ROOT

echo "--- Patching UI vars"
VER_CHECK=$(shared/hdhomerun_record_linux version | grep -i version | awk '{print $4}')
VER_STR="\"$VER_CHECK\";"
sed -i "s!\(DVR_version=\).*!\1$VER_STR!" shared/ui/vars.php

