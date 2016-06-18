<?php
	require_once("includes/dvrui_hdhrjson.php");

class DVRUI_Rules {
	
	private $recordingsURL = 'http://my.hdhomerun.com/api/recording_rules?DeviceAuth=';
	
	private $recording_RecID = 'RecordingRuleID';
	private $recording_SeriesID = 'SeriesID';
	private $recording_Title = 'Title';
	private $recording_ImageURL = 'ImageURL';
	private $recording_Priority = 'Priority';
	private $recording_StartPad = 'StartPadding';
	private $recording_EndPad = 'EndPadding';
	private $recording_Synopsis = 'Synopsis';
	private $recording_Recent = 'RecentOnly';
	private $recording_Channel = 'ChannelOnly';
	private $recording_Team = 'TeamOnly';
	private $recording_Airdate = 'AfterOriginalAirdateOnly';

	
	private $recordingCmd_delete = 'delete';
	private $recordingCmd_change = 'change';
	private $recordingCmd_add = 'add';
	
	private $execute_time = '';
	
	private $rules_list = array();
	
	public function DVRUI_Rules($hdhr) {
		//build up Auth string
		$auth='';
		
		$devices =  $hdhr->device_count();
		for ($i=0; $i < $devices; $i++) {
			$auth .= $hdhr->get_device_auth($i);
		}
		
		$context = stream_context_create(
				array('http' => array(
					'header'=>'Connection: close\r\n',
					'timeout' => 2.0)));
		$rules_json = file_get_contents($this->recordingsURL . $auth,false,$context);		

		$rules_info = json_decode($rules_json, true);
		for ($i = 0; $i < count($rules_info); $i++) {
			$this->rules[] = array($this->recording_RecID => $rules_info[$i][$this->recording_RecID],
					$this->recording_SeriesID => $rules_info[$i][$this->recording_SeriesID],
					$this->recording_Priority => $rules_info[$i][$this->recording_Priority],
					$this->recording_StartPad => $rules_info[$i][$this->recording_StartPad],
					$this->recording_EndPad => $rules_info[$i][$this->recording_EndPad],
					$this->recording_Title => $rules_info[$i][$this->recording_Title]);
		}
		
	}
	
	public function getRuleCount() {
		return count($this->rules);
	}
	
	public function getExecutionTime() {
		return $this->execute_time;
	}
	
	public function getRuleString($pos) {
		$rule = $this->rules[$pos];
		return 'Priority: ' . $rule[$this->recording_Priority]
			. ' RecID: ' . $rule[$this->recording_RecID]
			. ' SeriesID: ' . $rule[$this->recording_SeriesID]
			. ' Title: ' . $rule[$this->recording_Title]
			. ' StartPadding: ' . $rule[$this->recording_StartPad]
			. ' EndPadding: ' . $rule[$this->recording_EndPad];
	}
	
}
?>