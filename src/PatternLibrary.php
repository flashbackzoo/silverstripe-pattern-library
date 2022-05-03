<?php

namespace Flashbackzoo\SilverstripePatternLibrary;

use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injector;

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
     * Adapter class to use when generating stories e.g. Vue3 or React.
     */
    private static string $adapter = "";

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
        $config = self::config();

        $engine = Injector::inst()->create($config->get('engine'));
        $adapter = Injector::inst()->create($config->get('adapter'));

        foreach ($config->get('patterns') as $config) {
            $pattern = Pattern::create($engine, $adapter);

            $pattern->title = $config['title'];
            $pattern->component = $config['component'];
            $pattern->template = $config['template'];
            $pattern->args = $config['args'];

            echo $pattern->generate();
        }
    }
}
