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
     * Asserts that InvalidArgumentException is thrown while passing a string to default xpath
     *
     */
    public function testXpathCorrectString() {
      $grabber = new TestGrabber();
      $linksArrayPath = $grabber;  // passing wrong path 

      $exception = null;
      try {
        new RecursivePagination($grabber, $linksArrayPath);
      } catch (InvalidArgumentException $e) {
        $this->assertTrue(true);
        return;
      }
      $this->fail('Unexpected exception type');
    }


    /**
     * Asserts that InvalidArgumentException is thrown while passing an array to default xpath
     *
     */
    public function testXpathCorrectArray() {
      $grabber = new TestGrabber();
      $linksArrayPath = ["//span[@class='inner'][1]/a/@href", "//a[@class='pagenav']/@href", $grabber];  // passing wrong path

      $exception = null;
      try {
        new RecursivePagination($grabber, $linksArrayPath);
      } catch (InvalidArgumentException $e) {
        $this->assertTrue(true);
        return;
      }
      $this->fail('Unexpected exception type');
    }


    /**
     * Asserts that InvalidArgumentException is thrown while passing array of links to queue
     *
     */
    public function testAddToQueueLinksArray() {
      $grabber = new TestGrabber();
      $linksArrayPath = ["//span[@class='inner']/a/@href", "//a[@class='pagenav']/@href"];

      $exception = null;
      try {
        $paginator = new RecursivePagination($grabber, $linksArrayPath);
        $paginator->addToQueue([
          'osmosis/page1.html',
          $grabber, //wrong link
        ]);
      } catch (InvalidArgumentException $e) {
        $this->assertTrue(true);
        return;
      }
      $this->fail('Unexpected exception type');
    }


    /**
     * Asserts that InvalidArgumentException is thrown while passing one link to queue
     *
     */
    public function testAddToQueueLink() {
      $grabber = new TestGrabber();
      $linksArrayPath = ["//span[@class='inner']/a/@href", "//a[@class='pagenav']/@href"];

      $exception = null;
      try {
        $paginator = new RecursivePagination($grabber, $linksArrayPath);
        $paginator->addToQueue($grabber); //wrong link
      } catch (InvalidArgumentException $e) {
        $this->assertTrue(true);
        return;
      }
      $this->fail('Unexpected exception type');
    }
  }
