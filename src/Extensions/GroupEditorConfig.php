<?php

namespace Sunnysideup\CleanerTinyMCEConfig\Extensions;

use SilverStripe\ORM\DataExtension;
use SilverStripe\Core\Config\Config;

use Sunnysideup\CleanerTinyMCEConfig\Config\HTMLEditorConfigOptions;

class GroupEditorConfig extends DataExtension
{


    public function getHtmlEditorConfig()
    {
        return Config::inst()->get(HTMLEditorConfigOptions::class, 'main_editor') ?: 'cms';
    }
}
