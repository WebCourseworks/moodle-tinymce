<?php

define('MENU_SEPARATOR', '|');

$courseid = empty($COURSE->id) ? 1 : $COURSE->id;

$theme_advanced_buttons = array();
$theme_advanced_buttons['theme_advanced_buttons1'] = array('bold','italic','underline','strikethrough',MENU_SEPARATOR,'justifyleft','justifycenter','justifyright','justifyfull',MENU_SEPARATOR,'formatselect','fontselect','fontsizeselect',MENU_SEPARATOR,'undo','redo');
$theme_advanced_buttons['theme_advanced_buttons2'] = array('cut','copy','paste','pastetext','pasteword',MENU_SEPARATOR,'search','replace',MENU_SEPARATOR,'bullist','numlist',MENU_SEPARATOR,'outdent','indent','blockquote',MENU_SEPARATOR,'link','unlink','anchor','image','cleanup','help','code',MENU_SEPARATOR,'insertdate','inserttime','preview',MENU_SEPARATOR,'forecolor','backcolor');
$theme_advanced_buttons['theme_advanced_buttons3'] = array('tablecontrols',MENU_SEPARATOR,'hr','removeformat','visualaid',MENU_SEPARATOR,'sub','sup',MENU_SEPARATOR,'charmap','moodlesmileys','media',MENU_SEPARATOR,'ltr','rtl',MENU_SEPARATOR,'fullscreen');

// setup coversion table for swapping HTMLarea controls to TinyMCE controls
$tinymce_conversion = array(
                            'fontname' => 'fontselect',
                            'fontsize' => 'fontsizeselect',
                            'formatblock' => 'blockquote',
                            'subscript' => 'sub',
                            'superscript' => 'sup',
                            'clean' => 'cleanup',
                            'lefttoright' => 'ltr',
                            'righttoleft' => 'rtl',
                            'insertorderedlist' => 'numlist',
                            'insertunorderedlist' => 'bullist',
                            //'hilitecolor' => '',
                            'inserthorizontalrule' => 'hr',
                            'createanchor' => 'anchor',
                            'createlink' => 'link',
                            'insertimage' => 'image',
                            'inserttable' => 'tablecontrols',
                            'insertsmile' => 'moodlesmileys',
                            'insertchar' => 'charmap',
                            'spellcheck' => 'spellchecker',
                            'htmlmode' => 'code',
                            'popupeditor' => 'fullscreen',
                            'search_replace' => 'search,replace'
                           );
//setup array of buttons to hide and convert them to TinyMCE if needed.
$hidebuttons =  array();
if(!empty($CFG->editorhidebuttons)) {
    foreach (explode(' ', $CFG->editorhidebuttons) as $index => $button) {
        $hidebuttons[] = array_key_exists($button, $tinymce_conversion) ? $tinymce_conversion[$button] : $button;
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

// load configured fonts if not set it will default to TinyMCE defaults.
$themefonts = '';
if(!empty($CFG->editorfontlist)) {
    $themefonts = str_replace(':', '=', $CFG->editorfontlist);
}

?>

HTMLArea = function(id, config) {
    var textareaId = id;
};

HTMLArea.prototype.replaceAll = function(config) {
	tinyMCE.init({
		// General options
		mode : "textareas",
		theme : "advanced",
		plugins : "safari,pagebreak,layer,table,moodlesmileys,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups",
        convert_urls : false,
		file_browser_callback : 'moodleFileBrowser',		
		// Theme options
		theme_advanced_buttons1 : "<?php echo $theme_advanced_buttons['theme_advanced_buttons1'] ?>",
		theme_advanced_buttons2 : "<?php echo $theme_advanced_buttons['theme_advanced_buttons2'] ?>",
		theme_advanced_buttons3 : "<?php echo $theme_advanced_buttons['theme_advanced_buttons3'] ?>",

        theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true
	});
};

HTMLArea.prototype.generate = function() {
	tinyMCE.init({
		// General options
        mode : "exact",
        elements : this.textareaId,
		theme : "advanced",
		plugins : "safari,pagebreak,layer,table,moodlesmileys,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups",
        convert_urls : false,
		file_browser_callback : 'moodleFileBrowser',		
		// Theme options
		theme_advanced_buttons1 : "<?php echo $theme_advanced_buttons['theme_advanced_buttons1'] ?>",
		theme_advanced_buttons2 : "<?php echo $theme_advanced_buttons['theme_advanced_buttons2'] ?>",
		theme_advanced_buttons3 : "<?php echo $theme_advanced_buttons['theme_advanced_buttons3'] ?>",

        theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true
	});

};
    
function moodleFileBrowser (field_name, url, type, win) {

    if(type == 'file'){
		var cmsURL =  '<?php echo $CFG->httpswwwroot; ?>/lib/editor//link.php';
		var width = 480;
		var height = 400;
	}
    if(type == 'image'){
		var cmsURL =  '<?php echo $CFG->httpswwwroot; ?>/lib/editor//insert_image.php';
		var width = 736;
		var height = 430;
	}
    if(type == 'media'){
		var cmsURL =  '<?php echo $CFG->httpswwwroot; ?>/lib/editor//insert_image.php';
		var width = 736;
		var height = 430;
	}
    var courseid = 	<?php  echo $courseid; ?>;

    tinyMCE.activeEditor.windowManager.open({
        file : cmsURL + "?id=" + courseid,
        width : width,  
        height : height,
        resizable : "yes",
        inline : "yes",  // This parameter only has an effect if you use the inlinepopups plugin!
        close_previous : "no"
    }, {
        window : win,
        input : field_name
    });

    return false;
  }
