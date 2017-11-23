#!/bin/sh

# Build the SPK
SPK_ROOT=$PWD
TMP_PKG_PATH=$PWD/tmp_pkg
TMP_SPK_PATH=$PWD/tmp_spk
BUILD_PATH=$PWD/build
SPK_PKG_NAME=HDHomeRunDVR.spk

#Get the binary
sh $PWD/fetch_record_engine.sh

#Check Package Exists

#copy files to tmp
mkdir $TMP_PKG_PATH
mkdir $TMP_SPK_PATH
cp -R bin $TMP_PKG_PATH
cp -R etc $TMP_PKG_PATH
cp -R web $TMP_PKG_PATH

#Build the package
cd $TMP_PKG_PATH
tar cvfz $TMP_SPK_PATH/package.tgz *
cd $SPK_ROOT

#Move files ready for the SPK
cp -R scripts $TMP_SPK_PATH
cp -R conf $TMP_SPK_PATH
cp icons/* $TMP_SPK_PATH
cp INFO $TMP_SPK_PATH

#Build SPK
cd $TMP_SPK_PATH
tar cvf $BUILD_PATH/$SPK_PKG_NAME *
cd $SPK_ROOT


