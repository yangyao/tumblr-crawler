<?php

namespace Yangyao\Tumblr;

class Factory {

    public static function map($type){
        $className = ucfirst($type);
        return 'Yangyao\\Tumblr\\'.$className;
    }

}