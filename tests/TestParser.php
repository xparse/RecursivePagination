<?php

namespace Xparse\RecursivePagination\Test;

use Xparse\ElementFinder\ElementFinder;
use Xparse\ElementFinder\ElementFinderInterface;
use Xparse\Parser\ParserInterface;

class TestParser implements ParserInterface
{
    /**
     * @var ElementFinderInterface|null
     */
    private $lastPage;

    /**
     * @return string
     */
    private function fileDataPath(): string
    {
        return __DIR__ . '/test-data/';
    }


    final public function get(string $url, array $options = []): ElementFinderInterface
    {
        $this->lastPage = new ElementFinder(
            file_get_contents($this->fileDataPath() . $url)
        );
        return $this->lastPage;
    }

    final public function post(string $url, array $options): ElementFinderInterface
    {
        return $this->get($url, $options);
    }

    final public function getLastPage(): ?ElementFinderInterface
    {
        return $this->lastPage;
    }
}