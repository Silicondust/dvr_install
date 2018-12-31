BUILD_ROOT=$(PWD)
APKG_NAME=HDHomeRunDVR
APKG_VER=$(shell cat $(BUILD_ROOT)/VERSION)
APKG_EXT=apk
APKG_BIN=apkg-tool.py
APKG_OPTION=create
APKG_FULLNAME=$(APKG_NAME)-$(APKG_VER).$(APKG_EXT)

# DVR Parameters - update if SiliconDust changes the values
DVR_BIN=hdhomerun_record_linux
DVR_LINK=http://download.silicondust.com/hdhomerun/hdhomerun_record_linux_beta

# Wrapper Parameters
WRAPPER_BIN_ARM7=hdhr_wrapper_arm7
WRAPPER_BIN_ARM8=hdhr_wrapper_arm8
WRAPPER_BIN_i686=hdhr_wrapper_i686
WRAPPER_BIN_X86_64=hdhr_wrapper_x86_64
WRAPPER_REPO_LINK=http://www.irish-networx.com/hdhr_wrapper

# Update this with any additional WGET parameters you need to use.. or place in local .wgetrc
WGET_BIN=wget
WGET_DVR_OPTS=-q -O $(BUILD_BIN_PATH)/$(DVR_BIN)
WGET_WRAPPER_OPTS=-q -P $(BUILD_BIN_PATH)
CHMOD_BIN=chmod
CHMOD_OPTS=u+x
BUILD_BIN_PATH=$(BUILD_ROOT)/bin
BUILD_OUT_PATH=$(BUILD_ROOT)/build


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

$(BUILD_OUT_PATH)/$(APKG_FULLNAME): $(BUILD_BIN_PATH)/$(DVR_BIN) all_wrappers
	@echo "Testing"
	touch $(BUILD_OUT_PATH)/$(APKG_FULLNAME)
	

all: $(BUILD_OUT_PATH)/$(APKG_FULLNAME)

clean:
	@rm $(BUILD_BIN_PATH)/$(DVR_BIN) \
     $(BUILD_BIN_PATH)/$(WRAPPER_BIN_ARM7) \
     $(BUILD_BIN_PATH)/$(WRAPPER_BIN_i686) \
     $(BUILD_BIN_PATH)/$(WRAPPER_BIN_X86_64)

.PHONY: all clean all_wrappers
