<?php

define('APP_NAME','Amar Savera');

define('APP_VERSION','1.0.0');

define('APP_ENV','production');

define('UPLOAD_PATH',
dirname(__DIR__).'/uploads/');

define('NEWS_UPLOAD_PATH',
UPLOAD_PATH.'news/');

define('ADS_UPLOAD_PATH',
UPLOAD_PATH.'advertisements/');

define('IDCARD_UPLOAD_PATH',
UPLOAD_PATH.'id-cards/');

define('AUTHORITY_UPLOAD_PATH',
UPLOAD_PATH.'authority-letters/');

define('DEFAULT_TIMEZONE',
'Asia/Kolkata');

date_default_timezone_set(
DEFAULT_TIMEZONE
);