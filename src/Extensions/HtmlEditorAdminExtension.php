<?php

namespace Sunnysideup\TinyHandyEditor\Extensions;

use SilverStripe\Core\Extension;
use SilverStripe\Core\Config\Config;
use SilverStripe\Forms\HTMLEditor\TinyMCEConfig;
use SilverStripe\Core\Manifest\ModuleLoader;

use SilverStripe\Core\Injector\Injector;

use Sunnysideup\CleanerTinyMCEConfig\Api\ApplyTinyMceConfigs;

class HtmlEditorAdminExtension extends Extension
{
    public function init()
    {
        Injector::inst()->get(ApplyTinyMceConfigs::class)->applyAll();
    }
}
