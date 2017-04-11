<?php
require 'vendor/autoload.php';

use Yangyao\Tumblr\Crawler;
use Yangyao\Tumblr\Config;
$config_file = dirname(Phar::running(false)) . '/config.json';
if(!file_exists($config_file)){
    $config_file = __DIR__.'/config.json';
}
$raw_configuration = @file_get_contents($config_file);
$configuration = json_decode($raw_configuration, true);
$config = new Config($configuration);
$crawler = new Crawler($config);
$crawler->fetch();