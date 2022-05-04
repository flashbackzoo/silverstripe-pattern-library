<?php

namespace Flashbackzoo\SilverstripePatternLibrary\Adapter;

use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;

/**
 * The component framework used by the Engine e.g. Vue3 or React.
 */
abstract class Adapter
{
    use Configurable;
    use Injectable;

    abstract public function generate(array $data = []);
}
