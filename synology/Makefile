BUILD_ROOT=$(PWD)
SPK_VER=$(shell awk -F'=' '/^version/{print $$2}' ./INFO | tr -d '"')
SPK_NAME=HDHomeRunDVR-$(SPK_VER).spk

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


$(TMP_SPK_PATH)/$(TMP_PKG_NAME): prep_dirs
	@cp -R etc $(TMP_PKG_PATH)
	@cp -R $(UI_IN_PATH)/* $(TMP_PKG_PATH)/web
	@tar cfz $(TMP_SPK_PATH)/package.tgz -C $(TMP_PKG_PATH) bin etc web
	
prep_dirs:
	@echo "Creating Temporary Folders"
	@mkdir $(TMP_PKG_PATH)
	@mkdir $(TMP_PKG_PATH)/web
	@mkdir $(TMP_BIN_PATH)
	@mkdir $(TMP_SPK_PATH)

	
all: $(BUILD_OUT_PATH)/$(MD5_NAME)

clean:
	@rm -rf $(TMP_PKG_PATH) \
			$(TMP_SPK_PATH) \
			$(BUILD_OUT_PATH)/$(SPK_NAME) \
			$(BUILD_OUT_PATH)/$(MD5_NAME)

.PHONY: all clean prep_dirs

.DEFAULT:
	@echo "You need to do something"