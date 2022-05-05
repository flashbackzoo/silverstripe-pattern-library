<?php

namespace Flashbackzoo\SilverstripePatternLibrary\Engine;

use Flashbackzoo\SilverstripePatternLibrary\Renderer;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;

/**
 * The pattern library framework e.g. Storybook.
 */
abstract class Engine implements Renderer
{
    use Configurable;
    use Injectable;

    /**
     * @config
     *
     * File suffix for the output pattern file e.g. ".js".
     */
    private static string $file_suffix = '';

    public function getFileSuffix(): string
    {
        return $this->config()->get('file_suffix');
    }
}
