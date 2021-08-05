BUILD_ROOT=$(PWD)
SPK_VER=$(shell awk -F'=' '/^version/{print $$2}' ./INFO | tr -d '"')
DSM6_SPK_NAME=HDHomeRunDVR-$(SPK_VER)_DSM6.spk
DSM7_SPK_NAME=HDHomeRunDVR-$(SPK_VER)_DSM7.spk
DSM6_MIN_OS="6.0-0"
DSM7_MIN_OS="7.0-40000"

# Temporary Folders
TMP_PKG6_PATH=$(BUILD_ROOT)/tmp_pkg6
TMP_PKG7_PATH=$(BUILD_ROOT)/tmp_pkg7
TMP_BIN6_PATH=$(BUILD_ROOT)/tmp_pkg6/bin
TMP_BIN7_PATH=$(BUILD_ROOT)/tmp_pkg7/bin
TMP_PKG_NAME=package.tgz
TMP_SPK6_PATH=$(BUILD_ROOT)/tmp_spk6
TMP_SPK7_PATH=$(BUILD_ROOT)/tmp_spk7

# MD5 Signing
MD5_BIN=md5sum
MD5_EXT=md5
MD5_DSM6_NAME=$(DSM6_SPK_NAME).$(MD5_EXT)
MD5_DSM7_NAME=$(DSM7_SPK_NAME).$(MD5_EXT)

#UI Params
UI_IN_PATH=$(BUILD_ROOT)/../common/php/simpleui

#
SED_BIN=sed
SED_OSMIN6_OPT='s/"OSMINVER"/$(DSM6_MIN_OS)/'
SED_OSMIN7_OPT='s/"OSMINVER"/$(DSM7_MIN_OS)/'


BUILD_BIN_PATH=$(BUILD_ROOT)/shared
BUILD_OUT_PATH=$(BUILD_ROOT)/build

all: build
	@echo "Building Synology Packages"

$(BUILD_OUT_PATH)/$(MD5_DSM6_NAME): $(BUILD_OUT_PATH)/$(DSM6_SPK_NAME)
	@$(MD5_BIN) $(BUILD_OUT_PATH)/$(DSM6_SPK_NAME) > $(BUILD_OUT_PATH)/$(MD5_DSM6_NAME)

$(BUILD_OUT_PATH)/$(MD5_DSM7_NAME): $(BUILD_OUT_PATH)/$(DSM7_SPK_NAME)
	@$(MD5_BIN) $(BUILD_OUT_PATH)/$(DSM7_SPK_NAME) > $(BUILD_OUT_PATH)/$(MD5_DSM7_NAME)

$(BUILD_OUT_PATH)/$(DSM6_SPK_NAME): prep6_info
	@cp -R scripts $(TMP_SPK6_PATH)
	@cp LICENSE $(TMP_SPK6_PATH)
	@cp icons/PACKAGE_ICON_256.PNG $(TMP_SPK6_PATH)
	@cp icons/PACKAGE_ICON_72.PNG $(TMP_SPK6_PATH)/PACKAGE_ICON.PNG
	@echo "Building $(DSM6_SPK_NAME)"
	@tar cf $(BUILD_OUT_PATH)/$(DSM6_SPK_NAME) -C $(TMP_SPK6_PATH) INFO PACKAGE_ICON_256.PNG PACKAGE_ICON.PNG package.tgz scripts LICENSE

$(BUILD_OUT_PATH)/$(DSM7_SPK_NAME): prep7_info
	@cp -R scripts $(TMP_SPK7_PATH)
	@cp -R conf $(TMP_SPK7_PATH)
	@cp LICENSE $(TMP_SPK7_PATH)
	@cp icons/PACKAGE_ICON_256.PNG $(TMP_SPK7_PATH)
	@cp icons/PACKAGE_ICON_64.PNG $(TMP_SPK7_PATH)/PACKAGE_ICON.PNG
	@echo "Building $(DSM7_SPK_NAME)"
	@tar cf $(BUILD_OUT_PATH)/$(DSM7_SPK_NAME) -C $(TMP_SPK7_PATH) INFO PACKAGE_ICON_256.PNG PACKAGE_ICON.PNG package.tgz scripts conf LICENSE

prep6_info: $(TMP_SPK6_PATH)/$(TMP_PKG_NAME)
	@echo "Fixing OS MIN Version to $(DSM6_MIN_OS)"
	@$(SED_BIN) $(SED_OSMIN6_OPT) INFO > $(TMP_SPK6_PATH)/INFO

prep7_info: $(TMP_SPK7_PATH)/$(TMP_PKG_NAME)
	@echo "Fixing OS MIN Version to $(DSM7_MIN_OS)"
	@$(SED_BIN) $(SED_OSMIN7_OPT) INFO > $(TMP_SPK7_PATH)/INFO

$(TMP_SPK6_PATH)/$(TMP_PKG_NAME): prep6_dirs
	@cp -R $(UI_IN_PATH)/* $(TMP_PKG6_PATH)/web
	@tar cfz $(TMP_SPK6_PATH)/package.tgz -C $(TMP_PKG6_PATH) web etc

$(TMP_SPK7_PATH)/$(TMP_PKG_NAME): prep7_dirs
	@cp -R $(UI_IN_PATH)/* $(TMP_PKG7_PATH)/web
	@cp -R ui $(TMP_PKG7_PATH)
	@tar cfz $(TMP_SPK7_PATH)/package.tgz -C $(TMP_PKG7_PATH) web etc ui

prep6_dirs:
	@echo "Creating DSM6 Temporary Folders"
	@mkdir $(TMP_PKG6_PATH)
	@mkdir $(TMP_PKG6_PATH)/web
	@mkdir $(TMP_PKG6_PATH)/etc
	@mkdir $(TMP_SPK6_PATH)

prep7_dirs:
	@echo "Creating DSM7 Temporary Folders"
	@mkdir $(TMP_PKG7_PATH)
	@mkdir $(TMP_PKG7_PATH)/web
	@mkdir $(TMP_PKG7_PATH)/etc
	@mkdir $(TMP_SPK7_PATH)

	
build: $(BUILD_OUT_PATH)/$(MD5_DSM6_NAME) $(BUILD_OUT_PATH)/$(MD5_DSM7_NAME)

clean:
	@rm -rf $(TMP_PKG6_PATH) \
			$(TMP_PKG7_PATH) \
			$(TMP_SPK6_PATH) \
			$(TMP_SPK7_PATH) \
			$(BUILD_OUT_PATH)/$(DSM6_SPK_NAME) \
			$(BUILD_OUT_PATH)/$(DSM7_SPK_NAME) \
			$(BUILD_OUT_PATH)/$(MD5_DSM6_NAME) \
			$(BUILD_OUT_PATH)/$(MD5_DSM7_NAME)

.PHONY: all build clean prep6_dirs prep7_dirs 

.DEFAULT:
	@echo "You need to do something"