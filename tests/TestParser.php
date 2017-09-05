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
    protected function fileDataPath() : string {
      return __DIR__ . '/test-data/';
    }


    /**
     * @param string $url
     * @param array $options
     * @return ElementFinder
     */
    public function get(string $url, array $options = []) : ElementFinder {

      $fileData = file_get_contents($this->fileDataPath() . $url);
      $this->lastPage = new ElementFinder($fileData);

      return $this->lastPage;
    }
  }