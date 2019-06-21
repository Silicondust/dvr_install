<?php
	require_once("vars.php");
	require_once("includes/dvrui_hdhrbintools.php");

class DVRUI_DVREngine {
	private $dvr_download_url = 'http://download.silicondust.com/hdhomerun';
	private $dvr_download_bin = 'hdhomerun_record_linux';
	private $dvr_wrapper_url = 'http://www.irish-networx.com/hdhr_wrapper';
	private $temp_folder = './download';
	private $real_folder = './bin';
	
	public function checkForNewEngine() {
		$hdhr = DVRUI_Vars::DVR_pkgPath . '/' . DVRUI_Vars::DVR_bin;
		$DVRBin = new DVRUI_HDHRbintools($hdhr);
		$DVRBinVersion = $DVRBin->get_DVR_version();

		$url = $this->dvr_download_url . '/' . $this->dvr_download_bin;
		if ($this->verifyBinaryExists($url)) {
			$tmpBin = $this->temp_folder . '/'. $this->dvr_download_bin;
			$this->getBinary($url, $tmpBin);
			$newVersion = $this->getBinVersion($tmpBin,true);
			$newdate = strtotime($newVersion);
			$currdate =  strtotime($DVRBinVersion);
			error_log('Checking [' . $newdate . '] to [' . $currdate . ']');
			if ($newdate > $currdate) {
				$retVal = true;
			}
			$retVal = false;
			unlink($tmpBin);
		}	
		return $retVal;
	}
	
	public function checkForNewWrappers() {
		
	}
	
	public function downloadEngine() {
		error_log('Downloading and saving new Engine');
		$url = $this->dvr_download_url . '/' . $this->dvr_download_bin;
		$tmpBin = $this->temp_folder . '/'. $this->dvr_download_bin;
		$realBin = $this->real_folder . '/'. $this->dvr_download_bin;
		if ($this->getBinary($url, $tmpBin));
		return $this->saveBinary($tmpBin, $realBin);
	}
	
	public function downloadWrappers() {
		
	}
	
	private function verifyBinaryExists($binurl) {
		error_log('Checking for : ' . $binurl);
    $ch = curl_init($binurl);    
    curl_setopt($ch, CURLOPT_NOBODY, true);
    // Silicondust does redirect to actual file.. need to follow
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION	, true);
    curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if($code == 200){
       $status = true;
    }else{
      $status = false;
    }
    curl_close($ch);
   return $status;
	}
	
	
	private function getBinary($binurl, $binloc) {
	  error_log('Download binary from : ' . $binurl . ' to: ' . $binloc);
	  if (file_put_contents($binloc,fopen($binurl, 'r')) > 0) {
		  return chmod($binloc,0700);
	  }
		return false;
	}

	private function verifyBinary($binurl, $binloc) {
	  error_log('Verify binary to : ' . $binloc);
		return true;
	}
	
	private function getBinVersion($binloc,$engine){
	  error_log('Getting Version of : ' . $binloc);
	  $version='Unknown Version';
	  if ($engine) {
			$cmd = $binloc . ' version';
			$output = shell_exec($cmd);
			if ($output != NULL) {
				$tempStrs = preg_split("/\r\n|\n|\r/",$output);
				$verStr = explode(" ",$tempStrs[0]);
				$version = $verStr[3];
			}
				  	
	  } else {
	  	
	  }
		return $version;
	}
	
	private function saveBinary($tmploc,$finalloc){
	  error_log('Saving Update of : ' . $tmploc . ' to ' . $finalloc);
	  return rename($tmploc, $finalloc);
	}
}

?>