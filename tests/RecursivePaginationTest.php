<?php

declare(strict_types=1);

namespace Xparse\RecursivePagination\Test;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Xparse\RecursivePagination\RecursivePagination;

/**
 *
 */
final class RecursivePaginationTest extends TestCase
{

    public function testAllLinks(): void
    {
        $parser = new TestParser();
        $linksArrayPath = ["//span[@class='inner']/a/@href", "//a[@class='pagenav']/@href"];

        $paginator = new RecursivePagination($parser, $linksArrayPath);
        $paginator->addToQueue('osmosis/page1.html');

        $allLinks = [];
        while ($page = $paginator->getNextPage()) {
            $adsList = $page->value('//h2/a/@href')->all();
            $links = array_unique(array_merge($allLinks, $adsList));
            $allLinks = array_values($links);
        }
        static::assertCount(22, $allLinks);
    }


    /**
     * Asserts that $allLinks array has 10 elements.
     *
     */
    public function testOneLink(): void
    {
        $parser = new TestParser();
        $linksArrayPath = ["//span[@class='inner'][1]/a/@href", "//a[@class='pagenav']/@href"];

        $paginator = new RecursivePagination($parser, $linksArrayPath);
        $paginator->addToQueue('osmosis/page1.html');

        $allLinks = [];
        while ($page = $paginator->getNextPage()) {
            $adsList = $page->value('//h2/a/@href')->all();
            $links = array_unique(array_merge($allLinks, $adsList));
            $allLinks = array_values($links);
        }
        static::assertCount(10, $allLinks);
    }


    public function testInvalidExpression(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new RecursivePagination(new TestParser(), ['', '']);
    }


    /**
     * Asserts that $allLinks array has 10 elements.
     *
     */
    public function testGetNextPageCustomPath(): void
    {
        $parser = new TestParser();
        $linksArrayPath = [
            "//span[@class='inner'][1]/a/@href",
            "//a[@class='pagenav']/@href",
        ];

        $paginator = new RecursivePagination($parser, $linksArrayPath);
        $paginator->addToQueue('osmosis/page1.html');

        $allLinks = [];
        while ($page = $paginator->getNextPage()) {
            $adsList = $page->value('//h2/a/@href')->all();
            $links = array_unique(array_merge($allLinks, $adsList));
            $allLinks = array_values($links);
        }
        static::assertCount(10, $allLinks);
    }


    public function testValidStringExpression(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $parser = new TestParser();
        $linksArrayPath = $parser;  // passing wrong path
        new RecursivePagination($parser, $linksArrayPath);
    }


    public function testValidArrayExpression(): void
    {
        $parser = new TestParser();
        $linksArrayPath = ["//span[@class='inner'][1]/a/@href", "//a[@class='pagenav']/@href", $parser];  // passing wrong path
        $this->expectException(InvalidArgumentException::class);
        new RecursivePagination($parser, $linksArrayPath);
    }


    public function testAddToQueueLinksArray(): void
    {
        $parser = new TestParser();
        $linksArrayPath = ["//span[@class='inner']/a/@href", "//a[@class='pagenav']/@href"];

        $paginator = new RecursivePagination($parser, $linksArrayPath);
        $this->expectException(InvalidArgumentException::class);
        $paginator->addToQueue([
            'osmosis/page1.html',
            $parser, //wrong link
        ]);
    }


    public function testAddToQueueLink(): void
    {
        $parser = new TestParser();
        $linksArrayPath = ["//span[@class='inner']/a/@href", "//a[@class='pagenav']/@href"];
        $paginator = new RecursivePagination($parser, $linksArrayPath);
        $this->expectException(InvalidArgumentException::class);
        $paginator->addToQueue($parser); //wrong link
    }


    public function testGetNextPage(): void
    {
        $parser = new TestParser();
        $linksArrayPath = [
            "//span[@class='inner'][1]/a/@href",
            "//a[@class='pagenav']/@href",
        ];
        $paginator = new RecursivePagination($parser, $linksArrayPath);
        $paginator->addToQueue('osmosis/page1.html');
        $allLinks = [];
        while ($page = $paginator->getNextPage()) {
            $adsList = $page->value('//h2/a/@href')->all();
            $links = array_unique(array_merge($allLinks, $adsList));
            # Ensure Parser::get() method will not brake Pagination
            $heading = $parser->get('index.html')->value('//h1')->first();
            static::assertEquals('Test index page', $heading);
            $allLinks = array_values($links);
        }
        static::assertCount(10, $allLinks);
    }
}
