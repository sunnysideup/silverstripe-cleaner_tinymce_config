<?php

namespace Sunnysideup\CleanerTinyMCEConfig\Extensions;

use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Extension;
use SilverStripe\Security\Group;
use Sunnysideup\CleanerTinyMCEConfig\Config\HTMLEditorConfigOptions;

/**
 * Class \Sunnysideup\CleanerTinyMCEConfig\Extensions\GroupEditorConfig
 *
 * @property Group|GroupEditorConfig $owner
 */
class GroupEditorConfig extends Extension
{
    public function getHtmlEditorConfig()
    {
        /**
         * @var Group $owner
         */
        $owner = $this->getOwner();
        $originalConfig = $owner->getField('HtmlEditorConfig');

        if ($originalConfig) {
            return $originalConfig;
        }

        return Config::inst()->get(HTMLEditorConfigOptions::class, 'main_editor') ?: 'cms';
    }
}
