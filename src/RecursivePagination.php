<?php

  namespace Xparse\RecursivePagination;

  use Doctrine\Instantiator\Exception\InvalidArgumentException;
  use Fiv\Parser\Exception;

  class RecursivePagination
  {

    protected $queue = array();

    protected $grabber = null;

    protected $defaultXpath = array();

    public function __construct(\Fiv\Parser\Grabber $grabber, $xpath = array())
    {
      $this->grabber = $grabber;
      if (!is_string($xpath) && !is_array($xpath)) {
        throw new \InvalidArgumentException('xPath should be an array or a string');
      }
      $xpath = (array)$xpath;
      foreach ($xpath as $path) {
        if (!is_string($path)) {
          throw new \InvalidArgumentException('Incorrect xPath, should be an array or a string');
        }
        $this->defaultXpath[] = $path;
      }
    }

    public function addToQueue($link, $state = false)
    {
      $this->queue[$link] = $state;
      return $this;
    }

    public function getNextPaginationPage()
    {
      $page = $this->grabber->getLastPage();
      if (!empty($page)) {
        foreach ($this->defaultXpath as $xPath) {
          $queueLinks = $page->attribute($xPath)->getItems();

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
  }