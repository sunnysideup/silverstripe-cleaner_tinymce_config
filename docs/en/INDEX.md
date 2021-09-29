There are three ways to use this module:

### 1. set main editor:

```yml
Sunnysideup\CleanerTinyMCEConfig\Config\HTMLEditorConfigOptions:
  main_editor: basic
```

This will make all your TinyMCE editors in your project be `basic`.

### 2. set config for individual editors

If you need different editors for different fields, then you can use configs on a case by case basis. 


```php

use SilverStripe\Forms\HTMLEditor\HTMLEditorField;


    function getCMSFields() {
        $fields = parent::getCMSFields();
        $fields->addFieldsToTab(
            'Root.Main',
            [
                HTMLEditorField::create('Content')
                    ->setEditorConfig('cms'),                
                HTMLEditorField::create('InlineContent')
                    ->setEditorConfig('inline'),                
                HTMLEditorField::create('ParagraphsContent')
                    ->setEditorConfig('paragraphs'),
                HTMLEditorField::create('HeadingsContent')
                    ->setEditorConfig('heading'),
                HTMLEditorField::create('BasicContent')
                    ->setEditorConfig('basic'),
                HTMLEditorField::create('BasicContentWithButon')
                    ->setEditorConfig('basicbutton')
            ]
        );
        return $fields;
    }

```


### 3. add your own recipe (and use as above)

What is super useful is that you can take one of the recipes above and then use 
`based_on` to just remove / add a few buttons / etc....

```yml
Sunnysideup\CleanerTinyMCEConfig\Config\HTMLEditorConfigOptions:
  editor_configs:
    custom:
        based_on: basic
        enabled_plugins:
            - A
            - B
            - C
        disabled_plugins:
          - A
          - B
          - C

        add_buttons:
          1:
              - A
              - B
              - C
          2:
            - A
            - B
            - C
          3:
            - A
            - B
            - C
        lines:
          1:
              - A
              - B
              - C
          2:
            - A
            - B
            - C
          3:
            - A
            - B
            - C
      remove_buttons:
          - A
          - B
          - C
      add_macrons: true
      options:
          skin: silverstripe'
          width: 80ch
      block_formats:
          p: 'paragraph'
```

# more notes scrambled from other places - just FYI.
##
title: Custom embeds in the WYSIWYG editor
summary: How to allow custom embeds in the TinyMCE HTML editor.

# Custom embeds in the WYSIWYG editor

This how-to guides developers through the steps necessary to disable default TinyMCE handling
of the `<embed>` and `<object>` tags.

<div class="alert alert-info" markdown='1'>
Proceeding with this guide will disable the ability of the CMS to embed `.swf` files through the "Insert Media"
interface button. You will need to provide your own
[custom TinyMCE plugins](https://docs.silverstripe.org/en/3.2/developer_guides/forms/field_types/htmleditorfield) or
embed the files directly via HTML code.
</div>

## Disable the media plugin

CMS uses the TinyMCE media plugin to embed `.swf` files. The side effect of this is that `<embed>` and `<object>` tags
are managed by the plugin which sometimes prevents custom markup to be inserted, or rewrites it with custom names
and attributes.

In your module's `_config.php` (the module's name has to be alphabetically after `cwp` for your `_config.php` statements
not to be overriden, see the notice in the [Rich Text Editing
docs](https://docs.silverstripe.org/en/4/developer_guides/forms/field_types/htmleditorfield/)) disable the media plugin for the
default editor installed by the `cwp` module:

```php
use SilverStripe\Forms\HTMLEditor\HTMLEditorConfig;

HTMLEditorConfig::get('cwp')->disablePlugins('media');
```

Note the editor configuration we want to amend is "cwp" here, different from the default SilverStripe configuration
called "cms".

You should now be able to embed any `<embed>` or `<object>` using the HTML button in the TinyMCE editor.

## Modifying the whitelist

If you find out some attributes are still being removed by the editor, your can update the whitelist of elements.
Copy the `extended_valid_elements` option from `cwp/_config.php`, and amend it in your own `_config.php` to suit.

```php
use SilverStripe\Forms\HTMLEditor\HTMLEditorConfig;

HTMLEditorConfig::get('cwp')->setOption('extended_valid_elements', '<your modified whitelist goes here>');```
