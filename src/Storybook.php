<?php

namespace Flashbackzoo\SilverstripeStorybook;

use SilverStripe\Core\Config\Configurable;

class Storybook
{
    use Configurable;

    /**
     * @config
     *
     * Version of Storybook to output.
     */
    private static string $version = "6";

    /**
     * @config
     *
     * Adaptor class to use when generating stories e.g. Vue3 or React.
     */
    private static string $adaptor = "";

    /**
     * @config
     *
     * List stories to include in the storybook.
     */
    private static array $stories = [];

    /**
     * Generate a storybook.
     */
    public static function generate()
    {
        foreach (self::config()->get('stories') as $config) {
            $story = Story::create();

            $story->setTitle($config['title']);
            $story->setComponent($config['component']);
            $story->setTemplate($config['template']);
            $story->setArgs($config['args']);

            var_dump($story);
        }
    }
}
