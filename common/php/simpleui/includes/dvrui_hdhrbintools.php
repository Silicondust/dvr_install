<?php
require_once ("vars.php");
require_once ("includes/dvrui_recordengine_config.php");

class DVRUI_HDHRbintools {
	private $hdhr_bin = '';
	private $dvr_version = 'Unable to discover DVR version';
	private $dvr_status = 'Unable to discover DVR status';
	
	public function __construct() {
                $dvrconf = new DVRUI_Engine_Config();
                $rpath = $dvrconf->getRecordPath();
                $hdhr = $rpath . "/" . DVRUI_Vars::DVR_bin;
                error_log("Setting hdhr to [".$hdhr."]");
		if (file_exists($hdhr)) {
			$this->hdhr_bin = $hdhr;
		}
	}

	private function exec_bin_version() {
		if ($this->hdhr_bin != '') {
			$cmd = $this->hdhr_bin . ' version';
			$output = shell_exec($cmd);
			return $output;
		}
		return NULL;
	}	

	private function exec_bin_status() {
		if ($this->hdhr_bin != '') {
			$cmd = $this->hdhr_bin . ' status';
			$output = shell_exec($cmd);
			return $output;
		}
	}	
	
	public function get_DVR_version() {
		$output = $this->exec_bin_version();
		if ($output != NULL) {
			$tempStrs = preg_split("/\r\n|\n|\r/",$output);
			$verStr = explode(" ",$tempStrs[0]);
			$this->dvr_version = $verStr[3];
		}
		return $this->dvr_version;
	}

	public function get_DVR_status() {
		$output = $this->exec_bin_status();
		if ($output != NULL) {
			$this->dvr_status = $output;
		}
		return $this->dvr_status;
	}

}

?>
