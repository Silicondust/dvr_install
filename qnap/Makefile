BUILD_ROOT=$(PWD)
QPKG_BIN=qbuild
QPKG_VER=$(shell awk -F'=' '/^QPKG_VER/{print $$2}' ./qpkg.cfg | tr -d '"')
QPKG_NAME=HDHomeRunDVR-$(QPKG_VER).qpkg

# DVR Parameters - update if SiliconDust changes the values
DVR_BIN=hdhomerun_record_linux
DVR_LINK=http://download.silicondust.com/hdhomerun/hdhomerun_record_linux_beta

# Wrapper Parameters
WRAPPER_BIN_ARM7=hdhr_wrapper_arm7
WRAPPER_BIN_ARM8=hdhr_wrapper_arm8
WRAPPER_BIN_i686=hdhr_wrapper_i686
WRAPPER_BIN_X86_64=hdhr_wrapper_x86_64
WRAPPER_REPO_LINK=http://www.irish-networx.com/hdhr_wrapper

# UI Params
UI_OUT_PATH=$(BUILD_ROOT)/shared/ui
UI_IN_PATH=$(BUILD_ROOT)/../common/php/simpleui
UI_SPECIFIC=$(BUILD_ROOT)/web
UI_VARS=vars.php

# Update this with any additional WGET parameters you need to use.. or place in local .wgetrc
WGET_BIN=wget
WGET_DVR_OPTS=-q -O $(BUILD_BIN_PATH)/$(DVR_BIN)
WGET_WRAPPER_OPTS=-q -P $(BUILD_BIN_PATH)
CHMOD_BIN=chmod
CHMOD_OPTS=a+x
BUILD_BIN_PATH=$(BUILD_ROOT)/shared
BUILD_OUT_PATH=$(BUILD_ROOT)/build

$(BUILD_OUT_PATH)/$(QPKG_NAME): $(BUILD_BIN_PATH)/$(DVR_BIN) all_wrappers $(UI_OUT_PATH)/$(UI_VARS)
	@echo "Building QPackage"
	@$(QPKG_BIN)

$(UI_OUT_PATH)/$(UI_VARS):
	@echo "Prepping UI Folder"
	@mkdir $(UI_OUT_PATH)
	@cp -R $(UI_IN_PATH)/* $(UI_OUT_PATH)
	@cp $(UI_SPECIFIC)/$(UI_VARS) $(UI_OUT_PATH)

$(BUILD_BIN_PATH)/$(DVR_BIN):
	@echo "Fetching $(DVR_BIN)"
	@$(WGET_BIN) $(WGET_DVR_OPTS) $(DVR_LINK)
	@$(CHMOD_BIN) $(CHMOD_OPTS) $@

$(BUILD_BIN_PATH)/$(WRAPPER_BIN_ARM7):
	@echo "Fetching $(WRAPPER_BIN_ARM7)"
	@$(WGET_BIN) $(WGET_WRAPPER_OPTS) $(WRAPPER_REPO_LINK)/$(WRAPPER_BIN_ARM7)
	@$(CHMOD_BIN) $(CHMOD_OPTS) $@
	
$(BUILD_BIN_PATH)/$(WRAPPER_BIN_ARM8):
	@echo "Fetching $(WRAPPER_BIN_ARM8)"
	@$(WGET_BIN) $(WGET_WRAPPER_OPTS) $(WRAPPER_REPO_LINK)/$(WRAPPER_BIN_ARM8)
	@$(CHMOD_BIN) $(CHMOD_OPTS) $@

$(BUILD_BIN_PATH)/$(WRAPPER_BIN_i686):
	@echo "Fetching $(WRAPPER_BIN_i686)"
	@$(WGET_BIN) $(WGET_WRAPPER_OPTS) $(WRAPPER_REPO_LINK)/$(WRAPPER_BIN_i686)
	@$(CHMOD_BIN) $(CHMOD_OPTS) $@

$(BUILD_BIN_PATH)/$(WRAPPER_BIN_X86_64):
	@echo "Fetching $(WRAPPER_BIN_X86_64)"
	@$(WGET_BIN) $(WGET_WRAPPER_OPTS) $(WRAPPER_REPO_LINK)/$(WRAPPER_BIN_X86_64)
	@$(CHMOD_BIN) $(CHMOD_OPTS) $@


all_wrappers: $(BUILD_BIN_PATH)/$(WRAPPER_BIN_ARM7) \
     					$(BUILD_BIN_PATH)/$(WRAPPER_BIN_i686) \
     					$(BUILD_BIN_PATH)/$(WRAPPER_BIN_X86_64)
	
all: $(BUILD_OUT_PATH)/$(QPKG_NAME)

clean:
	@rm -rf $(BUILD_BIN_PATH)/$(DVR_BIN) \
     $(BUILD_BIN_PATH)/$(WRAPPER_BIN_ARM7) \
     $(BUILD_BIN_PATH)/$(WRAPPER_BIN_i686) \
     $(BUILD_BIN_PATH)/$(WRAPPER_BIN_X86_64) \
     $(UI_OUT_PATH) \
     $(BUILD_OUT_PATH)/$(QPKG_NAME) \
     $(BUILD_OUT_PATH)/$(QPKG_NAME).md5

.PHONY: all clean all_wrappers

.DEFAULT:
	@echo "You need to do something"