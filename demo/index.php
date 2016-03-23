<?php
include __DIR__ . '/../vendor/autoload.php';

use Slince\RssCollector\RssCollector;

//url源
$rssUrl = 'http://feed.yeeyan.org/select';
//创建采集客户端
$rssCollector = new RssCollector($rssUrl);
//添加文章处理器
$rssCollector->pushHandler(function(&$title, &$content, &$link){
    @file_put_contents(iconv('utf-8', 'gbk', __DIR__ . "/data/{$title}.txt"), $content);
});
//运行并获取执行结果
$data = $rssCollector->run()->getData();
var_dump($data);