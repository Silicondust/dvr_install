# *QDK_HDHR_DVR*  
SiliconDust's windows installer for the QNAP NAS requires a lot of steps that are unncessary and for some complex.
The purpose of this project is to create a simple QPKG which any QNAP owner can install via known and well practised methods.
It also provides some advantages wrt upgrading, start/stop of the service, etc.

The project here is the QDK sources to build the QPKG.
The project does not contain a copy of SiliconDust's HDHomeRun DVR Record Engine Binary. Builders must get this binary separately from the SiliconDust [forums](https://www.silicondust.com/forum/viewforum.php?f=119)

License of the QDK sources is public domain.
License of the HDHomeRun DVR Record Engine remains the property of SiliconDust and use of this QDK sources does in no way permit the user the right to redistribute the binaries without SiliconDust's permission and/or permit any breaking of SiliconDust's license restrictions on the DVR record engine.

By default the recording path is set to place all recordings in a HDHomeRunDVR folder in the Recordings Share, i.e. **/share/Recordings/HDHomeRunDVR**
You can modify this by changing 'HDHR_REC_PATH' in the **package_routines** file.
Eventually this will be modifable from the App Center

**_Prerequisites_**  
[QDK](http://forum.qnap.com/viewtopic.php?f=128&t=36132) - at time of writing was v2.2.5  

**_Current Status_**  
At this stage scripts are still in development and dependent on updates from SiliconDust.  
Feature Status
- Basic QPKG installation of the DVR Binary [DONE]
- Provide status indicator of service from App Center [DONE]
- Ability to stop/start service from App Center [DONE]

For Future Releases
- Ability to modify the Recording Path from App Center
- Ability to view logs from QTS Desktop
- Synthax highlighting of the logs

**_Tested Platforms_**  
QNAP-x51 with QTS 4.2  (linux)

**_Build Instructions_**
Once you have cloned the package to your system
..1. cd to the QDK project directory
2. Run the fetch_record_engine.sh script to get the latest DVR binary from SiliconDust
3. Build the QPKG with 'qbuild --force-config' 

This will create the QPKG in the build subfolder.
install via the QTS Desktop app center, or via the commandline on the NAS 'sh build/HDHomeRunDVR.ver.qpkg'

