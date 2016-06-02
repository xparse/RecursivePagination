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
     * @param string|array $expression
     */
    public function __construct(Parser $parser, $expression) {
      $this->parser = $parser;

      $this->setExpression($expression);
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

      $page = $this->parser->getLastPage();
      if (!empty($page)) {
        foreach ($this->elementSelector as $expression => $state) {
          $queueLinks = $page->value($expression)->getItems();
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
     * @param string|array $expression
     * @throws \InvalidArgumentException
     */
    private function setExpression($expression) {

      if (!is_string($expression) and !is_array($expression) or empty($expression)) {
        throw new \InvalidArgumentException('Invalid expression, should be not empty array or string');
      }

      $expression = (array) $expression;
      foreach ($expression as $path) {
        if (!is_string($path) or empty($path)) {
          throw new \InvalidArgumentException('Invalid expression, should be not empty string');
        }
        $this->elementSelector[$path] = true;
      }
    }

  }