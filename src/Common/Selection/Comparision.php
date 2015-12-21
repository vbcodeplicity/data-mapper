<?php

namespace G4\DataMapper\Common\Selection;

use G4\DataMapper\Common\Quote;
class Comparision
{

    private $name;

    private $operator;

    private $value;

    public function __construct($name, $operator, $value)
    {
        $this->name     = $name;
        $this->operator = $operator;
        $this->value    = $value;
    }

    public function __toString()
    {
        return sprintf("%s %s %s",
            $this->name,
            $this->operator,
            (string) new Quote($this->value));
    }
}