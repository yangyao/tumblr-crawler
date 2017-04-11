<?php
require 'vendor/autoload.php';

use Yangyao\Tumblr\Crawler;
use Yangyao\Tumblr\Cache\Secache;
$raw_configuration = @file_get_contents("config.json");
$configuration = json_decode($raw_configuration, true);
$api_key = $configuration['API_KEY'];
$cache = new Secache();
$cache->workat('cachedata');
$path = __DIR__."/blog/";
$crawler = new Crawler($cache, $path,  $api_key,'pug-of-war','photo');
$crawler->fetch();