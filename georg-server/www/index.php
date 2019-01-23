<?php

@chdir($_SERVER['GEORG_BASE_DIR']);
require 'vendor/autoload.php';
$app = require 'app.php';
$app->dispatch($_SERVER['REDIRECT_URI'], $_SERVER);
