### 基于Rss的文章采集器
采集器集成了文章正文抽取算法，会自动将文章页面其它页面元素排除；保证文章内容的干净。

## 安装
 * 基于composer安装
  `composer require slince/rss-collector *@dev `
 * 要求
    - php 5.4

### 使用
```
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
```
最终数据结构请自行试验