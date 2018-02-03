#!/bin/sh
####################
#
# Simple script to fetch the prebuilt wrappers
#
####################

# QDK Parameters
QDK_ROOT=$PWD
QDK_X86_PATH=$QDK_ROOT/x86
QDK_X86_64_PATH=$QDK_ROOT/x86_64

# Wrapper Parameters
WRAPPER_BIN=hdhr_wrapper
WRAPPER_REPO_LINK=http://www.irish-networx.com/hdhr_wrapper

##
# ARCH specific wrappers
X86_WRAPPER_BIN=$WRAPPER_REPO_LINK/qnap/x86/$WRAPPER_BIN
X86_64_WRAPPER_BIN=$WRAPPER_REPO_LINK/qnap/x86_64/$WRAPPER_BIN

# Update this with any additional WGET parameters you need to use.. or place in local .wgetrc
WGET_OPTS=-q

######################
######################
# SCRIPT STARTS HERE #
######################
######################


#### x86 ####
echo "--- Moving to $QDK_X86_PATH folder...."
cd $QDK_X86_PATH

echo "--- Removing previous $DVR_BIN if it exists..."
if [ -f $WRAPPER_BIN ] ; then
  echo "--- Exists, deleting..."
  rm -f $WRAPPER_BIN
fi

echo "--- Fetching $DVR_BIN from SiliconDust $DVR_LINK ..."
wget $WGET_OPTS $X86_WRAPPER_BIN

echo "--- Making $DVR_BIN executable..."
chmod a+x $WRAPPER_BIN

echo "--- Done, returning to $QDK_ROOT."
cd $QDK_ROOT

#### x86_64 ####
echo "--- Moving to $QDK_X86_64_PATH folder...."
cd $QDK_X86_64_PATH

echo "--- Removing previous $DVR_BIN if it exists..."
if [ -f $WRAPPER_BIN ] ; then
  echo "--- Exists, deleting..."
  rm -f $WRAPPER_BIN
fi

echo "--- Fetching $DVR_BIN from SiliconDust $DVR_LINK ..."
wget $WGET_OPTS $X86_64_WRAPPER_BIN

echo "--- Making $DVR_BIN executable..."
chmod a+x $WRAPPER_BIN

echo "--- Done, returning to $QDK_ROOT."
cd $QDK_ROOT

