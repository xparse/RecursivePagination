<?php

  namespace Xparse\RecursivePagination\Test;

  use InvalidArgumentException;
  use Xparse\Parser\Parser;
  use Xparse\RecursivePagination\RecursivePagination;

  /**
   *
   * @package Xparse\RecursivePagination\Test
   */
  class RecursivePaginationTest extends \PHPUnit_Framework_TestCase {


    public function testBasicUsage() {

      $parser = new Parser();

      $githubUrl = 'https://github.com/search?q=xparse';
      $paginator = new RecursivePagination($parser, "//*[@class='pagination']//a/@href");
      $paginator->addToQueue($githubUrl);

      $countResults = $parser->get($githubUrl)->match('!found (\d+) repository results!iu')->getFirst();

      $allLinks = [];
      while (($page = $paginator->getNextPage())) {
        $adsList = $page->value("//*[@class='repo-list-name']//a/@href")->getItems();
        $allLinks = array_values(array_unique(array_merge($allLinks, $adsList)));
      }
      $this->assertEquals($countResults, count($allLinks));
    }


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
      while (($page = $paginator->getNextPage())) {
        $adsList = $page->value("//h2/a/@href")->getItems();
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
      while (($page = $paginator->getNextPage())) {
        $adsList = $page->value("//h2/a/@href")->getItems();
        $allLinks = array_values(array_unique(array_merge($allLinks, $adsList)));
      }
      $this->assertTrue(count($allLinks) == 10);
    }


    /**
     * @expectedException \InvalidArgumentException
     *
     */
    public function testInvalidExpression() {
      $parser = new TestParser();

      new RecursivePagination($parser, []);
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
      while (($page = $paginator->getNextPage())) {
        $adsList = $page->value("//h2/a/@href")->getItems();
        $allLinks = array_values(array_unique(array_merge($allLinks, $adsList)));
      }
      $this->assertTrue(count($allLinks) == 10);
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
  }
