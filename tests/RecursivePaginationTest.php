<?php

  namespace Xparse\RecursivePagination\Test;

  use InvalidArgumentException;
  use Xparse\RecursivePagination\RecursivePagination;

  /**
   *
   * @package Xparse\RecursivePagination\Test
   */
  class RecursivePaginationTest extends \PHPUnit_Framework_TestCase {


    /**
     * Asserts that $allLinks array has 22 elements.
     *
     */
    public function testAllLinks() {
      $grabber = new TestGrabber();
      $linksArrayPath = ["//span[@class='inner']/a/@href", "//a[@class='pagenav']/@href"];

      $paginator = new RecursivePagination($grabber, $linksArrayPath);
      $paginator->addToQueue('osmosis/page1.html');

      $allLinks = [];
      while ($page = $paginator->getNextPage()) {
        $adsList = $page->attribute("//h2/a/@href")->getItems();
        $allLinks = array_values(array_unique(array_merge($allLinks, $adsList)));
      }
      $this->assertTrue(count($allLinks) == 22);
    }


    /**
     * Asserts that $allLinks array has 10 elements.
     *
     */
    public function testOneLink() {
      $grabber = new TestGrabber();
      $linksArrayPath = ["//span[@class='inner'][1]/a/@href", "//a[@class='pagenav']/@href"];

      $paginator = new RecursivePagination($grabber, $linksArrayPath);
      $paginator->addToQueue('osmosis/page1.html');

      $allLinks = [];
      while ($page = $paginator->getNextPage()) {
        $adsList = $page->attribute("//h2/a/@href")->getItems();
        $allLinks = array_values(array_unique(array_merge($allLinks, $adsList)));
      }
      $this->assertTrue(count($allLinks) == 10);
    }


    /**
     * Asserts that $allLinks array has 2 elements. Checking only first page, without pagination Xpath
     *
     */
    public function testOneLinkWithEmptyPaginationXpath() {
      $grabber = new TestGrabber();

      $paginator = new RecursivePagination($grabber);
      $paginator->addToQueue('osmosis/page1.html');

      $allLinks = [];
      while ($page = $paginator->getNextPage()) {
        $adsList = $page->attribute("//h2/a/@href")->getItems();
        $allLinks = array_values(array_unique(array_merge($allLinks, $adsList)));
      }
      $this->assertTrue(count($allLinks) == 2);
    }


    /**
     * Asserts that $allLinks array has 10 elements.
     *
     */
    public function testGetNextPageCustomPath() {
      $grabber = new TestGrabber();
      $linksArrayPath = ["//span[@class='inner'][1]/a/@href"];

      $paginator = new RecursivePagination($grabber, $linksArrayPath);
      $paginator->addToQueue('osmosis/page1.html');

      $allLinks = [];
      while ($page = $paginator->getNextPage("//a[@class='pagenav']/@href")) {
        $adsList = $page->attribute("//h2/a/@href")->getItems();
        $allLinks = array_values(array_unique(array_merge($allLinks, $adsList)));
      }
      $this->assertTrue(count($allLinks) == 10);
    }


    /**
     * @expectedException InvalidArgumentException
     */
    public function testXpathCorrectString() {
      $grabber = new TestGrabber();
      $linksArrayPath = $grabber;  // passing wrong path 

      new RecursivePagination($grabber, $linksArrayPath);
    }


    /**
     * @expectedException InvalidArgumentException
     */
    public function testXpathCorrectArray() {
      $grabber = new TestGrabber();
      $linksArrayPath = ["//span[@class='inner'][1]/a/@href", "//a[@class='pagenav']/@href", $grabber];  // passing wrong path

      new RecursivePagination($grabber, $linksArrayPath);
    }


    /**
     * @expectedException InvalidArgumentException
     */
    public function testAddToQueueLinksArray() {
      $grabber = new TestGrabber();
      $linksArrayPath = ["//span[@class='inner']/a/@href", "//a[@class='pagenav']/@href"];

      $paginator = new RecursivePagination($grabber, $linksArrayPath);
      $paginator->addToQueue([
        'osmosis/page1.html',
        $grabber, //wrong link
      ]);
    }


    /**
     * @expectedException InvalidArgumentException
     */
    public function testAddToQueueLink() {
      $grabber = new TestGrabber();
      $linksArrayPath = ["//span[@class='inner']/a/@href", "//a[@class='pagenav']/@href"];

      $paginator = new RecursivePagination($grabber, $linksArrayPath);
      $paginator->addToQueue($grabber); //wrong link
    }
  }
