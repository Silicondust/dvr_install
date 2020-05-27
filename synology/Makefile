BUILD_ROOT=$(PWD)
SPK_VER=$(shell awk -F'=' '/^version/{print $$2}' ./INFO | tr -d '"')
SPK_NAME=HDHomeRunDVR-$(SPK_VER).spk

# DVR Parameters - update if SiliconDust changes the values
DVR_BIN=hdhomerun_record_linux
DVR_LINK=http://download.silicondust.com/hdhomerun/hdhomerun_record_linux_beta

# Wrapper Parameters
WRAPPER_BIN_ARM7=hdhr_wrapper_arm7
WRAPPER_BIN_ARM64=hdhr_wrapper_arm64
WRAPPER_BIN_i686=hdhr_wrapper_i686
WRAPPER_BIN_X86_64=hdhr_wrapper_x86_64
WRAPPER_REPO_LINK=http://www.irish-networx.com/hdhr_wrapper

# Temporary Folders
TMP_PKG_PATH=$(BUILD_ROOT)/tmp_pkg
TMP_BIN_PATH=$(BUILD_ROOT)/tmp_pkg/bin
TMP_PKG_NAME=package.tgz
TMP_SPK_PATH=$(BUILD_ROOT)/tmp_spk

# MD5 Signing
MD5_BIN=md5sum
MD5_EXT=md5
MD5_NAME=$(SPK_NAME).$(MD5_EXT)

#UI Params
UI_IN_PATH=$(BUILD_ROOT)/../common/php/simpleui

# Update this with any additional WGET parameters you need to use.. or place in local .wgetrc
WGET_BIN=wget
WGET_DVR_OPTS=-q -O $(TMP_BIN_PATH)/$(DVR_BIN)
WGET_WRAPPER_OPTS=-q -P $(TMP_BIN_PATH)
CHMOD_BIN=chmod
CHMOD_OPTS=a+x
BUILD_BIN_PATH=$(BUILD_ROOT)/shared
BUILD_OUT_PATH=$(BUILD_ROOT)/build

$(BUILD_OUT_PATH)/$(MD5_NAME): $(BUILD_OUT_PATH)/$(SPK_NAME)
	@$(MD5_BIN) $(BUILD_OUT_PATH)/$(SPK_NAME) > $(BUILD_OUT_PATH)/$(MD5_NAME)


$(BUILD_OUT_PATH)/$(SPK_NAME): $(TMP_SPK_PATH)/$(TMP_PKG_NAME)
	@cp -R scripts $(TMP_SPK_PATH)
	@cp icons/* $(TMP_SPK_PATH)
	@cp -R conf $(TMP_SPK_PATH)
	@cp INFO $(TMP_SPK_PATH)
	@echo "Building $(SPK_NAME)"
	@tar cf $(BUILD_OUT_PATH)/$(SPK_NAME) -C $(TMP_SPK_PATH) INFO PACKAGE_ICON_256.PNG PACKAGE_ICON.PNG package.tgz scripts


$(TMP_SPK_PATH)/$(TMP_PKG_NAME): $(TMP_BIN_PATH)/$(DVR_BIN) all_wrappers prep_dirs
	@cp -R etc $(TMP_PKG_PATH)
	@cp -R web $(TMP_PKG_PATH)
	@cp -R $(UI_IN_PATH)/* $(TMP_PKG_PATH)/web
	@tar cfz $(TMP_SPK_PATH)/package.tgz -C $(TMP_PKG_PATH) bin etc web
	

$(TMP_BIN_PATH)/$(DVR_BIN): prep_dirs
	@echo "Fetching $(DVR_BIN)"
	@$(WGET_BIN) $(WGET_DVR_OPTS) $(DVR_LINK)
	@$(CHMOD_BIN) $(CHMOD_OPTS) $@

$(TMP_BIN_PATH)/$(WRAPPER_BIN_ARM7): prep_dirs
	@echo "Fetching $(WRAPPER_BIN_ARM7)"
	@$(WGET_BIN) $(WGET_WRAPPER_OPTS) $(WRAPPER_REPO_LINK)/$(WRAPPER_BIN_ARM7)
	@$(CHMOD_BIN) $(CHMOD_OPTS) $@
	
$(TMP_BIN_PATH)/$(WRAPPER_BIN_ARM64): prep_dirs
	@echo "Fetching $(WRAPPER_BIN_ARM64)"
	@$(WGET_BIN) $(WGET_WRAPPER_OPTS) $(WRAPPER_REPO_LINK)/$(WRAPPER_BIN_ARM64)
	@$(CHMOD_BIN) $(CHMOD_OPTS) $@

$(TMP_BIN_PATH)/$(WRAPPER_BIN_i686): prep_dirs
	@echo "Fetching $(WRAPPER_BIN_i686)"
	@$(WGET_BIN) $(WGET_WRAPPER_OPTS) $(WRAPPER_REPO_LINK)/$(WRAPPER_BIN_i686)
	@$(CHMOD_BIN) $(CHMOD_OPTS) $@

$(TMP_BIN_PATH)/$(WRAPPER_BIN_X86_64): prep_dirs
	@echo "Fetching $(WRAPPER_BIN_X86_64)"
	@$(WGET_BIN) $(WGET_WRAPPER_OPTS) $(WRAPPER_REPO_LINK)/$(WRAPPER_BIN_X86_64)
	@$(CHMOD_BIN) $(CHMOD_OPTS) $@

prep_dirs:
	@echo "Creating Temporary Folders"
	@mkdir $(TMP_PKG_PATH)
	@mkdir $(TMP_BIN_PATH)
	@mkdir $(TMP_SPK_PATH)

all_wrappers: $(TMP_BIN_PATH)/$(WRAPPER_BIN_ARM7) \
     					$(TMP_BIN_PATH)/$(WRAPPER_BIN_i686) \
     					$(TMP_BIN_PATH)/$(WRAPPER_BIN_X86_64)
	
all: $(BUILD_OUT_PATH)/$(MD5_NAME)

clean:
	@rm -rf $(TMP_PKG_PATH) \
			$(TMP_SPK_PATH) \
			$(BUILD_OUT_PATH)/$(SPK_NAME) \
			$(BUILD_OUT_PATH)/$(MD5_NAME)

.PHONY: all clean all_wrappers prep_dirs

.DEFAULT:
	@echo "You need to do something"