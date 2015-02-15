<?php

$GLOBALS['TL_DCA']['tl_page']['config']['onload_callback'][] = array('tl_content_seo_preview', 'addField');


$GLOBALS['TL_DCA']['tl_page']['fields']['seoPreview'] = array(
	 'input_field_callback' => array('tl_content_seo_preview', 'generatePreview'),
     'eval' => array('tl_class'=>'clr'),
);


class tl_content_seo_preview extends Backend {

	public function addField() {

		foreach ($GLOBALS['TL_DCA']['tl_page']['palettes'] as $k => &$palette) {
			$skipTypes = array('__selector__','root','forward','redirect');
			if(in_array($k, $skipTypes)) {
				continue;
			}
			$palette = str_replace('pageTitle', 'seoPreview,pageTitle', $palette);
		}
	}

	public function generatePreview($dc) {

		$GLOBALS['TL_JAVASCRIPT'][] = 'assets/jquery/core/' . $GLOBALS['TL_ASSETS']['JQUERY'] . '/jquery.min.js|static';
		$GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/seo_preview/assets/js/noconflict.js';
		$GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/seo_preview/assets/js/seo_preview.js';

		$GLOBALS['TL_CSS'][] = 'system/modules/seo_preview/assets/css/seo_preview.css';
		
		$this->loadLanguageFile('tl_content');

		$objPage = $this->getPageDetails($dc->id);
		$rootPage = $this->getPageDetails($objPage->trail[0]);

		$objTemplate = new FrontendTemplate('be_seopreview');
		$objTemplate->page = $objPage->row();
		$objTemplate->title = $objPage->pageTitle ? $objPage->pageTitle : $objPage->title;
		$objTemplate->rootTitle = $rootPage->pageTitle ? $rootPage->pageTitle : $rootPage->title;
		$objTemplate->description = $objPage->description ? $objPage->description : $GLOBALS['TL_LANG']['tl_content']['noDescription'];
		$objTemplate->url = $this->Environment->url.'/'.$this->generateFrontendUrl($objPage->row());

		$objTemplate->seo_preview_noDescription = $GLOBALS['TL_LANG']['tl_content']['seo_preview_noDescription'];
		$objTemplate->seo_preview_headline = $GLOBALS['TL_LANG']['tl_content']['seo_preview_headline'];
		$objTemplate->seo_preview_title = $GLOBALS['TL_LANG']['tl_content']['seo_preview_title'];
		$objTemplate->seo_preview_description = $GLOBALS['TL_LANG']['tl_content']['seo_preview_description'];
		$objTemplate->seo_preview_info = $GLOBALS['TL_LANG']['tl_content']['seo_preview_info'];

		return $objTemplate->parse();
	}

}