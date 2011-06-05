<?php

global $CFG;

// Disable the HTML area code from being included in print_textarea.
$CFG->editorsrc = array("{$CFG->httpswwwroot}/lib/editor/tinymce/jscripts/tiny_mce/tiny_mce.js",
                        "{$CFG->httpswwwroot}/lib/editor/tinymce/tinymcejs.php");