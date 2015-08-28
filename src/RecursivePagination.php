<?php

  namespace Xparse\RecursivePagination;


  use Fiv\Parser\Grabber;

  /**
   *
   * @package Xparse\RecursivePagination
   */
  class RecursivePagination {

    /**
     * @var array
     */
    protected $queue = [];

    /**
     * @var Grabber|null
     */
    protected $grabber = null;

    /**
     * @var array
     */
    protected $defaultXpath = [];


    /**
     * @param Grabber $grabber
     * @param null $xpath
     */
    public function __construct(Grabber $grabber, $xpath = null) {
      $this->grabber = $grabber;
      if (isset($xpath)) {
        $this->addXpath($xpath);
      }
    }


    /**
     * @param array|string $links
     * @param bool $state
     * @throws \InvalidArgumentException
     * @return $this
     */
    public function addToQueue($links, $state = false) {
      if (!is_string($links) and !is_array($links)) {
        throw new \InvalidArgumentException('Links should be an array or a string');
      }
      $links = (array) $links;
      foreach ($links as $url) {
        if (!is_string($url)) {
          throw new \InvalidArgumentException('url should be a string');
        }
        $this->queue[$url] = $state;
      }

      return $this;
    }


    /**
     * @param null $customXpath
     * @return \Fiv\Parser\Dom\ElementFinder|null
     */
    public function getNextPage($customXpath = null) {
      if (isset($customXpath)) {
        $this->addXpath($customXpath);
      }
      $page = $this->grabber->getLastPage();
      if (!empty($page)) {
        foreach ($this->defaultXpath as $xpath) {
          $queueLinks = $page->attribute($xpath)->getItems();
          if (!empty($queueLinks)) {
            $queueLinks = array_combine($queueLinks, array_fill(0, count($queueLinks), false));
            $this->queue = array_merge($queueLinks, $this->queue);
          }
        }
      }
      $link = array_search(false, $this->queue, true);

      if (empty($link)) {
        return null;
      }

      $this->queue[$link] = true;
      return $this->grabber->getHtml($link);
    }


    /**
     * @param string|array $xpath
     * @throws \InvalidArgumentException
     */
    private function addXpath($xpath) {
      if (!is_string($xpath) and !is_array($xpath)) {
        throw new \InvalidArgumentException('Xpath should be an array or a string');
      }
      $xpath = (array) $xpath;
      foreach ($xpath as $path) {
        if (!is_string($path)) {
          throw new \InvalidArgumentException('Incorrect xpath, should be an array or a string');
        }
        $this->defaultXpath[] = $path;
      }
    }

  }