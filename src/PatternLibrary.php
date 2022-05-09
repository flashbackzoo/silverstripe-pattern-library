<?php

namespace Flashbackzoo\SilverstripePatternLibrary;

use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Core\Manifest\ResourceURLGenerator;
use SilverStripe\ORM\ArrayLib;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\View\ArrayData;
use SilverStripe\View\SSViewer;
use SilverStripe\View\ViewableData;
use Symfony\Component\Yaml\Parser;

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

        $output = $this->getOutputDirectory();

        if (!is_dir($output)) {
            mkdir($output);
        }

        $parser = new Parser();

        foreach ($config->get('patterns') as $configFile) {
            $config = $parser->parse($this->readPatternConfigFile($configFile));
            $data = $this->configToPatternLibraryTemplateData($config);
            $pattern = $this->renderPatternLibraryTemplates($data, [$adapter, $engine]);
            $filename = isset($config['component']['name'])
                ? $config['component']['name'] . $engine->getFileSuffix()
                : array_keys($config)[0] . $engine->getFileSuffix();

            $this->writePatternFile($filename, $pattern);
        }
    }

    protected function readPatternConfigFile($filePath)
    {
        return file_get_contents($this->baseDirectory() . '/' . ltrim($filePath, './'));
    }

    protected function baseDirectory()
    {
        return dirname(getcwd());
    }

    /**
     * Prepare user defined config to be rendered by the pattern library Silverstripe templates.
     */
    protected function configToPatternLibraryTemplateData(array $config): ViewableData
    {
        $data = ArrayData::create([]);
        $values = array_values($config)[0];

        $data->setField('Title', $values['title']);

        if (isset($values['args'])) {
            $argsList = ArrayList::create();

            foreach ($values['args'] as $key => $value) {
                $argsList->push(ArrayData::create(['Key' => $key, 'Value' => $value]));
            }

            $data->setField('Args', $argsList);
        }

        if (isset($values['component'])) {
            $data->setField(
                'Component',
                ArrayData::create([
                    'Title' => isset($values['component']['title'])
                        ? $values['component']['title']
                        : $values['component']['name'],
                    'Name' => $values['component']['name'],
                    'Path' => $values['component']['path'],
                    'Element' => $values['component']['element'],
                ]),
            );
        }

        $data->setField('Template', $this->renderComponentTemplate($values['template']));

        return $data;
    }

    protected function renderComponentTemplate(array $templateConfig): DBHTMLText
    {
        $staticDir = $this->config()->get('static_dir');

        // Disable hash re-writing.
        // See https://docs.silverstripe.org/en/4/developer_guides/templates/how_tos/disable_anchor_links/
        $origRewriteDefault = SSViewer::getRewriteHashLinksDefault();
        SSViewer::setRewriteHashLinksDefault(false);

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

        // Reset hash re-writing.
        SSViewer::setRewriteHashLinksDefault($origRewriteDefault);

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
        return file_put_contents($this->getOutputDirectory() . '/' . $filename, $content);
    }

    protected function getOutputDirectory(): string
    {
        return $this->baseDirectory() . '/' . trim($this->config()->get('output'), './');
    }
}
