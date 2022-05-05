<?php

namespace Flashbackzoo\SilverstripePatternLibrary;

use SilverStripe\Dev\BuildTask;

/**
 * @package populate
 */
class GeneratePatternLibraryTask extends BuildTask
{
    protected $title = 'Generate Pattern Library';

    protected $description = 'Generate a pattern library from your theme';

    public function run($request)
    {
        PatternLibrary::create()->generate();
    }
}
