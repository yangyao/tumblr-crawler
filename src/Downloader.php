<?php

namespace Yangyao\Tumblr;

class Downloader {

    public function __construct($blog, $links, $path){
        $data = implode("\r\n",$links);
        echo $data;
        $file = $path.$blog.".txt";
        if(!is_dir($path)){@mkdir($path);}
        if(!file_exists($file)){@touch($file);}
        file_put_contents($file,$data,FILE_APPEND);
    }


}