#!/bin/bash
# Copyright (c) 2000-2016 Synology Inc. All rights reserved.

source /pkgscripts/include/pkg_util.sh

package="HDHomeRunDVR"
version="0.0.0001"
displayname="HDHomeRun DVR Manager"
maintainer="rik.dunphy@gmail.com"
arch="$(pkg_get_unified_platform)"
description="HDHomeRun DVR Manager for the Silicondust HDHomeRun DVR"
thirdparty="yes"
startable="yes"
[ "$(caller)" != "0 NULL" ] && return 0
pkg_dump_info
