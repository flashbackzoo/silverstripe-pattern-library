<?php

namespace Flashbackzoo\SilverstripePatternLibrary\Engine;

use SilverStripe\View\ViewableData;

class StorybookV6 extends Engine
{
    public function generate() {
        $view = ViewableData::create();

        return $view->renderWith(StorybookV6::class)->forTemplate();
    }
}
