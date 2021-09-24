<?php

namespace Sunnysideup\TinyHandyEditor\Extensions;

use SilverStripe\Core\Extension;
use SilverStripe\Core\Config\Config;
use SilverStripe\Forms\HTMLEditor\TinyMCEConfig;
use SilverStripe\Core\Manifest\ModuleLoader;

class HtmlEditorAdminExtension extends Extension
{
    public function init()
    {
        $editorConfigs = Config::inst()->get(HTMLEditorFieldShortConfig::class, 'editor_configs');

        $adminModule = ModuleLoader::inst()->getManifest()->getModule('silverstripe/admin');
        $assetsAdminModule = ModuleLoader::inst()->getManifest()->getModule('silverstripe/asset-admin');
        $cmsModule = ModuleLoader::inst()->getManifest()->getModule('silverstripe/cms');

        foreach ($editorConfigs as $editorConfigName => $editorConfigSettings) {

            $editor = TinyMCEConfig::get($editorConfigName);

            // enable plugins
            if(! empty($editorConfigSettings['enabled_plugins'])) {
                $editor->enablePlugins($editorConfigSettings['enabled_plugins']);
            } else {
                $editor->enablePlugins([
                    'charmap',
                    'hr',
                    'contextmenu' => null,
                    'sslink' => $adminModule->getResource('client/dist/js/TinyMCE_sslink.js'),
                    'sslinkexternal' => $adminModule->getResource('client/dist/js/TinyMCE_sslink-external.js'),
                    'sslinkemail' => $adminModule->getResource('client/dist/js/TinyMCE_sslink-email.js'),
                    'sslinkfile' => $assetsAdminModule->getResource('client/dist/js/TinyMCE_sslink-file.js'),
                    'sslinkinternal' => $cmsModule->getResource('client/dist/js/TinyMCE_sslink-internal.js'),
                    'sslinkanchor' => $cmsModule->getResource('client/dist/js/TinyMCE_sslink-anchor.js'),
                ]);
            }

            // disable plugins
            if(! empty($editorConfigSettings['disabled_plugins'])) {
                $editor->disablePlugins($editorConfigSettings['disabled_plugins']);
            }

            // add buttons
            if(! empty($editorConfigSettings['add_buttons'])) {
                foreach($editorConfigSettings['add_buttons'] as $line => $buttons) {
                    $editor->addButtonsToLine($line, $buttons);
                }
            } else {
                $editor->addButtonsToLine(2, ['styleselect']);
                $editor->addButtonsToLine(2, ['hr']);
            }

            // remove buttons
            if(! empty($editorConfigSettings['remove_buttons'])) {
                $editor->removeButtons($editorConfigSettings['remove_buttons']);
            } else {
                $editor->removeButtons(
                    [
                        'outdent',
                        'indent',
                        'numlist',
                        'hr',
                        'pastetext',
                        'pasteword',
                        'visualaid',
                        'anchor',
                        'tablecontrols',
                        'justifyleft',
                        'justifycenter',
                        'justifyright',
                        'strikethrough',
                        'justifyfull',
                        'underline'
                    ]
                );
            }


            // add macrons
            if(! empty($editorConfigSettings['add_macrons'])) {
                $editor
                    ->addButtonsToLine(1, ['charmap'])
                    ->setOption(
                        'charmap_append',
                        [
                            ['256', 'A - macron'],
                            ['274', 'E - macron'],
                            ['298', 'I - macron'],
                            ['332', 'O - macron'],
                            ['362', 'U - macron'],
                            ['257', 'a - macron'],
                            ['275', 'e - macron'],
                            ['299', 'i - macron'],
                            ['333', 'o - macron'],
                            ['363', 'u - macron'],
                        ]
                    );
            }

            //options
            $editor->setOptions(
                [
                    'skin' => 'silverstripe',
                    'importcss_append' => true,
                    'style_formats_merge' => false,
                    'style_formats' => [],
                    'contextmenu' => 'sslink anchor ssmedia ssembed inserttable | cell row column deletetable',
                    'use_native_selects' => false,
                    'paste_as_text'=> true,
                    'paste_text_sticky'=> true,
                    'paste_text_sticky_default'=> true,
                    'paste_text_sticky_default'=> true,
                    'width'=> '702px',
                ]
            );
            if(! empty($editorConfigSettings['options'])) {
                $editor->setOptions($editorConfigSettings['options']);
            }

            // block formats
            if(!empty($editorConfigSettings['block_formats'])) {
                $blocks = [];
                foreach($editorConfigSettings['block_formats'] as $tag => $name) {
                    $blocks[] = $name.'='.$tag;
                }
                $formats = implode(';', $blocks);
                $editor->setOptions(
                    [
                        'block_formats' => $formats,
                        'theme_advanced_blockformats' => $formats,
                    ]
                );
            }
        }
    }
}
