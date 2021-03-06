<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

interface HasVisibility
{
    public function isPublic(): bool;

    public function isPrivate(): bool;

    public function isProtected(): bool;

    public function hasVisibility(Visibility $modifier): bool;
}
