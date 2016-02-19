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
		
		return static::getStatusHtml($arrPage, $status);


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

	static function getStatusHtml($arrPage, $status) {
		
		return '<img src="system/modules/seo_preview/assets/icons/seo_'.$status.'.png" />';

	}
	
}