<?php

namespace Flashbackzoo\SilverstripePatternLibrary\Adapter;

use SilverStripe\View\ViewableData;

class StorybookVue3 extends Adapter
{
    public function generate($data = []) {
        $imports = ViewableData::create()
            ->customise($data)
            ->renderWith(StorybookVue3::class . '_Imports');

        $componentTemplate = ViewableData::create()
            ->customise([])
            ->renderWith($data['TemplatePath']);

        $patternTemplate = ViewableData::create()
            ->customise(array_merge($data, ['ComponentTemplate' => $componentTemplate]))
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
