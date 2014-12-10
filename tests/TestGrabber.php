<?php

  namespace Xparse\RecursivePagination\Test;

  class TestGrabber extends \Fiv\Parser\Grabber
  {

    protected function fileDataPath() {
      return __DIR__ . '/test-data/';
    }
    
    public function getHtml($url){
         
      $fileData = file_get_contents($this->fileDataPath() . $url);
      $this->lastPage = new \Fiv\Parser\Dom\ElementFinder($fileData);
      return $this->lastPage;
    }
  }