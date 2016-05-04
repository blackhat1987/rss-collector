<?php
namespace Slince\RssCollector\Tests;


use Slince\RssCollector\ArticleExtractor;

class ArticleExtractorTest extends \PHPUnit_Framework_TestCase
{
    function testExtract()
    {
        $link = 'http://www.codeceo.com/article/bucket-sort.html';
        $html = file_get_contents($link);
        list($title, $content) = ArticleExtractor::extact($html, $link);
        $this->assertNotEmpty($title);
        $this->assertNotEmpty($content);
    }
}