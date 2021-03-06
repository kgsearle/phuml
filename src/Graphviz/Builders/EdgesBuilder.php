<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz\Builders;

use PhUml\Code\ClassDefinition;
use PhUml\Code\Structure;
use PhUml\Code\Variable;
use PhUml\Graphviz\Edge;

/**
 * It creates edges by inspecting a class
 *
 * 1. It creates edges by inspecting the attributes of a class
 * 2. It creates edges by inspecting the parameters of the constructor of a class
 */
class EdgesBuilder implements AssociationsBuilder
{
    /** @var bool[] */
    private $associations = [];

    /**
     * It creates an edge if the attribute
     *
     * - Has type information and it's not a PHP's built-in type
     * - The association hasn't already been resolved
     *
     * @return \PhUml\Graphviz\HasDotRepresentation[]
     */
    public function fromAttributes(ClassDefinition $class, Structure $structure): array
    {
        return array_map(function (Variable $attribute) use ($class, $structure) {
            return $this->addAssociation($class, $attribute, $structure);
        }, array_filter($class->attributes(), [$this, 'needAssociation']));
    }

    /**
     * It creates an edge if the constructor parameter
     *
     * - Has type information and it's not a PHP's built-in type
     * - The association hasn't already been resolved
     *
     * @return \PhUml\Graphviz\HasDotRepresentation[]
     */
    public function fromConstructor(ClassDefinition $class, Structure $structure): array
    {
        if (!$class->hasConstructor()) {
            return [];
        }
        return array_map(function (Variable $attribute) use ($class, $structure) {
            return $this->addAssociation($class, $attribute, $structure);
        }, array_filter($class->constructorParameters(), [$this, 'needAssociation']));
    }

    private function addAssociation(ClassDefinition $class, Variable $attribute, Structure $structure): Edge
    {
        $this->markAssociationResolvedFor($attribute);
        return Edge::association(
            $structure->get((string)$attribute->type()),
            $class
        );
    }

    private function needAssociation(Variable $attribute): bool
    {
        return $attribute->isAReference() && !$this->isAssociationResolved($attribute->type());
    }

    private function isAssociationResolved(string $type): bool
    {
        return array_key_exists(strtolower($type), $this->associations);
    }

    private function markAssociationResolvedFor(Variable $attribute): void
    {
        $this->associations[strtolower($attribute->type())] = true;
    }
}
