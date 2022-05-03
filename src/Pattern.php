<?php

namespace Flashbackzoo\SilverstripePatternLibrary;

use Flashbackzoo\SilverstripePatternLibrary\Adapter\Adapter;
use Flashbackzoo\SilverstripePatternLibrary\Engine\Engine;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;

class Pattern
{
    use Configurable;
    use Injectable;

    /**
     * Engine used to generate the pattern library e.g. Storybook.
     */
    protected Engine $engine;

    /**
     * Adapter used to generate the pattern library e.g. Vue3.
     */
    protected Adapter $adapter;

    /**
     * Name of the pattern.
     */
    public string $title = '';

    /**
     * Path to the JavaScript (Vue3, React, etc) component to use for the pattern.
     */
    public string $component = '';

    /**
     * Path to the Silverstripe template for the component.
     */
    public string $template = '';

    /**
     * Data to render the component with.
     */
    public array $args = [];

    public function __construct($engine, $adapter)
    {
        $this->engine = $engine;
        $this->adapter = $adapter;
    }

    public function generate() {
        return $this->engine->generate();
    }

    public function filename() {
        return $this->title . $this->engine->config()->get('file_suffix');
    }
}
