<?php

global $CFG;

// Disable the HTML area code from being included in print_textarea.
unset($CFG->editorsrc);

/**
 * Output the necessary TinyMCE JavaScript for javascript.php.
 */
function tinymce_output_page_javascript() {
	global $CFG;

	echo "<script type=\"text/javascript\" src=\"{$CFG->httpswwwroot}/lib/editor/tinymce/jscripts/tiny_mce/tiny_mce.js\"></script>";
	echo "<script type=\"text/javascript\" src=\"{$CFG->httpswwwroot}/lib/editor/tinymce/tinymcejs.php\"></script>";
}