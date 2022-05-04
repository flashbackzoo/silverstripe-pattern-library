<?php

namespace Flashbackzoo\SilverstripePatternLibrary\Engine;

use SilverStripe\View\ViewableData;

class StorybookV6 extends Engine
{
    public function generate($data = []) {
        return ViewableData::create()
            ->customise($data)
            ->renderWith(StorybookV6::class);
    }
}
