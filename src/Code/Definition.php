<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

use PhUml\Graphviz\HasNodeIdentifier;
use PhUml\Graphviz\ObjectHashIdentifier;

/**
 * Base class for interfaces and classes
 *
 * It does not support traits yet
 */
class Definition implements HasNodeIdentifier
{
    use ObjectHashIdentifier;

    /** @var string */
    protected $name;

    /** @var Method[] */
    protected $methods;

    /** @var Definition */
    protected $extends;

    /**
     * @param Method[] $methods
     */
    public function __construct(string $name, array $methods = [], Definition $extends = null)
    {
        $this->name = $name;
        $this->methods = $methods;
        $this->extends = $extends;
    }

    public function countMethodsByVisibility(Visibility $modifier): int
    {
        return \count(array_filter($this->methods, function (Method $method) use ($modifier) {
            return $method->hasVisibility($modifier);
        }));
    }

    public function name(): string
    {
        return $this->name;
    }

    /** @return Method[] */
    public function methods(): array
    {
        return $this->methods;
    }

    public function extends(): Definition
    {
        return $this->extends;
    }
}
