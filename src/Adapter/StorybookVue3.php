<?php

namespace Flashbackzoo\SilverstripePatternLibrary\Adapter;

use SilverStripe\View\ViewableData;

class StorybookVue3 extends Adapter
{
    public function generate($data = []) {
        $imports = ViewableData::create()
            ->customise($data)
            ->renderWith(StorybookVue3::class . '_Imports');

        $patternTemplate = ViewableData::create()
            ->customise($data)
            ->renderWith(StorybookVue3::class . '_PatternTemplate');

        $args = ViewableData::create()
            ->customise($data)
            ->renderWith(StorybookVue3::class . '_Args');

        // TODO: the Engine should define which "slots" it makes available for Adapters e.g. "Imports".
        return array_merge(
            $data,
            [
                'Imports' => $imports,
                'PatternTemplate' => $patternTemplate,
                'Args' => $args,
            ],
        );
    }
}
