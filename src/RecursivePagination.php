<?php

namespace Xparse\RecursivePagination;


use Xparse\ElementFinder\ElementFinderInterface;
use Xparse\Parser\Parser;
use Xparse\Parser\ParserInterface;

/**
 *
 */
class RecursivePagination
{

    /**
     * @var array
     */
    protected $queue = [];

    /**
     * @var Parser
     */
    protected $parser;

    /**
     * @var array
     */
    protected $elementSelector = [];


    /**
     * @param string|array $expression
     * @throws \Exception
     */
    public function __construct(ParserInterface $parser, $expression)
    {
        $this->parser = $parser;
        $this->setExpression($expression);
    }


    /**
     * @param array|string $links
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function addToQueue($links, bool $state = false): self
    {
        if (!is_string($links) and !is_array($links)) {
            throw new \InvalidArgumentException('Links should be an array or a string');
        }
        foreach ((array)$links as $url) {
            if (!is_string($url)) {
                throw new \InvalidArgumentException('url should be a string');
            }
            $this->queue[$url] = $state;
        }

        return $this;
    }


    /**
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    public function getNextPage(): ?ElementFinderInterface
    {

        $link = array_search(false, $this->queue, true);
        if ($link === false) {
            return null;
        }
        $this->queue[$link] = true;
        $page = $this->parser->get($link);
        if ($page === null) {
            return null;
        }
        foreach ($this->elementSelector as $expression => $state) {
            $queueLinks = $page->value($expression)->all();
            $countQueueLinks = count($queueLinks);
            if ($countQueueLinks > 0) {
                $queueLinks = array_combine($queueLinks, array_fill(0, $countQueueLinks, false));
                /** @noinspection SlowArrayOperationsInLoopInspection */
                $this->queue = array_merge($queueLinks, $this->queue);
            }
        }
        return $page;
    }


    /**
     * @param string|array $expression
     * @throws \InvalidArgumentException
     */
    private function setExpression($expression): void
    {

        if (!is_string($expression) and !is_array($expression)) {
            throw new \InvalidArgumentException('Invalid expression, should be array or string');
        }

        $expression = array_filter((array)$expression);

        if (count($expression) === 0) {
            throw new \InvalidArgumentException('Expression might be not empty');
        }
        foreach ($expression as $path) {
            if (!is_string($path)) {
                throw new \InvalidArgumentException('Invalid expression, should be a string');
            }
            $this->elementSelector[$path] = true;
        }
    }

}