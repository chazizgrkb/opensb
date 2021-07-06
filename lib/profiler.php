<?php

class Profiler {
	private $starttime;

	function __construct() {
		$this->starttime = microtime(true);
	}

	function __destruct() {
		$this->getStats();
	}

	function getStats() {
		global $currentUser, $frontend;

		$username = (isset($currentUser['username']) ? $currentUser['username'] : '(not logged in)');
		$language = (isset($currentUser['language']) ? $currentUser['language'] : 'en_US');
		$memoryUsage = memory_get_usage(false) / 1024;
		$renderTime = microtime(true) - $this->starttime;
		$res = getrusage();

		$debugData = sprintf(
			'[debug]: logged in as %s | user time used: %s | system time used: %s | current locale: %s | page rendered in %1.3f secs with %dKB used',
		$username, $res["ru_utime.tv_sec"], $res["ru_stime.tv_sec"], $language, $renderTime, $memoryUsage);

		if ($frontend == 'default') {
			printf(
				'<div class="offcanvas offcanvas-bottom show" data-bs-scroll="true" data-bs-backdrop="false" style="visibility:visible;height:unset;">
					<div class="offcanvas-body small">%s</div>
				</div>', $debugData);
		} else {
			printf('<center>%s</center>', $debugData);
		}
	}
}