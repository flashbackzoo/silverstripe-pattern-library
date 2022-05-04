<?php

namespace Flashbackzoo\SilverstripePatternLibrary;

use Flashbackzoo\SilverstripePatternLibrary\Adapter\Adapter;
use Flashbackzoo\SilverstripePatternLibrary\Engine\Engine;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\ORM\ArrayList;
use SilverStripe\View\ArrayData;

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
     * Name of the component to generate a pattern for.
     */
    public string $component_name = '';

    /**
     * Path to the JavaScript (Vue3, React, etc) component to use for the pattern.
     */
    public string $component_path = '';

    /**
     * Path to the Silverstripe template for the component.
     */
    public string $template_path = '';

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
        $paternData = [
            'Title' => $this->title,
            'ComponentName' => $this->component_name,
            'ComponentPath' => $this->component_path,
            'Args' => $this->argsToTemplateData($this->args),
        ];

        $adapterData = $this->adapter->generate($paternData);

        return $this->engine->generate($adapterData)->forTemplate();
    }

    protected function argsToTemplateData(array $args): ArrayList
    {
        $templateData = ArrayList::create();

        foreach ($args as $key => $value) {
            $templateData->push(ArrayData::create(['Key' => $key, 'Value' => $value]));
        }

        return $templateData;
    }

    public function filename() {
        return $this->component_name . $this->engine->config()->get('file_suffix');
    }
}
