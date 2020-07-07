BUILD_ROOT=$(PWD)
QPKG_BIN=qbuild
QPKG_VER=$(shell awk -F'=' '/^QPKG_VER/{print $$2}' ./qpkg.cfg | tr -d '"')
QPKG_NAME=HDHomeRunDVR-$(QPKG_VER).qpkg

# UI Params
UI_OUT_PATH=$(BUILD_ROOT)/shared/ui
UI_IN_PATH=$(BUILD_ROOT)/../common/php/simpleui
UI_SPECIFIC=$(BUILD_ROOT)/web
UI_VARS=vars.php

# Update this with any additional WGET parameters you need to use.. or place in local .wgetrc
BUILD_BIN_PATH=$(BUILD_ROOT)/shared
BUILD_OUT_PATH=$(BUILD_ROOT)/build

$(BUILD_OUT_PATH)/$(QPKG_NAME): $(UI_OUT_PATH)/$(UI_VARS)
	@echo "Building QPackage"
	@$(QPKG_BIN)

$(UI_OUT_PATH)/$(UI_VARS):
	@echo "Prepping UI Folder"
	@mkdir $(UI_OUT_PATH)
	@cp -R $(UI_IN_PATH)/* $(UI_OUT_PATH)
	
all: $(BUILD_OUT_PATH)/$(QPKG_NAME)

clean:
	@rm -rf $(UI_OUT_PATH) \
     $(BUILD_OUT_PATH)/$(QPKG_NAME) \
     $(BUILD_OUT_PATH)/$(QPKG_NAME).md5

.PHONY: all clean all_wrappers

.DEFAULT:
	@echo "You need to do something"
