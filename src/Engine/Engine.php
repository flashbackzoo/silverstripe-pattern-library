<?php

namespace Flashbackzoo\SilverstripePatternLibrary\Engine;

use SilverStripe\Core\Injector\Injectable;

abstract class Engine
{
    use Injectable;

    abstract public function generate();
}
