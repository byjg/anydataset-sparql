# AnyDataset-SparQL

[![Opensource ByJG](https://img.shields.io/badge/opensource-byjg-success.svg)](http://opensource.byjg.com)
[![GitHub source](https://img.shields.io/badge/Github-source-informational?logo=github)](https://github.com/byjg/anydataset-sparql/)
[![GitHub license](https://img.shields.io/github/license/byjg/anydataset-sparql.svg)](https://opensource.byjg.com/opensource/licensing.html)
[![GitHub release](https://img.shields.io/github/release/byjg/anydataset-sparql.svg)](https://github.com/byjg/anydataset-sparql/releases/)
[![Build Status](https://travis-ci.com/byjg/anydataset-sparql.svg?branch=master)](https://travis-ci.com/byjg/anydataset-sparql)


SparQL abstraction dataset. Anydataset is an agnostic data source abstraction layer in PHP. 

See more about Anydataset [here](https://opensource.byjg.com/php/anydataset).

## Examples

### Simple Manipulation

```php
<?php

$sparqlEndpoint = 'http://dbpedia.org/sparql';

$namespace = [
    'dbo' => 'http://dbpedia.org/ontology/',
    'dbp' => 'http://dbpedia.org/property/'
];

$dataset = new \ByJG\AnyDataset\Semantic\SparQLDataset($sparqlEndpoint, $namespace);
$iterator = $dataset->getIterator("select distinct ?Concept where {[] a ?Concept} LIMIT 5");

foreach ($iterator as $row) {
    echo $row->get("Concept");
    echo $row->get("Concept.type");
}
```

## Install

Just type: 

```bash
composer require "byjg/anydataset-sparql=4.0.*"
```

## Running the Unit tests

```bash
vendor/bin/phpunit
```

----
[Open source ByJG](http://opensource.byjg.com)
