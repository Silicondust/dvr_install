#!/bin/sh
####################
#
# Simple script to fetch the prebuilt wrappers
#
####################

# SPK Parameters
SPK_ROOT=$PWD
SPK_SHARED_PATH=$SPK_ROOT/bin

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

echo "--- Moving to $SPK_SHARED_PATH folder...."
cd $SPK_SHARED_PATH

echo "--- Removing previous binaries if they exists..."
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

echo "--- Done, returning to $SPK_ROOT."
cd $SPK_ROOT

