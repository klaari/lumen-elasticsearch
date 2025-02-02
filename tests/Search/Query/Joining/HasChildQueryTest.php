<?php

namespace Nord\Lumen\Elasticsearch\Tests\Search\Query\Joining;

use Nord\Lumen\Elasticsearch\Exceptions\InvalidArgument;
use Nord\Lumen\Elasticsearch\Search\Query\Compound\BoolQuery;
use Nord\Lumen\Elasticsearch\Search\Query\Joining\HasChildQuery;
use Nord\Lumen\Elasticsearch\Search\Query\ScoreMode;
use Nord\Lumen\Elasticsearch\Search\Query\TermLevel\TermsQuery;
use Nord\Lumen\Elasticsearch\Tests\Search\Query\AbstractQueryTestCase;

/**
 * Class HasChildQueryTest
 * @package Nord\Lumen\Elasticsearch\Tests\Search\Query\Joining
 */
class HasChildQueryTest extends AbstractQueryTestCase
{

    /**
     * @inheritdoc
     */
    public function testToArray()
    {
        $query = new HasChildQuery();
        $query->setType('doc')
              ->setQuery(
                  (new BoolQuery())
                      ->addMust(
                          (new TermsQuery())
                              ->setField('id')
                              ->setValues(['ID1', 'ID2'])
                      )
              );

        $this->assertEquals([
            'has_child' => [
                'type'  => 'doc',
                'query' => [
                    'bool' => [
                        'must' => [
                            ['terms' => ['id' => ['ID1', 'ID2']]],
                        ],
                    ],
                ],
            ],
        ], $query->toArray());

        $query = new HasChildQuery();
        $query->setType('doc')
              ->setQuery(
                  (new BoolQuery())
                      ->addMust(
                          (new TermsQuery())
                              ->setField('id')
                              ->setValues(['ID1', 'ID2'])
                      )
              )
              ->setScoreMode(ScoreMode::MODE_SUM);

        $this->assertEquals([
            'has_child' => [
                'type'       => 'doc',
                'query'      => [
                    'bool' => [
                        'must' => [
                            ['terms' => ['id' => ['ID1', 'ID2']]],
                        ],
                    ],
                ],
                'score_mode' => 'sum',
            ],
        ], $query->toArray());

        $query = new HasChildQuery();
        $query->setType('doc')
              ->setQuery(
                  (new BoolQuery())
                      ->addMust(
                          (new TermsQuery())
                              ->setField('id')
                              ->setValues(['ID1', 'ID2'])
                      )
              )
              ->setMinChildren(2);

        $this->assertEquals([
            'has_child' => [
                'type'         => 'doc',
                'query'        => [
                    'bool' => [
                        'must' => [
                            ['terms' => ['id' => ['ID1', 'ID2']]],
                        ],
                    ],
                ],
                'min_children' => 2,
            ],
        ], $query->toArray());

        $query = new HasChildQuery();
        $query->setType('doc')
              ->setQuery(
                  (new BoolQuery())
                      ->addMust(
                          (new TermsQuery())
                              ->setField('id')
                              ->setValues(['ID1', 'ID2'])
                      )
              )
              ->setMaxChildren(10);

        $this->assertEquals([
            'has_child' => [
                'type'         => 'doc',
                'query'        => [
                    'bool' => [
                        'must' => [
                            ['terms' => ['id' => ['ID1', 'ID2']]],
                        ],
                    ],
                ],
                'max_children' => 10,
            ],
        ], $query->toArray());
    }

    public function testToArrayWithMissingQuery()
    {
        $this->expectException(InvalidArgument::class);

        (new HasChildQuery())->toArray();
    }
}
