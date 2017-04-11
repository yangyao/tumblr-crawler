<?php

namespace Yangyao\Tumblr;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\EachPromise;
use Psr\Http\Message\ResponseInterface;
use Yangyao\Tumblr\Cache\Secache;
use GuzzleHttp\Promise\FulfilledPromise;

class Crawler {

    public $client = null;
    public $blog_url = 'http://api.tumblr.com/v2/blog/';
    public $concurrency = 10;
    public $page = 10;
    public $cache = null;
    public $config = null;
    public $links = [];

    public function __construct(Config $config){
        $this->config = $config;
        $this->cache = new Secache();
        $this->client = new Client();
        $this->cache->workat($config->get('cache_path','cache'));
        $this->path = $config->get('save_path',__DIR__."/../blog/");
        $this->page = $config->get('page',10);
    }

    public function fetch(){
        $promises = function ()  {
            for ($page = 1; $page <= $this->page; $page ++) {
                $link = $this->_link($page);
                $this->links[] = $link;
                $this->cache->fetch(md5($link),$data);
                if($data !== 1) {
                    yield $this->client->requestAsync(
                        'GET',
                        $link,
                        [
                            'verify' => false,
                            'proxy' => $this->config->get('proxy'),
                            'timeout'=>$this->config->get('timeout')
                        ]
                    )->then(
                            function(ResponseInterface $response) use ($link) {
                                $this->cache->store(md5($link),1);
                                return $response;
                            }
                        );
                }else{
                    yield new FulfilledPromise(1);
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
                echo "rejected" ;
                echo "rejected reason: " . $reason ;
            },
            // if you don't wait the queue will not run that's weird
        ]))->promise()->wait();
    }

    private function _link($page){
        $link = $this->blog_url
            . $this->config->get('blog')
            . ".tumblr.com/posts/"
            . $this->config->get('type')
            . "?api_key="
            . $this->config->get('api_key')
            . "&limit=20"
            . "&offset="
            . $page * 20;
        return $link;
    }

    public function dispatcher(ResponseInterface $response){
        /** @var callable $processor */
        $processor = Factory::map($this->config->get('type'));
        return (new $processor($response))->process($this->config->get('blog'), $this->path);
    }


}
