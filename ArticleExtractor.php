<?php
/**
 * slince rss-collector
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\RssCollector;

use Readability\Readability;

/**
 * 文章内容提取器
 */
class ArticleExtractor
{

    /**
     * 从html中取出标题和文章内容
     * @param string $html
     * @param string $url
     * @return boolean|array
     */
    static function extact($html, $url = null)
    {
        $readability = static::createReadability($html, $url);
        $result = $readability->init();
        if (! $result) {
            return false;
        }
        return [
            $readability->getTitle()->textContent,
            $readability->getContent()->textContent
        ];
    }

    /**
     * 创建readability
     * @param string $html
     * @param string $url
     */
    protected static function createReadability($html, $url = null)
    {
        return new Readability($html, $url);
    }
}