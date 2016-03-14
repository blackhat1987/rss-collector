<?php
use Slince\Config\Config;
use Slince\RssCollector\RssCollector;
include __DIR__ . '/../vendor/autoload.php';

$config = new Config();
$config->load(__DIR__ . '/config/app.php');

foreach ($config->get('sites') as $rssUrl) {
    $rssCollector = new RssCollector($rssUrl);
    $rssCollector->pushHandler(function(&$title, &$content, &$link){
        file_put_contents(iconv('utf-8', 'gbk', __DIR__ . "/data/{$title}.txt"), $content);
    });
    $data = $rssCollector->run()->getData();
    var_dump($data);
}