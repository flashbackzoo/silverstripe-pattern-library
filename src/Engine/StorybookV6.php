<?php

namespace Flashbackzoo\SilverstripePatternLibrary\Engine;

use SilverStripe\View\ArrayData;

class StorybookV6 extends Engine
{
    public function render($data): ArrayData
    {
        return ArrayData::create([
            'Engine' => $data->renderWith(StorybookV6::class),
        ]);
    }
}
