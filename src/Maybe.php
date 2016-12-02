<?php declare(strict_types = 1);

namespace ThibaudDauce\Maybe;

use ThibaudDauce\Maybe\Exceptions\CannotCreateJustWithANullValue;

class Maybe
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    static public function nothing()
    {
        return new static(null);
    }

    static public function just($value)
    {
        if (is_null($value)) {
            throw new CannotCreateJustWithANullValue;
        }

        return new static($value);
    }

    public function isNothing()
    {
        return is_null($this->value);
    }

    public function isJust()
    {
        return ! $this->isNothing();
    }

    public function fromDefault($default)
    {
        if ($this->isNothing()) {
            return $default;
        } else {
            return $this->value;
        }
    }

    public function act(callable $nothing, callable $just)
    {
        if ($this->isNothing()) {
            $nothing();
        } else {
            $just($this->value);
        }
    }
}