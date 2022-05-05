<?php

namespace Flashbackzoo\SilverstripePatternLibrary\Adapter;

use SilverStripe\View\ArrayData;
use SilverStripe\View\ViewableData;

class StorybookVue3 extends Adapter
{
    public function render($data): ArrayData
    {
        $imports = $data->renderWith(StorybookVue3::class . '_Imports');

        $componentTemplate = ViewableData::create()
            ->customise($data->getField('TemplateData'))
            ->renderWith($data->getField('TemplatePath'));

        $patternTemplate = ViewableData::create()
            ->customise(array_merge($data->toMap(), ['ComponentTemplate' => $componentTemplate]))
            ->renderWith(StorybookVue3::class . '_PatternTemplate');

        $args = $data->renderWith(StorybookVue3::class . '_Args');

        return ArrayData::create([
            'Adapter' => array_merge(
                $data->toMap(),
                [
                    'Imports' => $imports,
                    'PatternTemplate' => $patternTemplate,
                    'Args' => $args,
                ],
            ),
        ]);
    }
}
