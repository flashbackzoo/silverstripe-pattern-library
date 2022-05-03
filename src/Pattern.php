<?php

namespace Flashbackzoo\SilverstripePatternLibrary;

use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;

class Pattern
{
    use Configurable;
    use Injectable;

    /**
     * Name of the pattern.
     */
    protected string $title = "";

    /**
     * Path to the JavaScript (Vue3, React, etc) component to use for the pattern.
     */
    protected string $component = "";

    /**
     * Path to the Silverstripe template for the component.
     */
    protected string $template = "";

    /**
     * Data to render the component with.
     */
    protected array $args = [];

    public function setTitle(string $value): void
    {
        $this->title = $value;
    }

    public function setComponent(string $value): void
    {
        $this->component = $value;
    }

    public function setTemplate(string $value): void
    {
        $this->template = $value;
    }

    public function setArgs(array $value): void
    {
        $this->args = $value;
    }
}
