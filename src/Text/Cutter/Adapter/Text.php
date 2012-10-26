<?php

class Text_Cutter_Adapter_Text {

	const START_CONNECT_CHARS = ' .,:;!?-&(<[_-+\'';
	const END_CONNECT_CHARS   = ' .,:;!?-&)>]_-+\'';

	public function limit($string, $length = 50, $end = '…') {
		if (mb_strlen($string) > $length) {
			$string = mb_substr($string, 0, $length);
			$string = $this->_connect($string, null, $end);
		}
		return $string;
	}

	public function lines($string, $lines = 15, $end = '…') {
		$parts = explode("\n", $string);

		$parts = array_slice($parts, 0, $lines);

		$string = implode("\n", $parts);
		return $this->_connect($string, null, $end);
	}

	public function excerpt($string, $length = 400, $minLineLength = 100, $start = '…', $end = '…') {
		$parts = explode("\n", $string);

		if (!$string || !$parts) {
			return $string;
		}

		$results = array();
		$resultsLength = 0;

		foreach ($parts as $key => $part) {
			$resultLength = mb_strlen($part);

			if (!$results && $resultLength < $minLineLength) {
				continue;
			}
			$results[$key] = $part;
			$resultsLength += $resultLength;

			if ($resultsLength > $length) {
				break;
			}
		}
		if (!$results && $minLineLength) {
			return $this->excerpt($string, $length, $minLineLength / 2, $start, $end);
		}

		$string = implode("\n", $results);
		$string = trim($string);

		reset($results);
		reset($parts);
		$start = key($results) === key($parts) ? null : $start;

		end($results);
		end($parts);
		$end = key($results) === key($parts) ? null : $end;

		return $this->_connect($string, $start, $end);
	}

	protected function _connect($string, $start = null, $end = null) {
		if ($start) {
			$string = $start . ltrim($string, static::START_CONNECT_CHARS);
		}
		if ($end) {
			$string = rtrim($string, static::END_CONNECT_CHARS) . $end;
		}
		return $string;
	}
}

?>