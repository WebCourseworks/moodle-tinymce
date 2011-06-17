<?php

require_once('../../../config.php');

global $CFG; 

/* Uncomment after dev, before qa.
$lastmodified = filemtime(__FILE__);
$lifetime = 1800;

header("Content-type: application/x-javascript; charset: utf-8");  // Correct MIME type
header("Last-Modified: " . gmdate("D, d M Y H:i:s", $lastmodified) . " GMT");
header("Expires: " . gmdate("D, d M Y H:i:s", time() + $lifetime) . " GMT");
header("Cache-control: max_age = $lifetime");
header("Pragma: ");
*/

define('MENU_SEPARATOR', '|');

$courseid = optional_param('id', SITEID, PARAM_INT);

$theme_advanced_buttons = array(1 => array('bold', 'italic', 'underline', 'strikethrough', MENU_SEPARATOR, 'justifyleft', 'justifycenter', 'justifyright', 'justifyfull', MENU_SEPARATOR, 'formatselect', 'fontselect', 'fontsizeselect', MENU_SEPARATOR, 'undo', 'redo'),
                                2 => array('cut', 'copy', 'paste', 'pastetext', 'pasteword', MENU_SEPARATOR, 'search', 'replace', MENU_SEPARATOR, 'bullist', 'numlist', MENU_SEPARATOR, 'outdent', 'indent', 'blockquote', MENU_SEPARATOR, 'link', 'unlink', 'anchor', 'image', 'cleanup', 'help', 'code', MENU_SEPARATOR, 'insertdate', 'inserttime', 'preview', MENU_SEPARATOR, 'forecolor', 'backcolor'),
                                3 => array('tablecontrols', MENU_SEPARATOR, 'hr', 'removeformat', 'visualaid', MENU_SEPARATOR, 'sub', 'sup', MENU_SEPARATOR, 'charmap', 'moodlesmileys', 'media', MENU_SEPARATOR, 'ltr', 'rtl', MENU_SEPARATOR, 'fullscreen'));

$tinymceplugins = array('safari', 
                        'pagebreak', 
                        'layer', 
                        'table', 
                        'moodlesmileys', 
                        'insertdatetime', 
                        'preview', 
                        'media', 
                        'searchreplace', 
                        'print', 
                        'contextmenu', 
                        'paste', 
                        'directionality', 
                        'fullscreen', 
                        'noneditable', 
                        'visualchars', 
                        'nonbreaking', 
                        'xhtmlxtras', 
                        'template', 
                        'inlinepopups');

// setup coversion table for swapping HTMLarea controls to TinyMCE controls
$tinymcebuttonmap = array('fontname'             => 'fontselect', 
                          'fontsize'             => 'fontsizeselect', 
                          'formatblock'          => 'blockquote', 
                          'subscript'            => 'sub', 
                          'superscript'          => 'sup', 
                          'clean'                => 'cleanup', 
                          'lefttoright'          => 'ltr', 
                          'righttoleft'          => 'rtl', 
                          'insertorderedlist'    => 'numlist', 
                          'insertunorderedlist'  => 'bullist', 
//                          'hilitecolor'         => '', 
                          'inserthorizontalrule' => 'hr', 
                          'createanchor'         => 'anchor', 
                          'createlink'           => 'link', 
                          'insertimage'          => 'image', 
                          'inserttable'          => 'tablecontrols', 
                          'insertsmile'          => 'moodlesmileys', 
                          'insertchar'           => 'charmap', 
                          'spellcheck'           => 'spellchecker', 
                          'htmlmode'             => 'code', 
                          'popupeditor'          => 'fullscreen', 
                          'search_replace'       => 'search,replace');

//setup array of buttons to hide and convert them to TinyMCE if needed.
$hidebuttons =  array();
if(!empty($CFG->editorhidebuttons)) {
    foreach (explode(' ', $CFG->editorhidebuttons) as $index => $button) {
        $hidebuttons[] = array_key_exists($button, $tinymcebuttonmap) ? $tinymcebuttonmap[$button] : $button;
    }
}

// remove blocked controls
if(count($hidebuttons) > 0) {
    foreach($theme_advanced_buttons as $themeindex => $array) {
        $new_array = array();

        //prevent first menu item from being separator also prevent to separators in a row
        $last = MENU_SEPARATOR;
        foreach($array as $index => $icon) {
            if(!in_array($icon, $hidebuttons) && $last != $icon) {
                $new_array[] = $icon;
                $last = $icon;
            }
        }
        // if the last entry is a spacer remove it
        if($last == MENU_SEPARATOR) {
            array_pop($new_array);
        }

        $theme_advanced_buttons[$themeindex] = implode(',', $new_array);
    }
} else {
    foreach($theme_advanced_buttons as $themeindex => $array) {
        $theme_advanced_buttons[$themeindex] = implode(',', $array);
    }
}

// this doesn't seem to work
$editorbackgroundcolor = '#FFFFFF';
if(!empty($CFG->editorbackgroundcolor)) {
    $editorbackgroundcolor = $CFG->editorbackgroundcolor;
}

?>

HTMLArea = function() {
    var id;
    var cfg;

    if (arguments.length == 0) {
        return;
    }

    var id = arguments[0];

    // Default config (a replica of the HTMLArea config object).
    var config = {
        pageStyle : 'body { background-color: #ffffff; }', 
        killWordOnPaste : true, 
        fontname : {
            // This is the default font list from HtmlArea.Config.
            'Arial'           : 'arial,helvetica,sans-serif',
            'Courier New'     : 'courier new,courier,monospace',
            'Georgia'         : 'georgia,times new roman,times,serif',
            'Tahoma'          : 'tahoma,arial,helvetica,sans-serif',
            'Times New Roman' : 'times new roman,times,serif',
            'Verdana'         : 'verdana,arial,helvetica,sans-serif',
            'Impact'          : 'impact',
            'WingDings'       : 'wingdings'
        }, 
        fontsize : {
            // This is the default font size list from HtmlArea.Config.
            '1 (8 pt)'  : '8pt', 
            '2 (10 pt)' : '10pt', 
            '3 (12 pt)' : '12pt', 
            '4 (14 pt)' : '14pt', 
            '5 (18 pt)' : '18pt', 
            '6 (24 pt)' : '24pt', 
            '7 (36 pt)' : '36pt'
        }
    };

    // A really really basic merge op to merge one object into another.
    var mergeObjects = function(dest, source) {
        for (key in source) {
            dest[key] = source[key];
        }

        return dest;
    };

    // Merge in supplied config if provided with one through the constructor.
    // Otherwise the config will be set by manipulating this.config.
    if (arguments.length > 1) {
        config = mergeObjects(config, arguments[1]);
    }

    // Compile a list of items into a string that may be passed to theme_avanced_fonts or theme_advanced_font_sizes.
    var compileFontList = function(list) {
        // http://tinymce.moxiecode.com/wiki.php/Configuration:theme_advanced_fonts
        // http://tinymce.moxiecode.com/wiki.php/Configuration:theme_advanced_font_sizes
        var results = [];

        for(item in list) {
            results.push(item + '=' + list[item]);
        }

        return results.join(';');
    };

    // Initialize an instance of TinyMCE given a config.
    var tinymceinit = function(editorConfig) {
        tinyMCE.init(editorConfig);
    };

    // Construct a configuration object for TinyMCE.
    var buildTinymceConfig = function() {
        var editorConfig = {
            // Plugins
            plugins : "<?php echo implode(',', $tinymceplugins); ?>", 
            // General config
            convert_urls : false, 
            add_form_submit_trigger : true, 
            add_unload_trigger : true, 
            // Browser
            file_browser_callback : 'moodleFileBrowser',
            // Theme
            theme : "advanced", 
            theme_advanced_buttons1 : "<?php echo $theme_advanced_buttons[1] ?>", 
            theme_advanced_buttons2 : "<?php echo $theme_advanced_buttons[2] ?>", 
            theme_advanced_buttons3 : "<?php echo $theme_advanced_buttons[3] ?>", 
            theme_advanced_toolbar_location : "top", 
            theme_advanced_toolbar_align : "left", 
            theme_advanced_statusbar_location : "bottom", 
            theme_advanced_resizing : true,
            theme_advanced_fonts : compileFontList(config.fontname),
            theme_advanced_font_sizes : compileFontList(config.fontsize)
        };

        // Merge with an object that may have been passed as an argument.
        // This should be used to set mode and elements.
        if (arguments.length > 0) {
            editorConfig = mergeObjects(editorConfig, arguments[0]);
        }

        if (config.killWordOnPaste) {
            editorConfig = mergeObjects(editorConfig, {
                // Be extremely aggressive when stripping out the word formatting.
                paste_create_paragraphs : true, 
                paste_auto_cleanup_on_paste : true, 
                paste_convert_middot_lists : true, 
                paste_convert_headers_to_strong : true, 
                paste_strip_class_attributes : 'all', 
                paste_retain_style_properties : 'none', 
                paste_postprocess : function(pl, o) { 
                    tinymce.each(tinyMCE.activeEditor.dom.select('b', o.node), function(node) { 
                        tinyMCE.activeEditor.dom.rename(node, 'strong'); 
                    }); 
                }
            });
        }

        return editorConfig;
    };

    var generate = function() {
        var editorConfig = buildTinymceConfig({ 
            mode     : 'exact', 
            elements : id 
        });

        tinymceinit(editorConfig);
    };

    var replaceAll = function() {
        var editorConfig = buildTinymceConfig({ 
            mode : 'textareas'
        });

        tinymceinit(editorConfig);
    };

    return {
        generate   : function() { return generate(); },
        replaceAll : function() { return replaceAll(); },
        config     : config
    }
};

function moodleFileBrowser (field_name, url, type, win) {
    var cmsURL = '', 
        width = 0, 
        height = 0;

    switch(type) {
        default:
        case 'file':
            cmsURL = '<?php echo "{$CFG->httpswwwroot}/lib/editor/tinymce/link.php?id={$courseid}"; ?>';
            width = 480;
            height = 400;
            break;
        case 'image':
            cmsURL = '<?php echo "{$CFG->httpswwwroot}/lib/editor/tinymce/insert_image.php?id={$courseid}"; ?>';
            width = 736;
            height = 430;
            break;
        case 'media':
            cmsURL = '<?php echo "{$CFG->httpswwwroot}/lib/editor/tinymce/insert_image.php?id={$courseid}"; ?>';
            width = 736;
            height = 430;
            break;
    }

    tinyMCE.activeEditor.windowManager.open({
        file           : cmsURL,
        width          : width,  
        height         : height,
        resizable      : 'yes',
        inline         : 'yes',  // This parameter only has an effect if you use the inlinepopups plugin!
        close_previous : 'no'
    }, {
        window         : win,
        input          : field_name
    });

    return false;
}