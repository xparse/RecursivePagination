<?php

declare(strict_types=1);

namespace Xparse\RecursivePagination\Test;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Xparse\RecursivePagination\RecursivePagination;

/**
 *
 */
class RecursivePaginationTest extends TestCase
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


    /**
     * @expectedException \InvalidArgumentException
     *
     */
    public function testInvalidExpression(): void
    {
        $parser = new TestParser();

        new RecursivePagination($parser, ['', '']);
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


    /**
     * @expectedException InvalidArgumentException
     */
    public function testValidStringExpression(): void
    {
        $parser = new TestParser();
        $linksArrayPath = $parser;  // passing wrong path

        new RecursivePagination($parser, $linksArrayPath);
    }


    /**
     * @expectedException InvalidArgumentException
     */
    public function testValidArrayExpression(): void
    {
        $parser = new TestParser();
        $linksArrayPath = ["//span[@class='inner'][1]/a/@href", "//a[@class='pagenav']/@href", $parser];  // passing wrong path

        new RecursivePagination($parser, $linksArrayPath);
    }


    /**
     * @expectedException InvalidArgumentException
     */
    public function testAddToQueueLinksArray(): void
    {
        $parser = new TestParser();
        $linksArrayPath = ["//span[@class='inner']/a/@href", "//a[@class='pagenav']/@href"];

        $paginator = new RecursivePagination($parser, $linksArrayPath);
        $paginator->addToQueue([
            'osmosis/page1.html',
            $parser, //wrong link
        ]);
    }


    /**
     * @expectedException InvalidArgumentException
     */
    public function testAddToQueueLink(): void
    {
        $parser = new TestParser();
        $linksArrayPath = ["//span[@class='inner']/a/@href", "//a[@class='pagenav']/@href"];

        $paginator = new RecursivePagination($parser, $linksArrayPath);
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
