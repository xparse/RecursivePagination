## Abandoned !!! 
See [xparse/ElementFinder](https://github.com/xparse/ElementFinder/)

## Recursive Pagination


Recursive Pagination allows you to parse website pages recursively. 
You need to pass link from where to start and set next page expression (xPath, css, etc). 

## Installation

You can install the package via Composer

``` bash
$ composer require xparse/recursive-pagination
```

## Basic Usage

Try to find all links to the repositories on github. Our query will be `xparse`.
With recursive pagination we can traverse all pagination links and process each resulting page to fetch repositories links.  

```php
  use Xparse\Parser\Parser;
  use Xparse\RecursivePagination\RecursivePagination;
  
  # init Parser
  $parser = new Parser();

  # set expression to pagination links
  $paginator = new RecursivePagination($parser, "//*[@class='pagination']//a/@href");
  # set initial page url
  $paginator->addToQueue('https://github.com/search?q=xparse');

  $allLinks = [];
  while (($page = $paginator->getNextPage())) {
    # set expression to fetch repository links
    $adsList = $page->value("//*[@class='repo-list-name']//a/@href")->getItems();
    # merge and remove duplicates
    $allLinks = array_values(array_unique(array_merge($allLinks, $adsList)));
  }
  print_r($allLinks);
```

## Testing

``` bash
$ vendor/bin/phpunit
```

## Credits

- [Ganchev Anatolii](https://github.com/ganchclub)
- [All Contributors](https://github.com/xparse/RecursivePagination/graphs/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
