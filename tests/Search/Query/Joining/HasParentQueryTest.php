<?php

namespace Nord\Lumen\Elasticsearch\Tests\Search\Query\Joining;

use Nord\Lumen\Elasticsearch\Exceptions\InvalidArgument;
use Nord\Lumen\Elasticsearch\Search\Query\Compound\BoolQuery;
use Nord\Lumen\Elasticsearch\Search\Query\Joining\HasParentQuery;
use Nord\Lumen\Elasticsearch\Search\Query\ScoreMode;
use Nord\Lumen\Elasticsearch\Search\Query\TermLevel\TermsQuery;
use Nord\Lumen\Elasticsearch\Tests\Search\Query\AbstractQueryTestCase;

/**
 * Class HasParentQueryTest
 * @package Nord\Lumen\Elasticsearch\Tests\Search\Query\Joining
 */
class HasParentQueryTest extends AbstractQueryTestCase
{

    /**
     * @inheritdoc
     */
    public function testToArray()
    {
        $query = new HasParentQuery();
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
            'has_parent' => [
                'parent_type' => 'doc',
                'query'       => [
                    'bool' => [
                        'must' => [
                            ['terms' => ['id' => ['ID1', 'ID2']]],
                        ],
                    ],
                ],
            ],
        ], $query->toArray());

        $query = new HasParentQuery();
        $query->setType('doc')
              ->setQuery(
                  (new BoolQuery())
                      ->addMust(
                          (new TermsQuery())
                              ->setField('id')
                              ->setValues(['ID1', 'ID2'])
                      )
              )
              ->setScoreMode(ScoreMode::MODE_SCORE);

        $this->assertEquals([
            'has_parent' => [
                'parent_type' => 'doc',
                'query'       => [
                    'bool' => [
                        'must' => [
                            ['terms' => ['id' => ['ID1', 'ID2']]],
                        ],
                    ],
                ],
                'score_mode'  => 'score',
            ],
        ], $query->toArray());
    }

    public function testToArrayWithMissingQuery()
    {
        $this->expectException(InvalidArgument::class);

        (new HasParentQuery())->toArray();
    }
}
