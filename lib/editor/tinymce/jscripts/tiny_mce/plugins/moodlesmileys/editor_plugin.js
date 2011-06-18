/**
 * editor_plugin_src.js
 *
 * Copyright 2009, Moxiecode Systems AB
 * Released under LGPL License.
 *
 * License: http://tinymce.moxiecode.com/license
 * Contributing: http://tinymce.moxiecode.com/contributing
 */

(function(tinymce) {
	tinymce.create('tinymce.plugins.MoodleSmileysPlugin', {
		init : function(ed, url) {
			// Register commands
			ed.addCommand('mceMoodleSmileys', function() {
				ed.windowManager.open({
					file : url + '/emotions.php',
					inline : 1
				}, {
					plugin_url : url
				});
			});

			// Register buttons
			ed.addButton('moodlesmileys', {title : 'Smileys', cmd : 'mceMoodleSmileys'});
            ed.onBeforeRenderUI.add(function() { 
                tinymce.DOM.loadCSS(url + '/css/moodlesmileys.css');
            });                
		},

		getInfo : function() {
			return {
				longname : 'MoodleSmileys',
				version : '1'
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('moodlesmileys', tinymce.plugins.MoodleSmileysPlugin);
})(tinymce);