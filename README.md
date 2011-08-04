Overview
========

This is a drop in TinyMCE replacement for Moodle's HTMLArea WYSIWYG HTML editor developed by [Web Courseworks, Ltd.](http://www.webcourseworks.com).  This code has been tested with the stable version of Moodle 1.9.12 but should work on any installation of 1.9.  The TinyMCE editor will not appear in the Chrome or Safari web browsers unless your Moodle site is running 1.9.11 or later, or you've [patched your installation](https://github.com/moodle/moodle/commit/c476a2ec).

Installation
============

This modification requires *PHP 5.1* or later and works best on *Moodle 1.9.12* or later.  If you do not have *PHP 5.1* or later your site will stop functioning after modifying /lib/setup.php.

Copy the following files and directories into your Moodle installation:

* /lib/tinymcelib.php
* /lib/editor/tinymce/, replace the existing directory.

Add the following two lines of code to /lib/setup.php before the ?> at the end of the file:

```
// THIS MUST BE INCLUDED FOR TINYMCE TO WORK CORRECTLY.
require_once("{$CFG->libdir}/tinymcelib.php");
```

Dragmath Support
================

Dragmath is supported by TinyMCE.  To install download the dragmath plugin and support files provided by  Mauno Korpelainen [http://korpelainen.net/dragmxx.zip](http://korpelainen.net/dragmxx.zip)].  Copy the included /lib/dragmath and /lib/editor/tinymce/jscripts/tiny_mce/plugins/dragmath directories into your installation.  There is no need to modify /lib/editor/tinymce/tinymcejs.php any longer.  The plugin will be automatically loaded into TinyMCE if it has been installed.

Known Issues
============

TinyMCE is incompatible with the following Moodle customizations:

* NanoGong
* [HTMLAREA Editor custom plugin framework support](http://tracker.moodle.org/browse/CONTRIB-2730)