<?php

namespace Flashbackzoo\SilverstripePatternLibrary;

use SilverStripe\Core\Config\Configurable;

class PatternLibrary
{
    use Configurable;

    /**
     * @config
     *
     * Engine to use e.g. "storybook".
     */
    private static string $engine_name = "";

    /**
     * @config
     *
     * Version of the engine to use.
     */
    private static string $engine_version = "";

    /**
     * @config
     *
     * Adaptor class to use when generating stories e.g. Vue3 or React.
     */
    private static string $adaptor = "";

    /**
     * @config
     *
     * List patterns to include in the pattern library.
     */
    private static array $patterns = [];

    /**
     * Generate a pattern library.
     */
    public static function generate()
    {
        foreach (self::config()->get('patterns') as $config) {
            $pattern = Pattern::create();

            $pattern->setTitle($config['title']);
            $pattern->setComponent($config['component']);
            $pattern->setTemplate($config['template']);
            $pattern->setArgs($config['args']);

            var_dump($pattern);
        }
    }
}
