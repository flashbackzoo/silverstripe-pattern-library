<?php

namespace Flashbackzoo\SilverstripePatternLibrary\Adapter;

use Flashbackzoo\SilverstripePatternLibrary\Renderer;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;

/**
 * The component framework used by the Engine e.g. Vue3 or React.
 */
abstract class Adapter implements Renderer
{
    use Configurable;
    use Injectable;
}
