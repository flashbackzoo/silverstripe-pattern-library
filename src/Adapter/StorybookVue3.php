<?php

namespace Flashbackzoo\SilverstripePatternLibrary\Adapter;

use SilverStripe\View\ViewableData;

class StorybookVue3 extends Adapter
{
    public function generate($data = []) {
        $imports = ViewableData::create()
            ->customise($data)
            ->renderWith(StorybookVue3::class . '_Imports');

        return array_merge(
            $data,
            [
                'Imports' => $imports,
            ],
        );
    }
}
