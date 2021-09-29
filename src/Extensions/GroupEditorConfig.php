<?php

namespace Sunnysideup\CleanerTinyMCEConfig\Extensions;

use SilverStripe\ORM\DataExtension;
use SilverStripe\Core\Config\Config;

use Sunnysideup\CleanerTinyMCEConfig\Config\HTMLEditorConfigOptions;

class GroupEditorConfig extends DataExtension
{


    public function getHtmlEditorConfig()
    {
        $originalConfig = $this->owner->getField("HtmlEditorConfig");

        if ($originalConfig) {
            return $originalConfig;
        }
        return Config::inst()->get(HTMLEditorConfigOptions::class, 'main_editor') ?: 'cms';
    }
}
