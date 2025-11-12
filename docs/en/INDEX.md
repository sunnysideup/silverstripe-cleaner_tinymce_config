There are three ways to use this module:

### 1. set main editor

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
    customconfig:
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
        skin: 'silverstripe'
        width: 80ch
      blocks:
        p: 'paragraph'
        
```

You may also consider using the `onBeforeWrite` method on DataObjects to clear more tags for, for example, inline content.


Also consider:

```yml

---
Name: tiny_mce_editor_uset
---

#first reset the array
SilverStripe\Forms\HTMLEditor\TinyMCEConfig:
  editor_css: null
---
Name: tiny_mce_editor_set
---
#then set our desired files
SilverStripe\Forms\HTMLEditor\TinyMCEConfig:
  editor_css:
    - 'themes/base/dist/editor.css'
    - 'https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700'
```
