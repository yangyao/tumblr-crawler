<?php

namespace Yangyao\Tumblr;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\EachPromise;
use Psr\Http\Message\ResponseInterface;

class Photo {

    public $client = null;
    public $response = null;
    public $concurrency = 10;

    public function __construct(ResponseInterface $response){
        $this->response = $response;
        $this->client = new Client();
    }

    public function process($blog, $path){
        $links = [];
        $data = json_decode($this->response->getBody(),1);
        $posts = $data['response']['posts'];
        foreach($posts as $post){
           foreach($post['photos'] as $photo){
               $links[] = $photo['original_size']['url'];
           }
        }
        new Downloader($blog, $links, $path);
    }

}