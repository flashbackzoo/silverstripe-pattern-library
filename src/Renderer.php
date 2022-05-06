<?php

namespace Flashbackzoo\SilverstripePatternLibrary;

use SilverStripe\View\ArrayData;

interface Renderer
{
    public function render(ArrayData $data): ArrayData;
}
