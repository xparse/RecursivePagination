<?php

  namespace Xparse\RecursivePagination\Test;

  use Xparse\ElementFinder\ElementFinder;
  use Xparse\Parser\Parser;

  /**
   *
   * @package Xparse\RecursivePagination\Test
   */
  class TestParser extends Parser {

    /**
     * @return string
     */
    protected function fileDataPath() {
      return __DIR__ . '/test-data/';
    }


    /**
     * @param string $url
     * @return ElementFinder
     */
    public function get($url) {

      $fileData = file_get_contents($this->fileDataPath() . $url);
      $this->lastPage = new ElementFinder($fileData);
      return $this->lastPage;
    }
  }