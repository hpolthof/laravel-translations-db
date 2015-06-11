<?php namespace Hpolthof\Translation\DataCollector;

use DebugBar\DataCollector\DataCollector;
use Symfony\Component\HttpKernel\DataCollector\Util\ValueExporter;

class TranslationCollector extends DataCollector
{
    protected $items = array();
    protected $collect_data;
    /**
     * Create a ViewCollector
     *
     * @param bool $collectData Collects view data when tru
     */
    public function __construct($collectData = true)
    {
        $this->collect_data = $collectData;
        $this->name = 'translation';
        $this->items = array();
        $this->exporter = new ValueExporter();
    }

    public function getName()
    {
        return 'translation';
    }

    public function getWidgets()
    {
        return array(
            'views' => array(
                'icon' => 'leaf',
                'widget' => 'PhpDebugBar.Widgets.TemplatesWidget',
                'map' => 'translation',
                'default' => '[]'
            ),
            'views:badge' => array(
                'map' => 'translation.count',
                'default' => 0
            )
        );
    }

    public function addTranslation($translation)
    {
        $this->items[] = $translation;
    }

    public function collect()
    {
        $items = $this->items;
        return array(
            'count' => count($items),
            'translation' => $items,
        );
    }
}