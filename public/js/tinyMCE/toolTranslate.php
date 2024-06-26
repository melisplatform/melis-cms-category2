<?php
return [
    // 'mode' => 'textareas',
    'relative_urls' => false,
    'branding' => false,
    'selector' => 'tool-editable-selector',
    'language' => 'en',
    'inline' => false,
    'menubar' => false,
    'forced_root_block' => 'p',
    'image_uploadtab' => false,
    'cleanup' => false,
    'verify_html' => false,
    'paste_auto_cleanup_on_paste' => true,
    'plugins' => [
        'lists', 'advlist', 'autolink', 'link', 'image', 'charmap', 'preview', 'anchor',
        'searchreplace', 'visualblocks', 'code', 'fullscreen', 'insertdatetime', 'media', 'table'
    ],
    //styleselect = styles
    'toolbar' => 'undo redo | styles | bold italic | link image |  alignleft aligncenter alignright alignjustify',
    'toolbar_mode' => 'sliding',
    'deprecation_warnings' => false,
    'promotion' => false,
    'setup' => 'melisTinyMCE.tinyMceActionEvent',
    'init_instance_callback' => 'tinyMceCleaner'
];