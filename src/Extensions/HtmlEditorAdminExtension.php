<?php

namespace Sunnysideup\CleanerTinyMCEConfig\Extensions;

use SilverStripe\Core\Extension;
use SilverStripe\Core\Injector\Injector;
use Sunnysideup\CleanerTinyMCEConfig\Api\ApplyTinyMceConfigs;

class HtmlEditorAdminExtension extends Extension
{
    public function init()
    {
        // to do - this module needs work!
        // Injector::inst()->get(ApplyTinyMceConfigs::class)->applyAll();
    }
}
