<?php

$GLOBALS['TL_DCA']['tl_page']['list']['operations']['seoPreviewStatus'] = array
(
	'label'               => &$GLOBALS['TL_LANG']['tl_page']['edit'],
	'href'                => 'act=edit',
	'icon'                => 'edit.gif',
	'button_callback'     => array('tl_page_seo_preview', 'getStatus')
);


$GLOBALS['TL_DCA']['tl_page']['config']['onload_callback'][] = array('tl_page_seo_preview', 'addField');

$GLOBALS['TL_DCA']['tl_page']['fields']['seoPreview'] = array(
	'label' => &$GLOBALS['TL_LANG']['tl_page']['seoPreview'],
	'input_field_callback' => array('tl_page_seo_preview', 'generatePreview'),
    'eval' => array('tl_class'=>'clr'),
);


class tl_page_seo_preview extends tl_page {

	public function addField() {

		foreach ($GLOBALS['TL_DCA']['tl_page']['palettes'] as $k => &$palette) {
			$skipTypes = array('__selector__','root','forward','redirect');
			if(in_array($k, $skipTypes)) {
				continue;
			}
			$palette = str_replace('pageTitle', 'seoPreview,pageTitle', $palette);
		}
	}

	public function getStatus($row, $href, $label, $title, $icon, $attributes) {

		$content = SeoPreview::getPageStatus($row);
		return '<a style="position:relative;top:-1px;" href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$content.'</a> ';
	}

	public function generatePreview($dc) {

		$GLOBALS['TL_JAVASCRIPT'][] = 'assets/jquery/core/' . $GLOBALS['TL_ASSETS']['JQUERY'] . '/jquery.min.js';
		$GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/seo_preview/assets/js/noconflict.js';
		$GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/seo_preview/assets/js/seo_preview.js';

		$GLOBALS['TL_CSS'][] = 'system/modules/seo_preview/assets/css/seo_preview.css';
		
		$objPage = $this->getPageDetails($dc->id);
		$rootPage = $this->getPageDetails($objPage->trail[0]);

		$objTemplate = new FrontendTemplate('be_seopreview');

		$objTemplate->titleLength = $GLOBALS['seoPreview']['titleLength'];
		$objTemplate->descriptionLength = $GLOBALS['seoPreview']['descriptionLength'];

		$objTemplate->page = $objPage->row();
		$objTemplate->title = $objPage->pageTitle ? $objPage->pageTitle : $objPage->title;
		$objTemplate->rootTitle = $rootPage->pageTitle ? $rootPage->pageTitle : $rootPage->title;
		$objTemplate->description = $objPage->description ? $objPage->description : $GLOBALS['TL_LANG']['tl_page']['noDescription'];
		$objTemplate->url = $this->Environment->url.'/'.($GLOBALS['TL_CONFIG']['addLanguageToUrl'] ? $objPage->language.'/' : '').$this->generateFrontendUrl($objPage->row());

		$objTemplate->seo_preview_noDescription = $GLOBALS['TL_LANG']['tl_page']['seo_preview_noDescription'];
		$objTemplate->seo_preview_headline = $GLOBALS['TL_LANG']['tl_page']['seo_preview_headline'];
		$objTemplate->seo_preview_title = $GLOBALS['TL_LANG']['tl_page']['seo_preview_title'];
		$objTemplate->seo_preview_description = $GLOBALS['TL_LANG']['tl_page']['seo_preview_description'];
		$objTemplate->seo_preview_info = $GLOBALS['TL_LANG']['tl_page']['seo_preview_info'];

		return $objTemplate->parse();
	}
}
