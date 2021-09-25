<?php


namespace Sunnysideup\CleanerTinyMCEConfig\Api;

use SilverStripe\Forms\HTMLEditor\TinyMCEConfig;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Extension;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Manifest\ModuleLoader;
use SilverStripe\Core\Injector\Injector;

use Sunnysideup\CleanerTinyMCEConfig\Config\HTMLEditorConfigOptions;
/**
 * example:
 * ```yml
 *     [
 *         'config1' => [
 *             'enabled_plugins' => [A, B, C],
 *             'disabled_plugins' => [A, B, C],
 *             'add_buttons' => [
 *                 1: [A, B, C],
 *                 2: [A, B, C],
 *                 3: [A, B, C],
 *             ],
 *             'remove_buttons' => [A, B, C],
 *             'add_macrons' => true,
 *             'lines' => [
 *                 1 => [],
 *                 2 => [],
 *                 3 => [],
 *             ],
 *             'options' => [
 *                 'skin' => 'silverstripe',
 *                 'width' => '80ch',
 *             ],
 *             'block_formats' => [
 *                 'p' => 'paragraph',
 *                 'p' => 'paragraph',
 *             ]
 *         ]
 *
 *     ]
 *
 */


class ApplyTinyMceConfigs
{

    use Injectable;

    use Configurable;

    public function applyAll()
    {
        $editorConfigs = Config::inst()->get(HTMLEditorConfigOptions::class, 'editor_configs');
        $remove = Config::inst()->get(HTMLEditorConfigOptions::class, 'remove_options');

        $adminModule = ModuleLoader::inst()->getManifest()->getModule('silverstripe/admin');
        $assetsAdminModule = ModuleLoader::inst()->getManifest()->getModule('silverstripe/asset-admin');
        $cmsModule = ModuleLoader::inst()->getManifest()->getModule('silverstripe/cms');

        foreach ($editorConfigs as $editorConfigName => $editorConfigSettings) {
            if(! in_array($editorConfigName, $remove)) {

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
                    $a = $this->stringToArray($editorConfigSettings['disabled_plugins']);
                    $editor->disablePlugins($a);
                }

                // add buttons
                if(! empty($editorConfigSettings['add_buttons'])) {
                    $addButtons = $this->stringToArray($editorConfigSettings['add_buttons']);
                    foreach($addButtons as $line => $buttons) {
                        $editor->addButtonsToLine($line, $buttons);
                    }
                } else {
                    $editor->addButtonsToLine(2, ['styleselect']);
                    $editor->addButtonsToLine(2, ['hr']);
                }

                // remove buttons
                if(! empty($editorConfigSettings['remove_buttons'])) {
                    $removeButtons = $this->stringToArray($editorConfigSettings['remove_buttons']);
                    $editor->removeButtons($removeButtons);
                } else {
                    $editor->removeButtons(
                        [
                            'outdent',
                            'indent',
                            // 'numlist',
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

                // lines
                if(! empty($editorConfigSettings['lines'])) {
                    $lines = $editorConfigSettings['lines'];
                    for($i = 1; $i < 4; $i++) {
                        if(! isset($lines[$i])) {
                            $lines[$i] = [];
                        } else {
                            $lines[$i] = $this->stringToArray($lines[$i]);
                        }
                        $editor->setButtonsForLine($i, $lines);
                    }
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
                    $blockFormats = $this->stringToArray($editorConfigSettings['block_formats']);
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

    protected function stringToArray($mixed) : array
    {
        if(! is_array($mixed)) {
            $mixed = explode(',', $mixed);
        }
        // $mixed = array_map('trim', $mixed);
        // $mixed = array_filter($mixed);
        return $mixed;
    }

}
