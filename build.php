<?php
if(class_exists('Phar')){
    $phar = new Phar('tumblr-crawler.phar',0,'tumblr-crawler.phar');
    $phar->buildFromDirectory(__DIR__);
    $phar->setStub($phar->createDefaultStub('phar.php','phar.php'));
    $phar->compressFiles(Phar::GZ);
}else{
    exit('No Phar module found !');
}