<?php

namespace Flashbackzoo\SilverstripeStorybook;

use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;

class Story
{
    use Configurable;
    use Injectable;

    /**
     * Name of the component.
     */
    protected string $title = "";

    /**
     * Path to the JavaScript (Vue3, React, etc) component file.
     */
    protected string $component = "";

    /**
     * Path to the Silverstripe template for the component.
     */
    protected string $template = "";

    /**
     * Data to render the component with.
     *
     * @see https://storybook.js.org/docs/react/writing-stories/args
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
