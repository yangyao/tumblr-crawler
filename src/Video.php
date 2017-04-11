<?php

namespace Yangyao\Tumblr;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\EachPromise;
use Psr\Http\Message\ResponseInterface;

class Video {

    public $client = null;
    public $blog = null;
    public $uri = null;

    public function __construct(Client $client, $blog){
        $this->client = $client;
        $this->blog = $blog;
    }

    public function fetch(){
        $promises = function () use ($this) {
            foreach ($this->blog as $username) {
                yield $this->client->requestAsync('GET', $this->uri.$username,['verify' => false]);
            }
        };
        (new EachPromise($promises(), [
            'concurrency' => 4,
            'fulfilled' => function (ResponseInterface $response,$index) use ($this) {
                echo $this->blog[$index];
                echo $response->getBody();
            },
            'rejected' => function ($reason){
                echo "rejected" ;
                echo "rejected reason: " . $reason ;
            },
        ]))->promise()->wait();
    }

}