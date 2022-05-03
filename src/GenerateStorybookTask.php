<?php

namespace Flashbackzoo\SilverstripeStorybook;

use SilverStripe\Dev\BuildTask;

/**
 * @package populate
 */
class GenerateStorybookTask extends BuildTask
{
    protected $title = 'Generate Storybook';

    protected $description = 'Generate a Storybook from Silverstripe templates';

    public function run($request)
    {
        Storybook::generate();
    }
}
