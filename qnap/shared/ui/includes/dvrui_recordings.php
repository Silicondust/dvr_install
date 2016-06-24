<?php
	require_once("includes/dvrui_hdhrjson.php");

class DVRUI_Recordings {
	
	
	private $recording_Category = 'Category';
	private $recording_ChannelImageURL = 'ChannelImageURL';
	private $recording_ChannelName = 'ChannelName';
	private $recording_ChannelNumber = 'ChannelNumber';
	private $recording_EpisodeNumber = 'EpisodeNumber';
	private $recording_ImageURL = 'ImageURL';
	private $recording_EpisodeTitle = 'EpisodeTitle';
	private $recording_OriginalAirDate = 'OriginalAirDate';
	private $recording_RecordStartTime = 'RecordStartTime';
	private $recording_Synopsis = 'Synopsis';
	private $recording_Title = 'Title';
	private $recording_DisplayGroupTitle = 'DisplayGroupTitle';
	private $recording_PlayURL = 'PlayURL';

	
	private $recordingCmd_delete = 'delete';
	
	private $recordings_list = array();

	
	public function DVRUI_Recordings($hdhr) {
		$this->recordingsURL = $hdhr->get_storage_url();	
	
		if (in_array('curl', get_loaded_extensions())){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $this->recordingsURL);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CURLOPT_TIMEOUT, 2);
			$recordings_json = curl_exec($ch);
			curl_close($ch);
		} else { 
			$context = stream_context_create(
				array('http' => array(
					'header'=>'Connection: close\r\n',
					'timeout' => 2.0)));
			$recordings_json = file_get_contents($this->recordingsURL,false,$context);	
		}
		$recordings_info = json_decode($recordings_json, true);
		for ($i = 0; $i < count($recordings_info); $i++) {
				try {
					$this->recordings_list[] = array($this->recording_DisplayGroupTitle => $recordings_info[$i][$this->recording_DisplayGroupTitle],
						$this->recording_Category => $recordings_info[$i][$this->recording_Category],
						$this->recording_ChannelImageURL => $recordings_info[$i][$this->recording_ChannelImageURL],
						$this->recording_ChannelName => $recordings_info[$i][$this->recording_ChannelName],
						$this->recording_ChannelNumber => $recordings_info[$i][$this->recording_ChannelNumber],
						$this->recording_EpisodeNumber => $recordings_info[$i][$this->recording_EpisodeNumber],
						$this->recording_ImageURL => $recordings_info[$i][$this->recording_ImageURL],
						$this->recording_EpisodeTitle => $recordings_info[$i][$this->recording_EpisodeTitle],
						$this->recording_OriginalAirDate => $recordings_info[$i][$this->recording_OriginalAirDate],
						$this->recording_RecordStartTime => $recordings_info[$i][$this->recording_RecordStartTime],
						$this->recording_Synopsis => $recordings_info[$i][$this->recording_Synopsis],
						$this->recording_PlayURL => $recordings_info[$i][$this->recording_PlayURL],
						$this->recording_Title => $recordings_info[$i][$this->recording_Title]);
					} catch (Exception $e){
					echo('Exception on processing a recording: ' . $e->getMessage());
				}
		}
	}

	public function getRecordingCount() {
		return count($this->recordings_list);
	}

	public function getRecordingString($pos) {
		$recording = $this->recordings_list[$pos];
		return '<tr><td>' . $recording[$this->recording_Title] .
			' </td><td><a href="' . $recording[$this->recording_PlayURL] . '">' .
			$recording[$this->recording_EpisodeNumber] . ' ' . $recording[$this->recording_EpisodeTitle] . '</A></td></tr>'; 
	}
	
}
?>
