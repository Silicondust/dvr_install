#!/bin/sh
####################
#
# Simple script to fetch the DVR backend and make it executable ahead
# of the SPK build
#
####################

# QDK Parameters
SPK_ROOT=$PWD
SPK_SHARED_PATH=$SPK_ROOT/bin

# DVR Parameters - update if SiliconDust changes the values
DVR_BIN=hdhomerun_record_linux
DVR_LINK=http://download.silicondust.com/hdhomerun/hdhomerun_record_linux_beta

# Update this with any additional WGET parameters you need to use.. or place in local .wgetrc
WGET_OPTS=-q

######################
######################
# SCRIPT STARTS HERE #
######################
######################

echo "--- Moving to shared binary folder...."
cd $SPK_SHARED_PATH

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
cd $SPK_ROOT

