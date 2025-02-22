<?php

namespace G4\DataMapper\Engine\Elasticsearch\Version7\Operators;

use G4\DataMapper\Common\QueryOperatorInterface;
use G4\DataMapper\Common\QueryConnector;
use G4\DataMapper\Common\SingleValue;

/**
 * Class LikeCIOperator
 *
 * Case insensitive LIKE operator.
 *
 * @package G4\DataMapper\Engine\Elasticsearch\Operators
 */
class LikeCIOperator implements QueryOperatorInterface
{
    private $name;

    private $value;

    public function __construct($name, SingleValue $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function format()
    {
        return [
            QueryConnector::NAME_QUERY_STRING => [
                QueryConnector::NAME_QUERY_STRING_QUERY => $this->name . ":" . QueryConnector::WILDCARD . strtolower($this->value) . QueryConnector::WILDCARD
            ]
        ];
    }
}
