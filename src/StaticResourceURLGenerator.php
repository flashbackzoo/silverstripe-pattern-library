<?php

namespace Flashbackzoo\SilverstripePatternLibrary;

use SilverStripe\Core\Injector\Injectable;
use SilverStripe\Core\Manifest\ResourceURLGenerator;

class StaticResourceURLGenerator implements ResourceURLGenerator
{
    use Injectable;

    protected string $staticDir = '';

    public function __construct(string $staticDir)
    {
        $this->staticDir = trim($staticDir, './');
    }

    public function urlForResource($resource)
    {
        return str_replace($this->staticDir, '', $resource);
    }
}
