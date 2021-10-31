<?php

namespace Tests\AnyDataset\Dataset;

use ByJG\AnyDataset\Core\IteratorInterface;
use ByJG\AnyDataset\Semantic\SparQLDataset;
use PHPUnit\Framework\TestCase;

class SparQLDatasetTest extends TestCase
{

    const SPARQL_URL = 'https://dbpedia.org/sparql';

    protected static $SPARQL_NS = [
        'dbo' => 'http://dbpedia.org/ontology/',
        'dbp' => 'http://dbpedia.org/property/'
    ];

    // Run before each test case
    public function setUp()
    {
        
    }

    // Run end each test case
    public function teardown()
    {
        
    }

    public function test_connectSparQLDataset()
    {
        $dataset = new SparQLDataset(SparQLDatasetTest::SPARQL_URL, SparQLDatasetTest::$SPARQL_NS);
        $iterator = $dataset->getIterator("select distinct ?Concept where {[] a ?Concept} LIMIT 5");

        $this->assertTrue($iterator instanceof IteratorInterface);
        $this->assertTrue($iterator->hasNext());
        $this->assertEquals($iterator->Count(), 5);
    }

    /**
     * @expectedException \SparQL\ConnectionException
     */
    public function test_wrongSparQLDataset()
    {
        $dataset = new SparQLDataset("http://invaliddomain/", SparQLDatasetTest::$SPARQL_NS);
        $iterator = $dataset->getIterator("select distinct ?Concept where {[] a ?Concept} LIMIT 5");

        $this->assertTrue($iterator instanceof IteratorInterface);
        $this->assertTrue($iterator->hasNext());
        $this->assertEquals($iterator->Count(), 0);
    }

    /**
     * @expectedException \SparQL\Exception
     */
    public function test_wrongSparQLDataset2()
    {
        $dataset = new SparQLDataset(SparQLDatasetTest::SPARQL_URL);
        $dataset->getIterator("?Concept where {[] a ?Concept} LIMIT 5");
    }

    public function test_navigateSparQLDataset()
    {
        $dataset = new SparQLDataset(SparQLDatasetTest::SPARQL_URL, SparQLDatasetTest::$SPARQL_NS);
        $iterator = $dataset->getIterator(
            'SELECT  ?name ?meaning
                WHERE 
                {
                    ?s a  dbo:Name;
                    dbp:name  ?name;
                    dbp:meaning  ?meaning 
                    . FILTER (str(?name) = "Garrick")
                }'
        );

        $this->assertTrue($iterator->hasNext());
        $this->assertEquals(2, $iterator->count());

        $this->assertEquals(
            [
                [
                    "name" => "Garrick",
                    "name.type" => "literal",
                    "meaning" => "\"one who governs with a spear\", \"oak tree grove\"",
                    "meaning.type" => "literal",
                ],
                [
                    "name" => "Garrick",
                    "name.type" => "literal",
                    "meaning" => "\"spear king\", \"oak tree grove\"",
                    "meaning.type" => "literal",
                ]
            ],
            $iterator->toArray()
        );
    }
}
