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
WRAPPER_BIN_ARM=hdhr_wrapper_arm
WRAPPER_BIN_X86=hdhr_wrapper_x86_64
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
if [ -f $WRAPPER_BIN_ARM ] ; then
  echo "--- arm binary exists, deleting..."
  rm -f $WRAPPER_BIN_ARM
fi
if [ -f $WRAPPER_BIN_X86 ] ; then
  echo "--- x86_64 binary exists, deleting..."
  rm -f $WRAPPER_BIN_X86
fi

echo "--- Fetching binaries from SiliconDust $WRAPPER_REPO_LINK/ ..."
wget $WGET_OPTS $WRAPPER_REPO_LINK/$WRAPPER_BIN_X86
wget $WGET_OPTS $WRAPPER_REPO_LINK/$WRAPPER_BIN_ARM

echo "--- Making binaries executable..."
chmod a+x $WRAPPER_BIN_ARM
chmod a+x $WRAPPER_BIN_X86

echo "--- Done, returning to $QDK_ROOT."
cd $QDK_ROOT

