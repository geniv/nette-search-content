Search content
==============

Installation
------------
```sh
$ composer require geniv/nette-search-content
```
or
```json
"geniv/nette-search-content": ">=1.0.0"
```

require:
```json
"php": ">=7.0.0",
"nette/nette": ">=2.4.0"
```

Include in application
----------------------
content of ISearchContent:
```php
getListCategory(): array;
getList(): array;
```

php usage:
```php
$searchContent = new SearchContent();
$orderControl->getListCategory();
$orderControl->getList();
```
