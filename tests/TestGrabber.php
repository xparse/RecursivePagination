<?php

  namespace Xparse\RecursivePagination\Test;

  use Fiv\Parser\Grabber;

  /**
   *
   * @package Xparse\RecursivePagination\Test
   */
  class TestGrabber extends Grabber
  {

    /**
     * @return string
     */
    protected function fileDataPath() {
      return __DIR__ . '/test-data/';
    }


    /**
     * @param string $url
     * @return \Fiv\Parser\Dom\ElementFinder
     */
    public function getHtml($url){
         
      $fileData = file_get_contents($this->fileDataPath() . $url);
      $this->lastPage = new \Fiv\Parser\Dom\ElementFinder($fileData);
      return $this->lastPage;
    }
  }