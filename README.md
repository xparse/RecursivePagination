# Recursive Pagination

[![Latest Version](https://img.shields.io/packagist/v/xparse/recursive-pagination.svg?style=flat-square)](https://packagist.org/packages/xparse/recursive-pagination)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/xparse/RecursivePagination/master.svg?style=flat-square)](https://travis-ci.org/xparse/RecursivePagination)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/xparse/RecursivePagination.svg?style=flat-square)](https://scrutinizer-ci.com/g/xparse/RecursivePagination/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/xparse/RecursivePagination.svg?style=flat-square)](https://scrutinizer-ci.com/g/xparse/RecursivePagination)
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
    while ($page = $paginator->getNextPage()) {
    $adsList = $page->attribute("//div[@class='itemdetails']//a/@href")->getItems();
    $allLinks = array_values(array_unique(array_merge($allLinks, $adsList)));
  }
  print_r($allLinks);
```

## Testing

``` bash
$ vendor/bin/phpunit
```

## Credits

- [Ganchev Anatoly](https://github.com/ganchclub)
- [All Contributors](https://github.com/xparse/RecursivePagination/graphs/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.