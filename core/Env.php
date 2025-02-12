<?php

function loadEnv() {
	if (!file_exists(__DIR__ . '/../.env')) {
		throw new Exception(".env file not found");
	}

	$lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	
	foreach ($lines as $line) {
		if (strpos(trim($line), '#') === 0) continue;
		list($key, $value) = explode('=', $line, 2);
		$_ENV[trim($key)] = trim($value);
	}
}