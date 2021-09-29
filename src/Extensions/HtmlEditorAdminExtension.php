<?php

namespace Sunnysideup\CleanerTinyMCEConfig\Extensions;

use SilverStripe\Core\Extension;
use SilverStripe\Core\Injector\Injector;
use Sunnysideup\CleanerTinyMCEConfig\Api\ApplyTinyMceConfigs;

class HtmlEditorAdminExtension extends Extension
{
    public function init()
    {
        Injector::inst()->get(ApplyTinyMceConfigs::class)->applyAll();
    }
}
