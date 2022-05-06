<?php

namespace Flashbackzoo\SilverstripePatternLibrary\Adapter;

use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\View\ArrayData;
use SilverStripe\View\ViewableData;

class StorybookVue3 extends Adapter
{
    public function render($data): ArrayData
    {
        return $data->setField(
            'Adapter',
            [
                'Imports' => $this->renderSlot($data, 'Imports'),
                'Template' => $this->renderSlot($data, 'Template'),
                'Args' => $this->renderSlot($data, 'Args'),
            ],
        );
    }

    protected function renderSlot(ViewableData $data, string $slot): DBHTMLText
    {
        return $data->renderWith(StorybookVue3::class . '_' . $slot);
    }
}
