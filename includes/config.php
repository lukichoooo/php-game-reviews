<?php
session_start();

define('DB_PATH', __DIR__ . '/../gamereviews.db');
define('UPLOAD_DIR', __DIR__ . '/../uploads/');

require_once __DIR__ . '/dtos/ResponseDto.class.php';
require_once __DIR__ . '/Database.class.php';
require_once __DIR__ . '/User.class.php';
require_once __DIR__ . '/Review.class.php';
