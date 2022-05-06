<?php

namespace Flashbackzoo\SilverstripePatternLibrary;

use SilverStripe\Assets\Filesystem;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Core\Manifest\ResourceURLGenerator;
use SilverStripe\ORM\ArrayLib;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\View\ArrayData;
use SilverStripe\View\ViewableData;

class PatternLibrary
{
    use Configurable;
    use Injectable;

    /**
     * @config
     *
     * Directory where static assets are served.
     */
    private static string $static_dir = '';

    /**
     * @config
     *
     * Engine to use e.g. "storybook".
     */
    private static string $engine = '';

    /**
     * @config
     *
     * Adapter class to use when generating stories e.g. Vue3 or React.
     */
    private static string $adapter = '';

    /**
     * @config
     *
     * List patterns to include in the pattern library.
     */
    private static array $patterns = [];

    /**
     * @config
     *
     * List patterns to include in the pattern library.
     */
    private static string $output = '';

    /**
     * Generate a pattern library.
     */
    public function generate()
    {
        $config = $this->config();

        $engine = Injector::inst()->create($config->get('engine'));
        $adapter = Injector::inst()->create($config->get('adapter'));

        Filesystem::makeFolder($config->get('output'));

        foreach ($config->get('patterns') as $config) {
            $data = $this->configToPatternLibraryTemplateData($config);
            $pattern = $this->renderPatternLibraryTemplates($data, [$adapter, $engine]);
            $filename = $config['component']['name'] . $engine->getFileSuffix();
            $this->writePatternFile($filename, $pattern);
        }
    }

    /**
     * Prepare user defined config to be rendered by the pattern library Silverstripe templates.
     */
    protected function configToPatternLibraryTemplateData(array $config): ViewableData
    {
        $argsList = ArrayList::create();

        if (isset($config['args'])) {
            foreach ($config['args'] as $key => $value) {
                $argsList->push(ArrayData::create(['Key' => $key, 'Value' => $value]));
            }
        }

        return ArrayData::create([
            'Component' => ArrayData::create([
                'Title' => isset($config['component']['title'])
                    ? $config['component']['title']
                    : $config['component']['name'],
                'Name' => $config['component']['name'],
                'Path' => $config['component']['path'],
                'Element' => $config['component']['element'],
            ]),
            'Template' => $this->renderComponentTemplate($config['template']),
            'Args' => $argsList,
        ]);
    }

    protected function renderComponentTemplate(array $templateConfig): DBHTMLText
    {
        $staticDir = $this->config()->get('static_dir');

        if ($staticDir) {
            // Swap out the ResourceURLGenerator so assets are served by the pattern library instead of Silverstripe.
            Injector::nest();
            Injector::inst()->registerService(
                StaticResourceURLGenerator::create($staticDir),
                ResourceURLGenerator::class,
            );
        }

        $templateData = $this->configToComponentTemplateData($templateConfig['data'], ArrayData::create([]));
        $renderedTemplate = $templateData->renderWith($templateConfig['name']);

        // Reset the ResourceURLGenerator.
        if ($staticDir) {
            Injector::unnest();
        }

        return $renderedTemplate;
    }

    /**
     * Turn the component template data (an array defined in config) into something that the Silverstripe templating
     * engine can use. Basically wrap evething in an ArrayData or ArrayList so template loops etc work.
     */
    protected function configToComponentTemplateData(array $config, ViewableData $data): ViewableData
    {
        foreach ($config as $key => $configValue) {
            $fieldValue = $configValue;

            if (is_array($configValue)) {
                $dataClass = ArrayLib::is_associative($configValue) ? ArrayData::class : ArrayList::class;
                $fieldValue = $this->configToComponentTemplateData($configValue, $dataClass::create([]));
            }

            if ($data instanceof ArrayList) {
                $data->add($fieldValue);
            } else {
                $data->setField($key, $fieldValue);
            }
        }

        return $data;
    }

    public function renderPatternLibraryTemplates(ViewableData $data, array $renderers): string
    {
        if (empty($renderers)) {
            return $data->renderWith(PatternLibrary::class)->forTemplate();
        }

        $renderer = array_shift($renderers);

        return $this->renderPatternLibraryTemplates($renderer->render($data), $renderers);
    }

    protected function writePatternFile(string $filename, string $content)
    {
        $outputDir = rtrim($this->config()->get('output'), '/');

        return file_put_contents($outputDir . '/' . $filename, $content);
    }
}
