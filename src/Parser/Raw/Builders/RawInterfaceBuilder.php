<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Raw\Builders;

use PhpParser\Node\Stmt\Interface_;
use PhUml\Parser\Raw\RawDefinition;

/**
 * It builds an associative array with meta-information of an interface
 *
 * The array has the following structure
 *
 * - `interface` The interface name
 * - `methods` The meta-information of the methods of the interface
 * - `extends` The name of the interface it extends, if any
 *
 * @see MethodsBuilder for more details about the methods information
 */
class RawInterfaceBuilder
{
    /** @var MethodsBuilder */
    private $methodsBuilder;

    /**
     * @param MethodsBuilder $methodsBuilder
     */
    public function __construct(MethodsBuilder $methodsBuilder = null)
    {
        $this->methodsBuilder = $methodsBuilder ?? new MethodsBuilder();
    }

    public function build(Interface_ $interface): RawDefinition
    {
        return RawDefinition::interface([
            'interface' => $interface->name,
            'methods' => $this->methodsBuilder->build($interface->getMethods()),
            'extends' => !empty($interface->extends) ? end($interface->extends)->getLast() : null,
        ]);
    }
}
