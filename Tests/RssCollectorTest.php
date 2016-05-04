<?php
namespace Slince\RssCollector\Tests;

use Slince\RssCollector\RssCollector;

class RssCollectorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RssCollector
     */
    protected $collector;

    function setUp()
    {
        $this->collector = new RssCollector();
    }

    protected function regenerateCollector($rssUrl)
    {
        return $this->collector = new RssCollector($rssUrl);
    }

    function testSetRssUrl()
    {
        $rssUrl = 'http://feed.yeeyan.org/latest';
        $this->assertEquals('', $this->collector->getRssUrl());
        $this->collector->setRssUrl($rssUrl);
        $this->assertEquals($rssUrl, $this->collector->getRssUrl());
    }

    function testPushHandlers()
    {
        $this->assertEmpty($this->collector->getHandlers());
        $this->collector->pushHandler(function(){
            return true;
        });
        $this->assertNotEmpty($this->collector->getHandlers());
    }

    function testRun()
    {
        $rssUrl = 'http://www.codeceo.com/article/category/pick/feed';
        $collector = $this->regenerateCollector($rssUrl);
        $this->articleNumber = 0;
        $collector->pushHandler(function(){
            $this->articleNumber ++;
        });
        $data = $collector->run()->getData();
        $this->assertEquals($this->articleNumber, count($data->items));
    }
}