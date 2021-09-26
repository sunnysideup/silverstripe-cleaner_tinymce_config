<?php

namespace Sunnysideup\CleanerTinyMCEConfig\Config;

use SilverStripe\Forms\HTMLEditor\TinyMCEConfig;
/**
 *
 */

use SilverStripe\Core\Injector\Injectable;
use SilverStripe\Core\Config\Configurable;

class HTMLEditorConfigOptions
{
    use Injectable;

    use Configurable;


    /**
     *
     * @var string
     */
    private static $main_editor = 'inline';

    /**
     *
     * @var array
     */
    private static $remove_options = [
        // 'cms',
        // 'intro',
        // 'heading',
        // 'simple',
        // 'supersimple'
    ];

    public function getEditors() : array
    {
        $list = $this->config()->get('editor_configs');
        foreach($list as $key => $entry) {
            if(!empty($entry['based_on']) && !empty($list[$entry['based_on']])) {
                $entry = array_merge_recursive($list[$entry['based_on']], $entry);
            }
            $list[$key] = $entry;
        }
        return $list;
    }

    /**
     * example:
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
     *             'block_formats' => [
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
     * @var array
     */
    private static $editor_configs = [

        'cms' => [
            'disabled_plugins' => [
                // 'ssembed',
                // 'table',
            ],
            'remove_buttons' => [
                'alignjustify',
                'indent',
                'outdent',
            ],
            'block_formats' => [
                'p' => 'Paragraph',
                'h1' => 'Heading 1',
                'h2' => 'Heading 2',
                'h3' => 'Heading 3',
                'h4' => 'Heading 4',
                'h5' => 'Heading 5',
                'h6' => 'Heading 6',
                'blockquote' => 'quote',
            ],
        ],

        'inline' => [
            'disabled_plugins' => [
                'table',
            ],
            'block_formats' => [
                'span' => 'span',
            ],
            'charmap_append' => true,
            'lines' => [
                1 => [
                    'formatselect',
                    'styleselect',
                    'removeformat',
                    '|',
                    'bold',
                    'italic',
                    'underline',
                    '|',
                    'sslink',
                    'unlink',
                    'anchor',
                    '|',
                    '|',
                    'paste',
                    'pastetext',
                    '|',
                    'charmap',
                    'superscript',
                    '|',
                    'fullscreen',
                    'code',

                ],
            ]
        ],

        'paragraphs' => [
            'based_on' => 'inline',
            'block_formats' => [
                'p' => 'paragraph',
            ],

        ],

        'heading' => [
            'based_on' => 'inline',
            'block_formats' => [
                'h2' => 'heading',
            ],
        ],

        'basic' => [
            'based_on' => 'inline',
            'block_formats' => [
                'h1' => 'heading 1',
                'h2' => 'heading 2',
                'h3' => 'heading 3',
                'p' => 'paragraph',
                'blockquote' => 'quote',
            ],
            'lines' => [

                2 => [
                    'bullist',
                    'numlist',
                    '|',
                    'ssmedia',
                    'ssembed',
                ],
            ]

        ],
        'basicbutton' => [
            'based_on' => 'basic',
            'options' => [
                'style_formats' => [
                    'title' => 'Button-Link',
                    'attributes' => ['class' => 'tiny-mce-button'],
                    'selector' => 'a'
                ],
            ],
        ]


    ];


}
