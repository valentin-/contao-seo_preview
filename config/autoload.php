<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'SeoPreview' => 'system/modules/seo_preview/classes/SeoPreview.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'be_seopreview' => 'system/modules/seo_preview/templates',
));
