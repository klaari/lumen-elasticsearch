<?php

namespace Nord\Lumen\Elasticsearch\Tests\Search\Query\TermLevel;

use Nord\Lumen\Elasticsearch\Search\Query\TermLevel\WildcardQuery;
use Nord\Lumen\Elasticsearch\Tests\Search\Query\AbstractQueryTestCase;
use Nord\Lumen\Elasticsearch\Exceptions\InvalidArgument;

/**
 * Class WildcardQueryTest
 * @package Nord\Lumen\Elasticsearch\Tests\Search\Query\TermLevel
 */
class WildcardQueryTest extends AbstractQueryTestCase
{

    /**
     * @inheritdoc
     */
    public function testToArray()
    {
        // Basic value-only query
        $query = (new WildcardQuery())->setField('foo')->setValue('bar?baz*qux');

        $this->assertEquals([
            'wildcard' => [
                'foo' => [
                    'value' => 'bar?baz*qux',
                ],
            ],
        ], $query->toArray());

        // Value + boost
        $query->setBoost(2.0);

        $this->assertEquals([
            'wildcard' => [
                'foo' => [
                    'value' => 'bar?baz*qux',
                    'boost' => 2.0,
                ],
            ],
        ], $query->toArray());
    }


    public function testToArrayMissingFieldValue()
    {
        $this->expectException(InvalidArgument::class);

        (new WildcardQuery())->toArray();
    }
}
