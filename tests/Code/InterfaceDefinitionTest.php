<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

use PHPUnit\Framework\TestCase;

class InterfaceDefinitionTest extends TestCase
{
    /** @test */
    function it_knows_its_name()
    {
        $namedInterface = new InterfaceDefinition('NamedInterface');

        $name = $namedInterface->name();

        $this->assertEquals('NamedInterface', $name);
    }

    /** @test */
    function it_has_no_methods_by_default()
    {
        $noMethodsInterface = new InterfaceDefinition('WithNoMethods');

        $methods = $noMethodsInterface->methods();

        $this->assertCount(0, $methods);
    }

    /** @test */
    function it_does_not_extends_another_interface_by_default()
    {
        $interfaceWithoutParent = new InterfaceDefinition('InterfacesWithoutParent');

        $hasParent = $interfaceWithoutParent->hasParent();

        $this->assertFalse($hasParent);
    }

    /** @test */
    function it_knows_its_methods()
    {
        $methods = [
            Method::public('firstMethod'),
            Method::public('secondMethod'),
        ];
        $interfaceWithMethods = new InterfaceDefinition('InterfaceWithMethods', $methods);

        $interfaceMethods = $interfaceWithMethods->methods();

        $this->assertEquals($methods, $interfaceMethods);
    }

    /** @test */
    function it_knows_its_parent_interface()
    {
        $parent = new InterfaceDefinition('ParentInterface');
        $interfaceWithParent = new InterfaceDefinition('InterfaceWithMethods', [], $parent);

        $parentClass = $interfaceWithParent->extends();

        $this->assertEquals($parent, $parentClass);
    }

    /** @test */
    function it_has_an_identifier()
    {
        $interface = new InterfaceDefinition('InterfaceWithIdentifier');

        $interfaceId = $interface->identifier();

        $this->assertRegExp('/^[0-9A-Fa-f]{32}$/', $interfaceId);
    }

    /** @test */
    function its_identifier_is_unique_per_object()
    {
        $interfaceOne = new InterfaceDefinition('InterfaceOne');
        $interfaceTwo = new InterfaceDefinition('InterfaceOne');

        $this->assertNotEquals($interfaceOne->identifier(), $interfaceTwo->identifier());
        $this->assertEquals($interfaceOne->identifier(), $interfaceOne->identifier());
        $this->assertEquals($interfaceTwo->identifier(), $interfaceTwo->identifier());
    }
}
