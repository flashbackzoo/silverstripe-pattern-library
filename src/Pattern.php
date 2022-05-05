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
    protected string $title = '';

    /**
     * Name of the component to generate a pattern for.
     */
    protected string $component_name = '';

    /**
     * Snake cased element used for the component in the Silverstripe template.
     */
    protected string $component_element = '';

    /**
     * Path to the JavaScript (Vue3, React, etc) component to use for the pattern.
     */
    protected string $component_path = '';

    /**
     * Path to the Silverstripe template for the component.
     */
    protected string $template_path = '';

    /**
     * Data passed to the Silverstripe template when it's rendered.
     */
    protected array $template_data = [];

    /**
     * Data passed to the component when rendered by the pattern library.
     */
    protected array $args = [];

    public function __construct(Engine $engine, Adapter $adapter, array $config)
    {
        $this->engine = $engine;
        $this->adapter = $adapter;

        $this->title = isset($config['component']['title'])
            ? $config['component']['title']
            : $config['component']['name'];

        $this->component_name = $config['component']['name'];
        $this->component_element = $config['component']['element'];
        $this->component_path = $config['component']['path'];

        $this->template_path = $config['template']['path'];
        $this->template_data = $config['template']['data'];
    }

    public function generate() {
        $paternData = [
            'Title' => $this->title,
            'ComponentName' => $this->component_name,
            'ComponentPath' => $this->component_path,
            'ComponentElement' => $this->component_element,
            'TemplatePath' => $this->template_path,
            'TemplateData' => $this->template_data,
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
