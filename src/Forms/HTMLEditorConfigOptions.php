<?php

namespace Sunnysideup\CleanerTinyMCEConfig\Forms;

use SilverStripe\Forms\HTMLEditor\TinyMCEConfig;
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

class HTMLEditorFieldShortConfig
{

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



        'intro' => [
            'disabled_plugins' => [
                'ssembed',
                'ssmedia',
                'table',
            ],
            'remove_buttons' => [
                'alignjustify',
                'indent',
                'outdent',
                'bullist',
                'numlist',
            ],
            'block_formats' => [
                'p' => 'Paragraph',
                'h1' => 'Heading 1',
            ]
        ],



        'heading' => [
            'disabled_plugins' => [
                'ssembed',
                'ssmedia',
                'table',
            ],
            'remove_buttons' => [
                'alignleft',
                'aligncenter',
                'alignright',
                'alignjustify',
                'indent',
                'outdent',
                'bullist',
                'numlist',
            ],
            'block_formats' => [
                'h2' => 'Heading 2',
            ]
        ],



        'simple' => [
            'disabled_plugins' => [
                'ssembed',
                'ssmedia',
                'table',
            ],
            'remove_buttons' => [
                'alignleft',
                'aligncenter',
                'alignright',
                'alignjustify',
                'indent',
                'outdent',
                'bullist',
                'numlist',
                'formatselect',
            ],
            'block_formats' => [
                'p' => 'Paragraph',
            ]
        ],


        'supersimple' => [
            'disabled_plugins' => [
                'ssembed',
                'ssmedia',
                'table',
            ],
            'remove_buttons' => [
                'alignleft',
                'aligncenter',
                'alignright',
                'alignjustify',
                'indent',
                'outdent',
                'bullist',
                'numlist',
                'formatselect',
            ],
            'block_formats' => [
                'span' => 'span',
            ]
        ],
    ];


}