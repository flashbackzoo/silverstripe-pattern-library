<?php

namespace Flashbackzoo\SilverstripePatternLibrary\Adapter;

use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;

abstract class Adapter
{
    use Configurable;
    use Injectable;

    abstract public function generate();
}
