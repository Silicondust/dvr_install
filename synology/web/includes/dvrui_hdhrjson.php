<?php
	require_once("includes/dvrui_common.php");
	
class DVRUI_HDHRjson {
	private $myhdhrurl = DVRUI_Vars::DVRUI_apiurl . 'discover';
	private $hdhrkey_devID = 'DeviceID';
	private $hdhrkey_localIP = 'LocalIP';
	private $hdhrkey_baseURL = 'BaseURL';
	private $hdhrkey_discoverURL = 'DiscoverURL';
	private $hdhrkey_lineupURL = 'LineupURL';
	private $hdhrkey_modelNum = 'ModelNumber';
	private $hdhrkey_modelName = 'FriendlyName';
	private $hdhrkey_auth = 'DeviceAuth';
	private $hdhrkey_fwVer = 'FirmwareVersion';
	private $hdhrkey_fwName = 'FirmwareName';
	private $hdhrkey_tuners = 'TunerCount';
	private $hdhrkey_legacy = 'Legacy';

	private $hdhrkey_storageID = 'StorageID';
	private $hdhrkey_storageURL = 'StorageURL';
	private $hdhrlist = array();
	private $hdhrlist_key_channelcount = 'ChannelCount';

	public function DVRUI_HDHRjson() {
		$storageURL = "??";
		$myip = getHostByName(getHostName());

		$hdhr_data = getJsonFromUrl($this->myhdhrurl);
		for ($i=0;$i<count($hdhr_data);$i++) {
			$hdhr = $hdhr_data[$i];
			$hdhr_base = $hdhr[$this->hdhrkey_baseURL];
			$hdhr_ip = $hdhr[$this->hdhrkey_localIP];
			
			if (!array_key_exists($this->hdhrkey_discoverURL,$hdhr)) {
				// Skip this HDHR - it doesn't support the newer HTTP interface
				// for DVR
				continue;
			}

			$hdhr_info = getJsonFromUrl($hdhr[$this->hdhrkey_discoverURL]);

			if (array_key_exists($this->hdhrkey_storageURL,$hdhr)) {
				// this is a record engine!

				// Need to confirm it's a valid one - After restart of
				// engine it updates api.hdhomerun.com but sometimes the
				// old engine config is left behind.
				$rEngine = getJsonFromUrl($hdhr[$this->hdhrkey_discoverURL]);
				if (strcmp($rEngine[$this->hdhrkey_storageID],$hdhr[$this->hdhrkey_storageID]) != 0) {
					//skip, this is not a valid engine
					continue;
				}

				//get the IP address of record engine.
				$hdhr_ip = $hdhr[$this->hdhrkey_localIP];
				// Split IP and port
				if (preg_match('/^(\d[\d.]+):(\d+)\b/', $hdhr_ip, $matches)) {
					$ip = $matches[1];
					$port = $matches[2];
					// if IP of record engine matches the IP of this server
					// return storageURL
					if($ip == $myip){	
						$this->storageURL = $hdhr[$this->hdhrkey_storageURL];
						continue;
					}
				}

				
			}
			// ELSE we have a tuner

			$tuners='unknown';
			if (array_key_exists($this->hdhrkey_tuners,$hdhr_info)) {
				$tuners = $hdhr_info[$this->hdhrkey_tuners];
			}

			$legacy='No';
			if (array_key_exists($this->hdhrkey_legacy,$hdhr_info)) {
				$legacy = $hdhr_info[$this->hdhrkey_legacy];
			}

			$hdhr_lineup = getJsonFromUrl($hdhr_info[$this->hdhrkey_lineupURL]);	

			$this->hdhrlist[] = array( $this->hdhrkey_devID => $hdhr[$this->hdhrkey_devID],
										$this->hdhrkey_modelNum => $hdhr_info[$this->hdhrkey_modelNum],
										$this->hdhrlist_key_channelcount => count($hdhr_lineup),
										$this->hdhrkey_baseURL => $hdhr_base,
										$this->hdhrkey_lineupURL => $hdhr_info[$this->hdhrkey_lineupURL],
										$this->hdhrkey_modelName => $hdhr_info[$this->hdhrkey_modelName],
										$this->hdhrkey_auth =>$hdhr_info[$this->hdhrkey_auth],
										$this->hdhrkey_fwVer => $hdhr_info[$this->hdhrkey_fwVer],
										$this->hdhrkey_tuners => $tuners,
										$this->hdhrkey_legacy => $legacy,
										$this->hdhrkey_fwName => $hdhr_info[$this->hdhrkey_fwName]);
		}		
	}
	
	public function device_count() {
		return count($this->hdhrlist);
	}

	public function get_device_info($pos) {
		$device = $this->hdhrlist[$pos];
		return ' DeviceID: ' . $device[$this->hdhrkey_devID] 
		          . ' Model Number: ' . $device[$this->hdhrkey_modelNum] 
		          . ' Channels: ' . $device[$this->hdhrlist_key_channelcount] . ' ';
	}
	public function get_storage_url(){
		return $this->storageURL;
	}	
	public function get_device_id($pos) {
		$device = $this->hdhrlist[$pos];
		return $device[$this->hdhrkey_devID];
	}

	public function get_device_model($pos) {
		$device = $this->hdhrlist[$pos];
		return $device[$this->hdhrkey_modelNum];
	}

	public function get_device_channels($pos) {
		$device = $this->hdhrlist[$pos];
		return $device[$this->hdhrlist_key_channelcount];
	}

	public function get_device_lineup($pos) {
		$device = $this->hdhrlist[$pos];
		return $device[$this->hdhrkey_lineupURL];
	}

	public function get_device_baseurl($pos) {
		$device = $this->hdhrlist[$pos];
		return $device[$this->hdhrkey_baseURL];
	}

	public function get_device_firmware($pos) {
		$device = $this->hdhrlist[$pos];
		return $device[$this->hdhrkey_fwVer];
	}

	public function get_device_tuners($pos) {
		$device = $this->hdhrlist[$pos];
		return $device[$this->hdhrkey_tuners];
	}


	public function get_device_legacy($pos) {
		$device = $this->hdhrlist[$pos];
		if( $device[$this->hdhrkey_legacy] == 1) {
			return 'Legacy';
		}
		else {
			return 'HTTP';
		}
	}

	public function get_device_auth($pos) {
		$device = $this->hdhrlist[$pos];
		if (array_key_exists($this->hdhrkey_auth,$device)) {
			return $device[$this->hdhrkey_auth];
		} else {
			return '??';
		}
	}
	
	public function get_device_image($pos) {
		$device = $this->hdhrlist[$pos];
		switch ($device[$this->hdhrkey_modelNum]) {
			case 'HDTC-2US':
				return 'https://www.silicondust.com/wordpress/wp-content/uploads/2016/04/extend-logo-2.png';
			case 'HDHR3-CC':
				return 'https://www.silicondust.com/wordpress/wp-content/uploads/2016/04/prime-logo-2.png';
			case 'HDHR3-EU':
			case 'HDHR3-4DC':
				return 'https://www.silicondust.com/wordpress/wp-content/uploads/2016/04/expand-logo-2.png';
			case 'HDHR3-US':
			case 'HDHR3-DT':
				return './images/HDHOMERUN_LEGACY.png';
			case 'HDHR4-2US':
			case 'HDHR4-2DT':
				return 'https://www.silicondust.com/wordpress/wp-content/uploads/2016/04/connect-logo.png';
			default:
				return './images/HDHOMERUN_LEGACY.png';
		}
	}
}
?>
