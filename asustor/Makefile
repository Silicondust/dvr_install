BUILD_ROOT=$(PWD)
APKG_NAME=HDHomeRunDVR
APKG_VER=$(shell awk -F':' '/version/{print $$2}' CONTROL/config.json | tr -d '",')
APKG_EXT=apk
APKG_TRG=any
APKG_FULLNAME=$(APKG_NAME)_$(APKG_VER)_$(APKG_TRG).$(APKG_EXT)

# Temporary Folders
TMP_PKG_PATH=$(BUILD_ROOT)/$(APKG_NAME)

# UI Folders
UI_OUT_PATH=$(TMP_PKG_PATH)/www
UI_IN_PATH=$(BUILD_ROOT)/../common/php/simpleui

# APKG Tools
APKG_ZIP=APKG_Utilities_2.0.zip
APKG_URL=https://developer.asustor.com/document
APKG_TOOLS_PATH=$(BUILD_ROOT)/APKG_Utilities_2.0
APKG_BIN=apkg-tools.py
APKG_OPTION=create

# MD5 Signing
MD5_BIN=md5sum
MD5_EXT=md5
MD5_NAME=$(APKG_FULLNAME).$(MD5_EXT)

# Update this with any additional WGET parameters you need to use.. or place in local .wgetrc
WGET_BIN=wget
WGET_APKG_OPTS=-q -P $(BUILD_ROOT)

CHMOD_BIN=chmod
CHMOD_OPTS=a+x
BUILD_BIN_PATH=$(BUILD_ROOT)/bin
BUILD_OUT_PATH=$(BUILD_ROOT)

$(BUILD_OUT_PATH)/$(MD5_NAME): $(BUILD_OUT_PATH)/$(APKG_FULLNAME)
	$(MD5_BIN) $(BUILD_OUT_PATH)/$(APKG_FULLNAME) > $(BUILD_OUT_PATH)/$(MD5_NAME)

$(BUILD_OUT_PATH)/$(APKG_FULLNAME): prep_temp
	@echo "Build APKG"
	$(APKG_TOOLS_PATH)/$(APKG_BIN) $(APKG_OPTION) $(TMP_PKG_PATH)

tools:
	@echo "Getting latest APKG Tools"
	@$(WGET_BIN) $(WGET_APKG_OPTS) $(APKG_URL)/$(APKG_ZIP)
	@unzip $(BUILD_ROOT)/$(APKG_ZIP)
	@$(CHMOD_BIN) $(CHMOD_OPTS) $(APKG_TOOLS_PATH)/$(APKG_BIN)

prep_temp: tools 
	@echo "Creating Temp Folder"
	@mkdir $(TMP_PKG_PATH)
	@mkdir $(UI_OUT_PATH)
	@cp -R bin $(TMP_PKG_PATH)
	@cp -R etc $(TMP_PKG_PATH)
	@cp -R CONTROL $(TMP_PKG_PATH)
	@cp -R $(UI_IN_PATH)/* $(UI_OUT_PATH)


all: $(BUILD_OUT_PATH)/$(MD5_NAME)

clean:
	@rm -rf $(APKG_TOOLS_PATH) \
     $(BUILD_ROOT)/$(APKG_ZIP) \
     $(TMP_PKG_PATH) \
     $(BUILD_OUT_PATH)/$(MD5_NAME) \
     $(BUILD_OUT_PATH)/$(APKG_FULLNAME)

.PHONY: all clean tools prep_tmp
