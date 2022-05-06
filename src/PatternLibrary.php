<?php

namespace Flashbackzoo\SilverstripePatternLibrary;

use SilverStripe\Dev\Debug;
use SilverStripe\Assets\Filesystem;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\ORM\ArrayLib;
use SilverStripe\ORM\ArrayList;
use SilverStripe\View\ArrayData;
use SilverStripe\View\ViewableData;

class PatternLibrary
{
    use Configurable;
    use Injectable;

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
            $data = $this->patternConfigToTemplateData($config);
            $pattern = $this->renderTemplates($data, [$adapter, $engine]);

            $filename = $config['component']['name'] . $engine->getFileSuffix();
            $this->writePatternFile($filename, $pattern);
        }
    }

    protected function patternConfigToTemplateData(array $config): ViewableData
    {
        $argsList = ArrayList::create();

        if (isset($config['args'])) {
            foreach ($config['args'] as $key => $value) {
                $argsList->push(ArrayData::create(['Key' => $key, 'Value' => $value]));
            }
        }

        $templateData = $this->arrayToTemplateData($config['template']['data'], ArrayData::create([]));

        return ArrayData::create([
            'Component' => ArrayData::create([
                'Title' => isset($config['component']['title'])
                    ? $config['component']['title']
                    : $config['component']['name'],
                'Name' => $config['component']['name'],
                'Path' => $config['component']['path'],
                'Element' => $config['component']['element'],
            ]),
            'Template' => $templateData->renderWith($config['template']['name']),
            'Args' => $argsList,
        ]);
    }

    protected function arrayToTemplateData(array $config, ViewableData $data): ViewableData
    {
        foreach ($config as $key => $configValue) {
            $fieldValue = $configValue;

            if (is_array($configValue)) {
                $dataClass = ArrayLib::is_associative($configValue) ? ArrayData::class : ArrayList::class;
                $fieldValue = $this->arrayToTemplateData($configValue, $dataClass::create([]));
            }

            if ($data instanceof ArrayList) {
                $data->add($fieldValue);
            } else {
                $data->setField($key, $fieldValue);
            }
        }

        return $data;
    }

    public function renderTemplates(ViewableData $data, array $renderers): string
    {
        if (empty($renderers)) {
            return $data->renderWith(PatternLibrary::class)->forTemplate();
        }

        $renderer = array_shift($renderers);

        return $this->renderTemplates($renderer->render($data), $renderers);
    }

    protected function writePatternFile(string $filename, string $content)
    {
        $outputDir = rtrim($this->config()->get('output'), '/');

        return file_put_contents($outputDir . '/' . $filename, $content);
    }
}
