<?php

namespace Xparse\RecursivePagination\Test;

use Xparse\ElementFinder\ElementFinder;
use Xparse\Parser\Parser;

class TestParser extends Parser
{

    /**
     * @return string
     */
    private function fileDataPath(): string
    {
        return __DIR__ . '/test-data/';
    }


    final public function get(string $url, array $options = []): ElementFinder
    {
        $this->lastPage = new ElementFinder(
            file_get_contents($this->fileDataPath() . $url)
        );
        return $this->lastPage;
    }
}