<?php

// config

$GLOBALS['TL_DCA']['tl_page']['config']['onload_callback'][] = array('tl_page_seo_preview', 'addField');

// operations

$GLOBALS['TL_DCA']['tl_page']['list']['operations']['seoPreviewStatus'] = array
(
	'label'               => &$GLOBALS['TL_LANG']['tl_page']['edit'],
	'href'                => 'act=edit',
	'icon'                => 'edit.gif',
	'button_callback'     => array('tl_page_seo_preview', 'getStatus')
);

// fields

$GLOBALS['TL_DCA']['tl_page']['fields']['seoPreview'] = array(
	'label' => &$GLOBALS['TL_LANG']['tl_page']['seoPreview'],
	'input_field_callback' => array('SeoPreview', 'generatePreview'),
    'eval' => array('tl_class'=>'clr'),
);

$GLOBALS['TL_DCA']['tl_page']['fields']['ignoreRootTitle'] = array(
	'label'                   => &$GLOBALS['TL_LANG']['tl_page']['ignoreRootTitle'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "char(1) NOT NULL default ''"
);


class tl_page_seo_preview extends tl_page {

	public function addField() {

		foreach ($GLOBALS['TL_DCA']['tl_page']['palettes'] as $k => &$palette) {
			$skipTypes = array('__selector__','root','forward','redirect');

			if($k == 'root') {
				$palette = str_replace('pageTitle', 'pageTitle,ignoreRootTitle', $palette);
			}

			if(in_array($k, $skipTypes)) {
				continue;
			}
			$palette = str_replace('pageTitle', 'seoPreview,pageTitle', $palette);
		}
	}

	public function getStatus($row, $href, $label, $title, $icon, $attributes) {

		$status = SeoPreview::getPageStatus($row);
		$content = SeoPreview::getStatusHtml($row, $status);

		if($status != 'na') {
			$preview = SeoPreview::generatePreview($row, true);
		}
		
		$html = '<div class="seo_preview_link '.$status.'">';
		$html .= '<a data-show-preview="'.$row['id'].'" href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$content.'</a> ';
		$html .= $preview;
		$html .= '</div>';

		return $html;
	}


}
