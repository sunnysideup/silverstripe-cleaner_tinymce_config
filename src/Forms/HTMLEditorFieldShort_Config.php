<?php


class HTMLEditorFieldShort_Config implements HTMLEditorFieldShort_ConfigInterface
{
    public function getNumberOfRows() : int
    {
        return 7;
    }

    public function setConfig($name = 'cms')
    {
        HtmlEditorConfig::get('cms')
        ->setOption(
            'valid_styles',
            array('*' => 'color,font-weight,font-style,text-decoration')
        )->setOption(
            'paste_as_text',
            true
        )->setOption(
            'paste_text_sticky',
            true
        )->setOption(
            'paste_text_sticky_default',
            true
        )->setOption(
            'theme_advanced_blockformats',
            'Paragraph=p;Header 1=h1;Header 2=h2;Header 3=h3;quote=blockquote'
        )->setButtonsForLine(
            3,
            []
        )->removeButtons(
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
        );


        HtmlEditorConfig::get($name)->setOption(
            'width',
            '702px'
        );
    }

}
