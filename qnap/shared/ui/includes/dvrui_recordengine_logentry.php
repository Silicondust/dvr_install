<?php

class DVRUI_Engine_LogEntry {
	private $timestamp = '';
	private $type = '';
	private $subtype = '';
	private $filename = '';
	private $info = '';
	
	private $timestamp_pattern = '/\d{8}-\d{2}:\d{2}:\d{2} /';
	private $type_pattern = '/ [a-zA-Z]*: /';
	private $subtype_pattern = '/: [a-zA-Z]*: /';
	private $info_pattern = '/: [a-zA-Z]*$/';
	
	private function extractTimeStamp($entry) {
		$strings = preg_split($timestamp_pattern, $entry, 1);
		$timestamp = $strings[0];
		return $strings[1];
	}

	private function extractType($entry) {
		$strings = preg_split($type_pattern, $entry, 1);
		$type = $strings[0];
		return $strings[1];
	}

	private function extractSubType($entry) {
		$strings = preg_split($subtype_pattern, $entry, 1);
		$subtype = $strings[0];
		return $strings[1];
	}

	private function extractFilename($entry) {
	}

	private function extractInfo($entry) {
		$strings = preg_split($info_pattern, $entry, 1);
		$info = $strings[0];
		return $strings[1];
	}

	public function DVRUI_Engine_LogList($entry) {
		$remainder = extractTimeStamp($entry);
		$remainder = extractType($remainder);
		if !(strcmp($remainder,'Recorded') 
		   or strcmp($remainder,'Recording')
		   or strcmp($remainder,'System')){
			remainder = extractSubType($remainder);
		}
		extractInfo($remainder);
	}

	public function getLogTimestamp() {
		return $timestamp;
	}
	public function getLogType() {
		return $type;
	}
	public function getLogSubType() {
		return $subtype;
	}
	public function getFilename() {
		return $filename;
	}
	public function getLogInfo() {
		return $info;
	}
}

?>