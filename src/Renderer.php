<?php

namespace Flashbackzoo\SilverstripePatternLibrary;

use SilverStripe\View\ViewableData;

interface Renderer
{
    public function render(ViewableData $data): ViewableData;
}
