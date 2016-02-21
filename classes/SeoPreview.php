<?php

class SeoPreview extends Backend
{

	static function getPageStatus($arrPage)
	{

		$status = 'na';

		if ($arrPage['type'] != 'root' && $arrPage['type'] != 'redirect' && $arrPage['type'] != 'forward') {

			$title = $arrPage['pageTitle'];
			$description = $arrPage['description'];

			if (!$title && !$description) {

				$status = 'error';

			} elseif (self::checkTitleLength($title) && self::checkDescriptionLength($description)) {

				$status = 'success';

			} else {

				$status = 'ok';

			}

		}

		return $status;

	}

	static function checkTitleLength($title)
{
	$titleLength = $GLOBALS['seoPreview']['titleLength'];
	if (strlen($title) >= $titleLength - 20 && strlen($title) <= $titleLength + 5) {
		return true;
	}
	return false;
}

	static function checkDescriptionLength($description)
	{
		$descriptionLength = $GLOBALS['seoPreview']['descriptionLength'];
		if (strlen($description) >= $descriptionLength - 20 && strlen($description) <= $descriptionLength + 5) {
			return true;
		}
		return false;
	}


	static function getPreviewTitleStatus($objPage)
	{

		$status = '';
		$title = $objPage->pageTitle;

		if(!$title) {
			$title = $objPage->title;
		}
		
		$rootId = $objPage->trail[0];
		$rootPage = self::getRootPage($rootId);
		$rootTitle = $rootPage->title;
		$title .= ' - '.$rootTitle;

		$maxLength = $GLOBALS['seoPreview']['titleLength'];
		$titleLength = strlen($title);

		$l = $maxLength - $titleLength;
		
		if($l < 0) {
			$status = 'error';
		} elseif($l < 10) {
			$status = 'ok';
		} elseif($l < 20) {
			$status = 'warn';
		}

		return array(
			'status' => $status,
			'length' => $titleLength
		);

	}

	static function getPreviewDescriptionStatus($objPage)
	{
//		$status = '';
		$description = $objPage->description;

		$maxLength = $GLOBALS['seoPreview']['descriptionLength'];
		$descriptionLength = strlen($description);

		$l = $maxLength - $descriptionLength;

		if($l < 0) {
			$status = 'error';
		} elseif($l < 10) {
			$status = 'ok';
		} elseif($l < 20) {
			$status = 'warn';
		}

		return array(
			'status' => $status,
			'length' => $descriptionLength
		);
	}



	static function getStatusHtml($arrPage, $status)
	{
		return '<img src="system/modules/seo_preview/assets/icons/seo_' . $status . '.png" />';
	}

	static function getRootPage($id) {

		if($GLOBALS['seo_preview']['rootPage'][$id]) {
			return $GLOBALS['seo_preview']['rootPage'][$id];
		}

		$objRootPage = \Controller::getPageDetails($id);
		$GLOBALS['seo_preview']['rootPage'][$id] = $objRootPage;
		return $objRootPage;
	}

	static function generatePreview($arrPage, $pageList = false) {

		$GLOBALS['TL_JAVASCRIPT']['jquery'] = 'assets/jquery/core/' . $GLOBALS['TL_ASSETS']['JQUERY'] . '/jquery.min.js';
		$GLOBALS['TL_JAVASCRIPT']['noconflict'] = 'system/modules/seo_preview/assets/js/noconflict.js';
		$GLOBALS['TL_JAVASCRIPT']['seo_preview'] = 'system/modules/seo_preview/assets/js/seo_preview.js';

		$GLOBALS['TL_CSS']['seo_preview'] = 'system/modules/seo_preview/assets/css/seo_preview.css';

		$env = \Contao\Environment::getInstance();

		if(is_array($arrPage)) {
			$id = $arrPage['id'];
		} else {
			$id = $arrPage->id;
		}

		$objPage = \Controller::getPageDetails($id);
		$rootPage = self::getRootPage($objPage->trail[0]);

		$objTemplate = new FrontendTemplate('be_seopreview');

		$objTemplate->titleLength = $GLOBALS['seoPreview']['titleLength'];
		$objTemplate->descriptionLength = $GLOBALS['seoPreview']['descriptionLength'];
		$objTemplate->pageList = $pageList;

		$objTemplate->page = $objPage->row();
		$objTemplate->title = $objPage->pageTitle ? $objPage->pageTitle : $objPage->title;
		$objTemplate->rootTitle = $rootPage->pageTitle ? $rootPage->pageTitle : $rootPage->title;
		$objTemplate->description = $objPage->description ? $objPage->description : $GLOBALS['TL_LANG']['tl_page']['noDescription'];
		$objTemplate->url = $env->url.'/'.($GLOBALS['TL_CONFIG']['addLanguageToUrl'] ? $objPage->language.'/' : '').Controller::generateFrontendUrl($objPage->row());

		$objTemplate->titleStatus = self::getPreviewTitleStatus($objPage);
		$objTemplate->descriptionStatus = self::getPreviewDescriptionStatus($objPage);
		$objTemplate->ignoreRootTitle = $rootPage->ignoreRootTitle;
		
		$objTemplate->seo_preview_noDescription = $GLOBALS['TL_LANG']['tl_page']['seo_preview_noDescription'];
		$objTemplate->seo_preview_headline = $GLOBALS['TL_LANG']['tl_page']['seo_preview_headline'];
		$objTemplate->seo_preview_title = $GLOBALS['TL_LANG']['tl_page']['seo_preview_title'];
		$objTemplate->seo_preview_description = $GLOBALS['TL_LANG']['tl_page']['seo_preview_description'];
		$objTemplate->seo_preview_info = $GLOBALS['TL_LANG']['tl_page']['seo_preview_info'];

		return $objTemplate->parse();
	}

}