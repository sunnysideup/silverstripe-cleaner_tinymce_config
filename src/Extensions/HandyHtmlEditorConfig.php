<?php

namespace Sunnysideup\CleanerTinyMCEConfig\Extensions;

use SilverStripe\ORM\DataExtension;
use SilverStripe\Core\Config\Config;

class HandyHtmlEditorConfig extends DataExtension
{

    private static $main_editor = '';

    public function getHtmlEditorConfig()
    {
        return Config::inst()->get(HandyHtmlEditorConfig::class, 'main_editor') ?: 'cms';
    }
}
