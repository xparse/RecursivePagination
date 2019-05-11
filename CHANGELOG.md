# Changelog
All Notable changes to `RecursivePagination` will be documented in this file

## 0.4.0 [2019-05-11]
- Move to php 7.1
- Make properties private in the `RecursivePagination`
- Now `RecursivePagination` accept `ParserInterface`
- Update `xparse/parser`
## 0.2.1 [2018-02-07]

### Changed
- update xparse/parser version

## 0.2.0 [2017-09-05]

### Changed
- update xparse/parser version
- strict types added

## 0.1.0-alpha.2 [2016-09-22]

### Fixed
- `RecursivePagination::getNextPage()` bug fixed: #19 issue

### Removed
- First parameter of `RecursivePagination::getNextPage()` method has been removed

## 0.1.0-alpha.1 [2016-05-27]

### Added
- Expression check added in constructor whether it's a string or an array 

### Deprecated
- Remove first parameter from `RecursivePagination::getNextPage()` method

### Fixed
- Use `ElementFinder::value()` method instead of `ElementFinder::attribute()` to get attribute 

## 0.0.5 [2016-05-25]

### Changed
- updated dependencies

## 0.0.4 [2016-05-18]

### Changed
- use `Xparse/Parser` instead of `Fiv/Parser`

### Fixed
- package name fixed

## 0.0.3 [2015-08-28]

### Added 
- code coverage increased
- tests added

## 0.0.2 [2015-08-07]

### Added
- argument added to `RecursivePagination::getNextPage()` method
- code coverage increased

### Changed 
- method `RecursivePagination::getNextPaginationPage()` renamed to `RecursivePagination::getNextPage()` 

### Deleted 
- PHP version 5.3 removed from tests

## 0.0.1 [2014-12-10]