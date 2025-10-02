<?php

namespace Sunnysideup\CleanerTinyMCEConfig\Config;

use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;

class HTMLEditorConfigOptions
{
    use Injectable;

    use Configurable;

    /**
     * @var string
     */
    private static $main_editor = 'cms';

    /**
     * @var array
     */
    private static $remove_options = [
        // 'cms',
        // 'inline',
        // 'paragraphs',
        // 'heading',
        // 'basic',
        // 'basicbutton',
    ];

    /**
     * example:.
     *
     * ```php
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
     *             'options' => [
     *                 'skin' => 'silverstripe',
     *                 'width' => '80ch',
     *             ],
     *             'blocks' => [
     *                 'p' => 'paragraph',
     *                 'p' => 'paragraph',
     *             ]
     *         ],
     *         'config2' => [
     *             'based_on' => 'config1'
     *         ]
     *
     *     ]
     * ```
     *
     * @var array
     */
    private static $editor_configs = [
        'cms' => [
            'remove_buttons' => [
                'alignjustify',
                'indent',
                'outdent',
            ],
            'add_buttons' => [
                2 => ['fullscreen'],
            ],
        ],

        'inline' => [
            'disabled_plugins' => [
                'table',
            ],
            // 'blocks' => [
            //     'inline html' => 'span',
            // ],
            'charmap_append' => true,
            'lines' => [
                1 => [
                    'bold',
                    'italic',
                    'underline',
                    '|',
                    'blocks',
                    'styles',
                    'removeformat',
                ],
                2 => [
                    'sslink',
                    'unlink',
                    'pastetext',
                    'charmap',
                    'code',
                    'fullscreen',
                ],
            ],
        ],

        'paragraphs' => [
            'based_on' => 'inline',
            'blocks' => [
                'p' => 'paragraph',
            ],
        ],

        'heading' => [
            'based_on' => 'inline',
            'blocks' => [
                'h2' => 'heading',
            ],
        ],

        'basic' => [
            'charmap_append' => true,
            'blocks' => [
                'h2' => 'heading 2',
                'h3' => 'heading 3',
                'p' => 'paragraph',
                'blockquote' => 'quote',
            ],
            'lines' => [
                1 => [
                    'paste',
                    'pastetext',
                    '|',
                    'ssmedia',
                    'ssembed',
                    '|',
                    'sslink',
                    'unlink',
                    'anchor',
                    '|',
                    'charmap',
                    '|',
                    'code',
                    'fullscreen',
                ],
                2 => [
                    'removeformat',
                    'blocks',
                    'styles',
                    '|',
                    'bold',
                    'italic',
                    'underline',
                    '|',
                    'bullist',
                    'numlist',
                ],
            ],
        ],
        'basicbutton' => [
            'based_on' => 'basic',
            'options' => [
                'style_formats' => [
                    'title' => 'Button-Link',
                    'attributes' => ['class' => 'tiny-mce-button'],
                    'selector' => 'a',
                ],
            ],
        ],
    ];

    public function getEditors(): array
    {
        $list = $this->config()->get('editor_configs');
        foreach ($list as $key => $entry) {
            if (! empty($entry['based_on']) && ! empty($list[$entry['based_on']])) {
                $entry = array_merge_recursive($list[$entry['based_on']], $entry);
            }

            $list[$key] = $entry;
        }

        return $list;
    }
}
