#!/bin/sh
####################
#
# Simple script to fetch the prebuilt wrappers
#
####################

# QDK Parameters
QDK_ROOT=$PWD
QDK_SHARED_PATH=$QDK_ROOT/shared

# Wrapper Parameters
WRAPPER_BIN_ARM7=hdhr_wrapper_arm7
WRAPPER_BIN_ARM8=hdhr_wrapper_arm8
WRAPPER_BIN_i686=hdhr_wrapper_i686
WRAPPER_BIN_X86_64=hdhr_wrapper_x86_64
WRAPPER_REPO_LINK=http://www.irish-networx.com/hdhr_wrapper

# Update this with any additional WGET parameters you need to use.. or place in local .wgetrc
WGET_OPTS=-q

######################
######################
# SCRIPT STARTS HERE #
######################
######################

echo "--- Moving to $QDK_SHARED_PATH folder...."
cd $QDK_SHARED_PATH

echo "--- Removing previous $DVR_BIN if it exists..."
if [ -f $WRAPPER_BIN_ARM7 ] ; then
  echo "--- arm binary exists, deleting..."
  rm -f $WRAPPER_BIN_ARM7
fi
if [ -f $WRAPPER_BIN_X86_64 ] ; then
  echo "--- x86_64 binary exists, deleting..."
  rm -f $WRAPPER_BIN_X86_64
fi
if [ -f $WRAPPER_BIN_i686 ] ; then
  echo "--- i686 binary exists, deleting..."
  rm -f $WRAPPER_BIN_i686
fi

echo "--- Fetching binaries from SiliconDust $WRAPPER_REPO_LINK/ ..."
wget $WGET_OPTS $WRAPPER_REPO_LINK/$WRAPPER_BIN_X86_64
wget $WGET_OPTS $WRAPPER_REPO_LINK/$WRAPPER_BIN_i686
wget $WGET_OPTS $WRAPPER_REPO_LINK/$WRAPPER_BIN_ARM7

echo "--- Making binaries executable..."
chmod a+x $WRAPPER_BIN_ARM7
chmod a+x $WRAPPER_BIN_i686
chmod a+x $WRAPPER_BIN_X86_64

echo "--- Done, returning to $QDK_ROOT."
cd $QDK_ROOT

