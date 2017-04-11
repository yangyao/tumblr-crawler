<?php
require 'vendor/autoload.php';

use Yangyao\Tumblr\Crawler;
use Yangyao\Tumblr\Config;


$raw_configuration = @file_get_contents("config.json");
$configuration = json_decode($raw_configuration, true);

$config = new Config($configuration);
$crawler = new Crawler($config);
$crawler->fetch();