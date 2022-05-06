<?php

namespace Flashbackzoo\SilverstripePatternLibrary\Engine;

use SilverStripe\View\ArrayData;

class StorybookV6 extends Engine
{
    public function render($data): ArrayData
    {
        $this->onBeforeRender();

        $rendered = ArrayData::create([
            'Engine' => $data->renderWith(StorybookV6::class),
        ]);

        $this->onAfterRender();

        return $rendered;
    }

    protected function onBeforeRender()
    {
        // TODO:
        // If there's a static dir replace SilverStripe\Core\Manifest\ResourceURLGenerator with a custom class.
    }

    protected function onAfterRender()
    {
        // TODO:
        // If there's a static dir swap back to the original class.
    }
}
