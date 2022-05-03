<?php

namespace Flashbackzoo\SilverstripePatternLibrary\Engine;

use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;

abstract class Engine
{
    use Configurable;
    use Injectable;

    /**
     * @config
     *
     * File suffix for the output pattern file e.g. ".js".
     */
    private static string $file_suffix = '';

    abstract public function generate();
}
