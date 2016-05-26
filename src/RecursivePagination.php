<?php

  namespace Xparse\RecursivePagination;


  use Xparse\ElementFinder\ElementFinder;
  use Xparse\Parser\Parser;

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
     * @var Parser|null
     */
    protected $parser = null;

    /**
     * @var array
     */
    protected $elementSelector = [];


    /**
     * @param Parser $parser
     * @param null $xpath
     */
    public function __construct(Parser $parser, $xpath = null) {
      $this->parser = $parser;

      if (!is_string($xpath) and !is_array($xpath)) {
        throw new \InvalidArgumentException('Xpath should be an array or a string');
      }

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
     * @return ElementFinder|null
     */
    public function getNextPage() {

      if (func_num_args() > 0) {
        trigger_error('This method doesn\'t have arguments', E_USER_DEPRECATED);
      }

      $page = $this->parser->getLastPage();
      if (!empty($page)) {
        foreach ($this->elementSelector as $xpath => $state) {
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
      return $this->parser->get($link);
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
        $this->elementSelector[$path] = true;
      }
    }

  }