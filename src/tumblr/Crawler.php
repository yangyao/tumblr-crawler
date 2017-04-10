<?php

namespace Yangyao\Tumblr;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\EachPromise;
use Psr\Http\Message\ResponseInterface;
use Yangyao\Tumblr\Cache\Secache;

class Crawler {

    public $client = null;
    public $blog_name = null;
    public $blog_url = 'http://api.tumblr.com/v2/blog/';
    public $api_key = 'Mb7WjvbkfI94YKEE8VkSod97c3naKdDrbirlbXelxGI9we4ufF';
    public $concurrency = 10;
    public $page = 10;
    public $type = 'video';
    public $cache = null;
    public $links = [];

    public function __construct(Secache $cache, $path,  $api_key, $blog_name, $type){
        $this->client = new Client();
        $this->type = $type;
        $this->blog_name = $blog_name;
        $this->api_key = $api_key;
        $this->cache = $cache;
        $this->path = $path;
    }

    public function fetch(){
        $promises = function ()  {
            for ($page = 1; $page <= $this->page; $page ++) {
                $link = $this->blog_url
                    . $this->blog_name
                    . ".tumblr.com/posts/"
                    . $this->type
                    . "?api_key="
                    . $this->api_key
                    . "&limit=20"
                    . "&offset="
                    . $page * 20;
                $this->links[] = $link;
                $this->cache->fetch(md5($link),$data);
                if($data !== 1) {
                    yield $this->client->requestAsync('GET',$link,['verify' => false,'proxy' => 'http://127.0.0.1:1080','timeout'=>120])
                        ->then(
                            function(ResponseInterface $response) use ($link) {
                                $this->cache->store(md5($link),1);
                                return $response;
                            }
                        );
                }else{
                    yield new \GuzzleHttp\Promise\FulfilledPromise(1);
                }
            }
        };
        (new EachPromise($promises(), [
            'concurrency' => $this->concurrency,
            'fulfilled' => function ($response)  {
                if($response !== 1 && !is_null($response)){
                    $this->dispatcher($response);
                }
            },
            'rejected' => function ($reason,$index){
                //echo "rejected" ;
                //echo "rejected reason: " . $reason ;
            },
        ]))->promise()->wait();
    }

    public function dispatcher(ResponseInterface $response){
        $processor = Factory::map($this->type);
        return (new $processor($response))->process($this->blog_name, $this->path);
    }


}