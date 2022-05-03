<?php

namespace Flashbackzoo\SilverstripePatternLibrary\Adapter;

use SilverStripe\Core\Injector\Injectable;

abstract class Adapter
{
    use Injectable;

    abstract public function generate();
}
