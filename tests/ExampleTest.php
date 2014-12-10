<?php

  namespace Xparse\RecursivePagination\Test;

  class ExampleTest extends \PHPUnit_Framework_TestCase
  {

    public function testAllLinks()
    {
      $grabber = new \Xparse\RecursivePagination\Test\TestGrabber();
      $linksArrayPath = ["//span[@class='inner']/a/@href", "//a[@class='pagenav']/@href"];

      $paginator = new \Xparse\RecursivePagination\RecursivePagination($grabber, $linksArrayPath);
      $paginator->addToQueue('osmosis/page1.html');

      $allLinks = [];
      while ($page = $paginator->getNextPaginationPage()) {
        $adsList = $page->attribute("//h2/a/@href")->getItems();
        $allLinks = array_values(array_unique(array_merge($allLinks, $adsList)));
      }
      $this->assertTrue(count($allLinks) == 22);
    }

    public function testOneLink()
    {
      $grabber = new \Xparse\RecursivePagination\Test\TestGrabber();
      $linksArrayPath = ["//span[@class='inner'][1]/a/@href", "//a[@class='pagenav']/@href"];

      $paginator = new \Xparse\RecursivePagination\RecursivePagination($grabber, $linksArrayPath);
      $paginator->addToQueue('osmosis/page1.html');

      $allLinks = [];
      while ($page = $paginator->getNextPaginationPage()) {
        $adsList = $page->attribute("//h2/a/@href")->getItems();
        $allLinks = array_values(array_unique(array_merge($allLinks, $adsList)));
      }
      $this->assertTrue(count($allLinks) == 10);
    }

    /*
     * @expectedException \InvalidArgumentException
     */
    public function testXpathCorrectString()
    {
      $grabber = new \Xparse\RecursivePagination\Test\TestGrabber();
      $linksArrayPath = ["//span[@class='inner'][1]/a/@href", "//a[@class='pagenav']/@href", $grabber];  // passing wrong path

      $exception = null;
      try {
        $paginator = new \Xparse\RecursivePagination\RecursivePagination($grabber, $linksArrayPath);
      } catch (\Exception $e) {
        $exception = $e;
      }
      $this->assertInstanceOf('InvalidArgumentException', $exception);
    }

    /*
     * @expectedException \InvalidArgumentException
     */
    public function testXpathCorrectArray()
    {
      $grabber = new \Xparse\RecursivePagination\Test\TestGrabber();
      $linksArrayPath = $grabber;  // passing wrong path

      $exception = null;
      try {
        $paginator = new \Xparse\RecursivePagination\RecursivePagination($grabber, $linksArrayPath);
      } catch (\Exception $e) {
        $exception = $e;
      }
      $this->assertInstanceOf('InvalidArgumentException', $exception);
    }
  }
