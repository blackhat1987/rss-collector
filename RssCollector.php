<?php
/**
 * slince rss-collector
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\RssCollector;

class RssCollector
{
    /**
     * rss源地址
     * 
     * @var string
     */
    protected $rssUrl;
    
    /**
     * 文章处理器，采集完成之后会触发
     * 
     * @var array
     */
    protected $handlers = [];
    
    /**
     * 最终的数据结构
     * 
     * @var object
     */
    protected $dataObject;
    
    function __construct($rssUrl = null, array $handlers = [])
    {
        $this->rssUrl = $rssUrl;
        $this->pushHandlers($handlers);
        $this->dataObject = new \stdClass();
    }
    
    /**
     * 运行
     */
    function run()
    {
        $rss = \Feed::loadRss($this->rssUrl);
        $data = [
            'title' => $rss->title,
            'description' => $rss->description,
            'link' => $rss->link,
            'items' => $rss->item,
            'articles' => $this->parseItems($rss->item)
        ];
        $this->dataObject = (object)$data;
        return $this;
    }
    
    /**
     * 解析rss中的items
     * 
     * @param array|Iterator $items
     */
    protected function parseItems($items)
    {
        $articles = [];
        foreach ($items as $item) {
            if (($html = file_get_contents($item->link)) !== false) {
                $result = ArticleExtractor::extact($html, $item->link);
                if ($result !== false) {
                    list($title, $content) = $result;
                    $link = $item->link;
                    $this->triggerHander($title, $content, $link, (array)$item);
                    $articles[] = (object)[
                        'title' => $title,
                        'content' => $content,
                        'link' => $link,
                    ];
                }
            }
        }
        return $articles;
    }
    
    /**
     * 触发所有的文章处理器
     * 
     * @param string $title
     * @param string $content
     * @param string $link
     * @param array $item
     */
    protected function triggerHander(&$title, &$content, &$link, $item)
    {
        foreach ($this->handlers as $handler) {
            call_user_func($handler, $title, $content, $link, $item);
        }
    }
    
    /**
     * 获取rssurl
     */
    function getRssUrl()
    {
        return $this->rssUrl;
    }
    
    /**
     * 设置rss url
     * 
     * @param string $rssUrl
     */
    function setRssUrl($rssUrl)
    {
        $this->rssUrl = $rssUrl;
    }
    
    /**
     * 添加handler
     * 
     * @param callable $handler
     */
    function pushHandler(callable $handler)
    {
        $this->handlers[] = $handler;
    }
    
    /**
     * 批量添加handler
     * 
     * @param array $handlers
     */
    function pushHandlers(array $handlers = [])
    {
        foreach ($handlers as $handler) {
            $this->pushHandler($handler);
        }
    }

    /**
     * 获取所有的handlers
     *
     * @return array
     */
    function getHandlers()
    {
        return $this->handlers;
    }
    
    /**
     * 获取数据
     */
    function getData()
    {
        return $this->dataObject;
    }
}