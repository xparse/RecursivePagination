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
        $adsList = $page->value('//h2/a/@href')->getItems();
        $links = array_unique(array_merge($allLinks, $adsList));
        $allLinks = array_values($links);
      }
      static::assertCount(22, $allLinks);
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
        $adsList = $page->value('//h2/a/@href')->getItems();
        $links = array_unique(array_merge($allLinks, $adsList));
        $allLinks = array_values($links);
      }
      static::assertCount(10, $allLinks);
    }


    /**
     * @expectedException \InvalidArgumentException
     *
     */
    public function testInvalidExpression() {
      $parser = new TestParser();

      new RecursivePagination($parser, ['', '']);
    }


    /**
     * Asserts that $allLinks array has 10 elements.
     *
     */
    public function testGetNextPageCustomPath() {
      $parser = new TestParser();
      $linksArrayPath = [
        "//span[@class='inner'][1]/a/@href",
        "//a[@class='pagenav']/@href",
      ];

      $paginator = new RecursivePagination($parser, $linksArrayPath);
      $paginator->addToQueue('osmosis/page1.html');

      $allLinks = [];
      while ($page = $paginator->getNextPage()) {
        $adsList = $page->value('//h2/a/@href')->getItems();
        $links = array_unique(array_merge($allLinks, $adsList));
        $allLinks = array_values($links);
      }
      static::assertCount(10, $allLinks);
    }


    /**
     * @expectedException InvalidArgumentException
     */
    public function testValidStringExpression() {
      $parser = new TestParser();
      $linksArrayPath = $parser;  // passing wrong path 

      new RecursivePagination($parser, $linksArrayPath);
    }


    /**
     * @expectedException InvalidArgumentException
     */
    public function testValidArrayExpression() {
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


    public function testGetNextPage() {

      $parser = new TestParser();
      $linksArrayPath = [
        "//span[@class='inner'][1]/a/@href",
        "//a[@class='pagenav']/@href",
      ];

      $paginator = new RecursivePagination($parser, $linksArrayPath);
      $paginator->addToQueue('osmosis/page1.html');

      $allLinks = [];
      while ($page = $paginator->getNextPage()) {
        $adsList = $page->value('//h2/a/@href')->getItems();
        $links = array_unique(array_merge($allLinks, $adsList));

        # Ensure Parser::get() method will not brake Pagination
        $heading = $parser->get('index.html')->value('//h1')->getFirst();
        static::assertEquals('Test index page', $heading);

        $allLinks = array_values($links);
      }
      static::assertCount(10, $allLinks);
    }
  }
