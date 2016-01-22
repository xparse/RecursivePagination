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
      $parser = new TestParser();
      $linksArrayPath = ["//span[@class='inner']/a/@href", "//a[@class='pagenav']/@href"];

      $paginator = new RecursivePagination($parser, $linksArrayPath);
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
      $parser = new TestParser();
      $linksArrayPath = ["//span[@class='inner'][1]/a/@href", "//a[@class='pagenav']/@href"];

      $paginator = new RecursivePagination($parser, $linksArrayPath);
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
      $parser = new TestParser();

      $paginator = new RecursivePagination($parser);
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
      $parser = new TestParser();
      $linksArrayPath = ["//span[@class='inner'][1]/a/@href"];

      $paginator = new RecursivePagination($parser, $linksArrayPath);
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
      $parser = new TestParser();
      $linksArrayPath = $parser;  // passing wrong path 

      new RecursivePagination($parser, $linksArrayPath);
    }


    /**
     * @expectedException InvalidArgumentException
     */
    public function testXpathCorrectArray() {
      $parser = new TestParser();
      $linksArrayPath = ["//span[@class='inner'][1]/a/@href", "//a[@class='pagenav']/@href", $parser];  // passing wrong path

      new RecursivePagination($parser, $linksArrayPath);
    }


    /**
     * @expectedException InvalidArgumentException
     */
    public function testAddToQueueLinksArray() {
      $parser = new TestParser();
      $linksArrayPath = ["//span[@class='inner']/a/@href", "//a[@class='pagenav']/@href"];

      $paginator = new RecursivePagination($parser, $linksArrayPath);
      $paginator->addToQueue([
        'osmosis/page1.html',
        $parser, //wrong link
      ]);
    }


    /**
     * @expectedException InvalidArgumentException
     */
    public function testAddToQueueLink() {
      $parser = new TestParser();
      $linksArrayPath = ["//span[@class='inner']/a/@href", "//a[@class='pagenav']/@href"];

      $paginator = new RecursivePagination($parser, $linksArrayPath);
      $paginator->addToQueue($parser); //wrong link
    }
  }
