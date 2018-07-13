<?php

namespace Sunnysideup\CleanerTinyMCEConfig\Forms;

use HtmlEditorField;


use SilverStripe\Core\Injector\Injector;
use SilverStripe\Core\Config\Config;
use Sunnysideup\CleanerTinyMCEConfig\Forms\HtmlEditorFieldShort;




class HtmlEditorFieldShort extends HtmlEditorField
{

    private static $config_class = 'HtmlEditorFieldShort_Config';

    private static $number_of_rows = 7;

    private static $default_config_name = 'cms';

    public static function create(){
        $args = func_get_args();

        // Class to create should be the calling class if not Object,
        // otherwise the first parameter
        $class = get_called_class();
        if($class == 'Object') $class = array_shift($args);

        $class = 'HtmlEditorField';

        $obj = Injector::inst()->createWithArgs($class, $args);

        $configClass = Config::inst()->get(HtmlEditorFieldShort::class, 'config_class');
        $configClassObject = Injector::inst()->get($configClass);
        $configName = Config::inst()->get(HtmlEditorFieldShort::class, 'default_config_name');

        $configClassObject->setConfig($configName);

        $rows = $configClassObject->getNumberOfRows();
        if(! $rows) {
            $rows = Config::inst()->get(HtmlEditorFieldShort::class, 'number_of_rows');
        }
        $obj->setRows($rows);

        return $obj;
    }



}
