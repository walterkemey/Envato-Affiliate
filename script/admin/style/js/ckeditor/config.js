/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For the complete reference:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config

	// The toolbar groups arrangement, optimized for two toolbar rows.
	config.toolbarGroups = [
		{ name: 'basicstyles', groups: [ 'basicstyles' ] },
		{ name: 'styles', groups : [ 'Styles','Format' ] },
		{ name: 'paragraph',   groups: [ 'list', 'align' ] },
		{ name: 'links' },
		{ name: 'insert' },
		{ name: 'document',	   groups: [ 'mode' ] },
	];

	// Remove some buttons, provided by the standard plugins, which we don't
	// need to have in the Standard(s) toolbar.
	config.removeButtons = 'Styles,Format,Smiley,Anchor,Flash,Table,PageBreak,Iframe,Templates,HorizontalRule,Save,Print,Preview,NewPage,Subscript,Superscript,Strike';

	// Se the most common block elements.
	config.format_tags = 'p;h1;h2;h3;pre';

	// Make dialogs simpler.
	config.removeDialogTabs = 'image:advanced;link:advanced';
};
