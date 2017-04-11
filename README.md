# tumblr-crawler
yet another tumblr crawler

## how to use it ?

### download

download file [tumblr-crawler.phar](https://github.com/yangyao/tumblr-crawler/blob/master/tumblr-crawler.phar "tumblr-crawler.phar") and [config.json](https://github.com/yangyao/tumblr-crawler/blob/master/config.json "config.json")

### config

modify `config.json` file to add you tumblr api_key and other stuff

```

{
  "api_key":"put your api_key here",
  "blog":"kylesmart",
  "cache_path":"E:/www/tumblr-crawler/cache",
  "save_path":"E:/www/tumblr-crawler/blog/",
  "type":"photo",
  "timeout":"120",
  "poxy":"http://localhost:10800",
  "page":200
}

```

### run

`php tumblr-crawler.phar` 


