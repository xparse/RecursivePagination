# Recursive Pagination

[![Latest Version](https://img.shields.io/github/release/xparse/recursive-pagination.svg?style=flat-square)](https://github.com/xparse/recursive-pagination/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/xparse/recursive-pagination/master.svg?style=flat-square)](https://travis-ci.org/xparse/recursive-pagination)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/xparse/recursive-pagination.svg?style=flat-square)](https://scrutinizer-ci.com/g/xparse/recursive-pagination/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/xparse/recursive-pagination.svg?style=flat-square)](https://scrutinizer-ci.com/g/xparse/recursive-pagination)
[![Total Downloads](https://img.shields.io/packagist/dt/xparse/recursive-pagination.svg?style=flat-square)](https://packagist.org/packages/xparse/recursive-pagination)

Recursive Pagination allows you to parse through website pages. You need to set xPath to pagination area and xPath to pages that have such pagination. 

## Install

Via Composer

``` bash
$ composer require xparse/recursive-pagination
```

## Usage

```php
  $grabber = new \Fiv\Parser\Grabber();
  $linksArrayPath = [
      "//a[@class='categoryitem']/@href",     // path to pages you want to scrape
      "//td[@class='pagination']//a/@href"    // path to pagination area
    ];
  
  $paginator = new RecursivePagination($grabber, $linksArrayPath);
  $paginator->addToQueue('http://www.example.com/first/page/to/parse.html');

  $allLinks = [];
    while ($page = $paginator->getNextPaginationPage()) {
    $adsList = $page->attribute("//div[@class='itemdetails']//a/@href")->getItems();
    $allLinks = array_values(array_unique(array_merge($allLinks, $adsList)));
  }
  print_r($allLinks);
```

## Testing

``` bash
$ phpunit
```

## Credits

- [Ganchev Anatoly](https://github.com/ganchclub)
- [All Contributors](https://github.com/xparse/recursive-pagination/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
