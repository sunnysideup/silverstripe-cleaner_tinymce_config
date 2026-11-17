<?php

namespace Sunnysideup\CleanerTinyMCEConfig\Extensions;

use SilverStripe\Admin\FormSchemaController;
use SilverStripe\Admin\LeftAndMain;
use SilverStripe\Core\Extension;
use SilverStripe\Core\Injector\Injector;
use Sunnysideup\CleanerTinyMCEConfig\Api\ApplyTinyMceConfigs;

/**
 * Applies the editor configs for FormSchemaControllers that are not LeftAndMain
 * (e.g. the inline edit forms of elemental blocks, served by ElementalAreaController).
 *
 * LeftAndMain is already covered by HtmlEditorAdminExtension.
 *
 * @property FormSchemaController|FormSchemaControllerEditorConfig $owner
 */
class FormSchemaControllerEditorConfig extends Extension
{
    public function onBeforeInit()
    {
        if ($this->getOwner() instanceof LeftAndMain) {
            return;
        }

        Injector::inst()->get(ApplyTinyMceConfigs::class)->applyAll();
    }
}
