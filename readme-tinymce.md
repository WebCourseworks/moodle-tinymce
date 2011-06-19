Overview
========

This is a drop in TinyMCE replacement for Moodle's HTMLArea WYSIWYG HTML editor developed by [Web Courseworks, Ltd.](http://www.webcourseworks.com)  This code has been tested with the stable version of Moodle 1.9.12 but should work on any installation of 1.9.

Installation
============

This modification requires *Moodle 1.9.12+* and *PHP 5.1+*.

Copy the following files and directories into your Moodle installation:

* /lib/tinymcelib.php
* /lib/editor/tinymce/, overwrite the existing directory.

Add the following two lines of code to /lib/setup.php before the ?> at the end of the file:

```
// THIS MUST BE INCLUDED FOR TINYMCE TO WORK CORRECTLY.
require_once("{$CFG->libdir}/tinymcelib.php");
```