<?php

namespace Sunnysideup\CleanerTinyMCEConfig\Api;

use SilverStripe\Control\Director;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Core\Manifest\ModuleLoader;
use SilverStripe\Forms\HTMLEditor\HTMLEditorConfig;
use SilverStripe\Forms\HTMLEditor\TinyMCEConfig;
use SilverStripe\View\Parsers\ShortcodeParser;
use Sunnysideup\CleanerTinyMCEConfig\Config\HTMLEditorConfigOptions;

class ApplyTinyMceConfigs
{
    use Injectable;

    use Configurable;

    public function applyAll()
    {
        $editorConfigs = Injector::inst()->get(HTMLEditorConfigOptions::class)->getEditors();
        $remove = Config::inst()->get(HTMLEditorConfigOptions::class, 'remove_options');

        $adminModule = ModuleLoader::inst()->getManifest()->getModule('silverstripe/admin');
        $assetsAdminModule = ModuleLoader::inst()->getManifest()->getModule('silverstripe/asset-admin');
        $cmsModule = ModuleLoader::inst()->getManifest()->getModule('silverstripe/cms');

        foreach ($editorConfigs as $editorConfigName => $editorConfigSettings) {
            if (! in_array($editorConfigName, $remove, true)) {
                $basedOn = $editorConfigSettings['based_on'] ?? 'cms';
                $editor = clone TinyMCEConfig::get($basedOn);

                $editor->enablePlugins('charmap', 'fullscreen');


                // disable plugins
                if (! empty($editorConfigSettings['disabled_plugins'])) {
                    $editor->disablePlugins(
                        $this->stringToArray($editorConfigSettings['disabled_plugins'])
                    );
                }

                // add buttons
                if (! empty($editorConfigSettings['add_buttons'])) {
                    $addButtons = $this->stringToArray($editorConfigSettings['add_buttons']);
                    foreach ($addButtons as $line => $buttons) {
                        $editor->addButtonsToLine($line, $buttons);
                    }
                }

                // remove buttons
                if (! empty($editorConfigSettings['remove_buttons'])) {
                    $removeButtons = $this->stringToArray($editorConfigSettings['remove_buttons']);
                    $editor->removeButtons($removeButtons);
                }

                if (! empty($editorConfigSettings['add_macrons'])) {
                    $editor
                        ->addButtonsToLine(1, 'charmap')
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
                        )
                    ;
                }

                // lines
                if (! empty($editorConfigSettings['lines'])) {
                    $lines = $editorConfigSettings['lines'];
                    for ($i = 1; $i < 4; ++$i) {
                        $myLine = isset($lines[(int) $i]) ? $this->stringToArray($lines[(int) $i]) : [];
                        if (!empty($myLine)) {
                            $editor->setButtonsForLine($i, $myLine);
                        } else {
                            $editor->setButtonsForLine($i, []);
                        }
                    }
                }

                if (! empty($editorConfigSettings['options'])) {
                    $editor->setOptions($editorConfigSettings['options']);
                }

                if (! empty($editorConfigSettings['style_formats'])) {
                    $editor->setOption('formats', $editorConfigSettings['style_formats']);
                }

                if (! empty($editorConfigSettings['formats'])) {
                    $editor->setOptions(['formats' => $editorConfigSettings['formats']]);
                }

                // block formats
                if (! empty($editorConfigSettings['blocks'])) {
                    $blocks = $this->stringToArray($editorConfigSettings['blocks']) ?? [];
                    foreach ($editorConfigSettings['blocks'] as $tag => $name) {
                        $blocks[] = $name . '=' . $tag;
                    }

                    $formats = implode(';', $blocks);
                    $editor->setOptions(
                        [
                            'block_formats' => $formats,
                            'theme_advanced_blocks' => $formats,
                        ]
                    );
                }
                HTMLEditorConfig::set_config($editorConfigName, $editor);
            } else {
                HTMLEditorConfig::set_config($editorConfigName, null);
            }
        }

        $default = Config::inst()->get(HTMLEditorConfigOptions::class, 'main_editor');
        if ($default) {
            HTMLEditorConfig::set_active_identifier($default);
        }
    }

    protected function stringToArray($mixed): array
    {
        if (! is_array($mixed)) {
            $mixed = explode(',', $mixed);
        }

        // $mixed = array_map('trim', $mixed);
        // $mixed = array_filter($mixed);
        return $mixed;
    }
}
