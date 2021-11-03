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
                $editor = TinyMCEConfig::get($editorConfigName);
                /** @var TinyMCEConfig $editorConfig */
                $editor
                    ->enablePlugins([
                        'contextmenu' => null,
                        'image' => null,
                        'anchor' => null,
                        'sslink' => $module->getResource('client/dist/js/TinyMCE_sslink.js'),
                        'sslinkexternal' => $module->getResource('client/dist/js/TinyMCE_sslink-external.js'),
                        'sslinkemail' => $module->getResource('client/dist/js/TinyMCE_sslink-email.js'),
                    ])
                    ->setOptions([
                        'friendly_name' => 'Default CMS',
                        'priority' => '50',
                        'skin' => 'silverstripe',
                        'body_class' => 'typography',
                        'contextmenu' => "sslink anchor ssmedia ssembed inserttable | cell row column deletetable",
                        'use_native_selects' => false,
                        'valid_elements' => "@[id|class|style|title],a[id|rel|rev|dir|tabindex|accesskey|type|name|href|target|title"
                            . "|class],-strong/-b[class],-em/-i[class],-strike[class],-u[class],#p[id|dir|class|align|style],-ol[class],"
                            . "-ul[class],-li[class],br,img[id|dir|longdesc|usemap|class|src|border|alt=|title|width|height|align|data*],"
                            . "-sub[class],-sup[class],-blockquote[dir|class],-cite[dir|class|id|title],"
                            . "-table[cellspacing|cellpadding|width|height|class|align|summary|dir|id|style],"
                            . "-tr[id|dir|class|rowspan|width|height|align|valign|bgcolor|background|bordercolor|style],"
                            . "tbody[id|class|style],thead[id|class|style],tfoot[id|class|style],"
                            . "#td[id|dir|class|colspan|rowspan|width|height|align|valign|scope|style],"
                            . "-th[id|dir|class|colspan|rowspan|width|height|align|valign|scope|style],caption[id|dir|class],"
                            . "-div[id|dir|class|align|style],-span[class|align|style],-pre[class|align],address[class|align],"
                            . "-h1[id|dir|class|align|style],-h2[id|dir|class|align|style],-h3[id|dir|class|align|style],"
                            . "-h4[id|dir|class|align|style],-h5[id|dir|class|align|style],-h6[id|dir|class|align|style],hr[class],"
                            . "dd[id|class|title|dir],dl[id|class|title|dir],dt[id|class|title|dir]",
                        'extended_valid_elements' => "img[class|src|alt|title|hspace|vspace|width|height|align|name"
                            . "|usemap|data*],iframe[src|name|width|height|align|frameborder|marginwidth|marginheight|scrolling],"
                            . "object[width|height|data|type],param[name|value],map[class|name|id],area[shape|coords|href|target|alt]"
                    ]);
                // enable ability to insert anchors
                $editor->insertButtonsAfter('sslink', 'anchor');
                // enable plugins
                if (! empty($editorConfigSettings['enabled_plugins'])) {
                    $editor->enablePlugins($editorConfigSettings['enabled_plugins']);
                } else {
                    $editor->enablePlugins(
                        [
                            'charmap',
                            'hr',
                            'fullscreen',
                            'contextmenu',
                            'anchor',
                            'autolink',
                            'sslink' => $adminModule->getResource('client/dist/js/TinyMCE_sslink.js'),
                            'sslinkexternal' => $adminModule->getResource('client/dist/js/TinyMCE_sslink-external.js'),
                            'sslinkemail' => $adminModule->getResource('client/dist/js/TinyMCE_sslink-email.js'),

                            'sslinkfile' => $assetsAdminModule->getResource('client/dist/js/TinyMCE_sslink-file.js'),

                            'ssembed' => $assetsAdminModule->getResource('client/dist/js/TinyMCE_ssembed.js'),
                            'ssmedia' => $assetsAdminModule->getResource('client/dist/js/TinyMCE_ssmedia.js'),

                            'sslinkinternal' => $cmsModule->getResource('client/dist/js/TinyMCE_sslink-internal.js'),
                            'sslinkanchor' => $cmsModule->getResource('client/dist/js/TinyMCE_sslink-anchor.js'),
                        ]
                    );
                }

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
                            'underline',
                        ]
                    );
                }

                // add macrons
                if (! empty($editorConfigSettings['add_macrons'])) {
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
                        )
                    ;
                }

                // lines
                if (! empty($editorConfigSettings['lines'])) {
                    $lines = $editorConfigSettings['lines'];
                    for ($i = 1; $i < 4; ++$i) {
                        $lines[$i] = isset($lines[$i]) ? $this->stringToArray($lines[$i]) : [];
                        $editor->setButtonsForLine($i, implode(', ', $lines[$i]));
                    }
                }

                //options
                $editor->setOptions(
                    [
                        'fix_list_elements' => true,
                        'skin' => 'silverstripe',
                        'importcss_append' => true,
                        'style_formats_merge' => false,
                        'style_formats' => [],
                        'contextmenu' => 'sslink anchor ssmedia ssembed inserttable | cell row column deletetable',
                        'use_native_selects' => false,
                        'paste_as_text' => true,
                        'paste_text_sticky' => true,
                        'paste_text_sticky_default' => true,
                        'width' => '100%',
                        'mode' => 'none',
                        'body_class' => 'typography',
                        'document_base_url' => Director::absoluteBaseURL(),
                        'cleanup_callback' => 'sapphiremce_cleanup',
                        'browser_spellcheck' => true,
                        'statusbar' => true,
                        'elementpath' => true, // https://www.tinymce.com/docs/configure/editor-appearance/#elementpath
                        'relative_urls' => true,
                        'remove_script_host' => true,
                        'convert_urls' => false, // Prevent site-root images being rewritten to base relative
                        'menubar' => false,
                        'language' => 'en',
                        'branding' => false,
                        'upload_folder_id' => null, // Set folder ID for insert media dialog                        
                        'valid_elements' => "@[id|class|style|title],a[id|rel|rev|dir|tabindex|accesskey|type|name|href|target|title"
                            . "|class],-strong/-b[class],-em/-i[class],-strike[class],-u[class],#p[id|dir|class|align|style]"
                            . ",-ol[class],"
                            . "-ul[class],"
                            . "-li[class],br,img[id|dir|longdesc|usemap|class|src|border|alt=|title|width|height|align|data*],"
                            . "-sub[class],-sup[class],-blockquote[dir|class],"
                            . "-table[cellspacing|cellpadding|width|height|class|align|dir|id|style],"
                            . "-tr[id|dir|class|rowspan|width|height|align|valign|bgcolor|background|bordercolor|style],"
                            . "tbody[id|class|style],thead[id|class|style],tfoot[id|class|style],"
                            . "#td[id|dir|class|colspan|rowspan|width|height|align|valign|scope|style|headers],"
                            . "-th[id|dir|class|colspan|rowspan|width|height|align|valign|scope|style|headers],caption[id|dir|class],"
                            . "-div[id|dir|class|align|style],-span[class|align|style],-pre[class|align],address[class|align],"
                            . "-h1[id|dir|class|align|style],-h2[id|dir|class|align|style],-h3[id|dir|class|align|style],"
                            . "-h4[id|dir|class|align|style],-h5[id|dir|class|align|style],-h6[id|dir|class|align|style],hr[class],"
                            . "dd[id|class|title|dir],dl[id|class|title|dir],dt[id|class|title|dir],@[id,style,class]",
                        'extended_valid_elements' =>
                            'img[class|src|alt|title|hspace|vspace|width|height|align|name|usemap|data*],'
                            . 'object[classid|codebase|width|height|data|type],'
                            . 'embed[width|height|name|flashvars|src|bgcolor|align|play|loop|quality|'
                            . 'allowscriptaccess|type|pluginspage|autoplay],'
                            . 'param[name|value],'
                            . 'map[class|name|id],'
                            . 'area[shape|coords|href|target|alt],'
                            . 'ins[cite|datetime],del[cite|datetime],'
                            . 'menu[label|type],'
                            . 'meter[form|high|low|max|min|optimum|value],'
                            . 'cite,abbr,,b,article,aside,code,col,colgroup,details[open],dfn,figure,figcaption,'
                            . 'footer,header,kbd,mark,,nav,pre,q[cite],small,summary,time[datetime],var,ol[start|type]',
                    ]
                );

                if (! empty($editorConfigSettings['options'])) {
                    $editor->setOptions($editorConfigSettings['options']);
                }

                // block formats
                if (! empty($editorConfigSettings['block_formats'])) {
                    $blockFormats = $this->stringToArray($editorConfigSettings['block_formats']);
                    $blocks = [];
                    foreach ($editorConfigSettings['block_formats'] as $tag => $name) {
                        $blocks[] = $name . '=' . $tag;
                    }
                    $formats = implode(';', $blocks);
                    $valids = implode(';', $blocks);
                    $editor->setOptions(
                        [
                            'block_formats' => $formats,
                            'theme_advanced_blockformats' => $formats,
                            // 'valid_elements' => $formats,
                        ]
                    );
                }
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
