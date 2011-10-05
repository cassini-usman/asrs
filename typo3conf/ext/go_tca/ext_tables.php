<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');


t3lib_div::loadTCA("tt_content");
$tempColumns = array(
					/*
					 * Singleimage, upload field for 1 image.
					 */
					'singleimage_1' => txdam_getMediaTCA('image_field', 'singleimage_1'),
					'singleimage_2' => txdam_getMediaTCA('image_field', 'singleimage_2'),
					'singleimage_3' => txdam_getMediaTCA('image_field', 'singleimage_3'),
					'singleimage_4' => txdam_getMediaTCA('image_field', 'singleimage_4'),
					'singleimage_5' => txdam_getMediaTCA('image_field', 'singleimage_5'),
					'singleimage_6' => txdam_getMediaTCA('image_field', 'singleimage_6'),

					/*
					 * Multipleimages, upload field for multiple image.
					 */
					'multipleimages_1' => txdam_getMediaTCA('image_field', 'multipleimages_1'),
					'multipleimages_2' => txdam_getMediaTCA('image_field', 'multipleimages_2'),
					'multipleimages_3' => txdam_getMediaTCA('image_field', 'multipleimages_3'),
					'multipleimages_4' => txdam_getMediaTCA('image_field', 'multipleimages_4'),
					'multipleimages_5' => txdam_getMediaTCA('image_field', 'multipleimages_5'),
					'multipleimages_6' => txdam_getMediaTCA('image_field', 'multipleimages_6'),

					/*
					 * Single file upload field
					 */
					'singlefile_1' => txdam_getMediaTCA('media_field', 'singlefile_1'),
					'singlefile_2' => txdam_getMediaTCA('media_field', 'singlefile_2'),
					'singlefile_3' => txdam_getMediaTCA('media_field', 'singlefile_3'),
					'singlefile_4' => txdam_getMediaTCA('media_field', 'singlefile_4'),
					'singlefile_5' => txdam_getMediaTCA('media_field', 'singlefile_5'),
					'singlefile_6' => txdam_getMediaTCA('media_field', 'singlefile_6'),

					/*
					 * Multiple file upload field
					 */
					'multiplefiles_1' => txdam_getMediaTCA('media_field', 'multiplefiles_1'),
					'multiplefiles_2' => txdam_getMediaTCA('media_field', 'multiplefiles_2'),
					'multiplefiles_3' => txdam_getMediaTCA('media_field', 'multiplefiles_3'),
					'multiplefiles_4' => txdam_getMediaTCA('media_field', 'multiplefiles_4'),
					'multiplefiles_5' => txdam_getMediaTCA('media_field', 'multiplefiles_5'),
					'multiplefiles_6' => txdam_getMediaTCA('media_field', 'multiplefiles_6'),

					/*
					 * Input field 1
					 */
					'input_1' => array (
						'exclude' => 0,
						'label' => 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.input',
						'config' => array (
							'type' => 'input',
							'size' => '30',
						),
					),

					/*
					 * Input field 2
					 */
					'input_2' => array (
						'exclude' => 0,
						'label' => 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.input',
						'config' => array (
							'type' => 'input',
							'size' => '30',
						),
					),

					/*
					 * Input field 3
					 */
					'input_3' => array (
						'exclude' => 0,
						'label' => 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.input',
						'config' => array (
							'type' => 'input',
							'size' => '30',
						),
					),

					/*
					 * Input field 4
					 */
					'input_4' => array (
						'exclude' => 0,
						'label' => 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.input',
						'config' => array (
							'type' => 'input',
							'size' => '30',
						),
					),

					/*
					 * Input field 5
					 */
					'input_5' => array (
						'exclude' => 0,
						'label' => 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.input',
						'config' => array (
							'type' => 'input',
							'size' => '30',
						),
					),

					/*
					 * Input field 6
					 */
					'input_6' => array (
						'exclude' => 0,
						'label' => 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.input',
						'config' => array (
							'type' => 'input',
							'size' => '30',
						),
					),

					/*
					 * Link field 1
					 */
					'link_1' => array (
						'exclude' => 0,
						'label' => 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.link',
						'config' => array (
							'type' => 'input',
							'size' => '30',
							'wizards'  => array(
								'_PADDING' => 2,
								'link'     => array(
									'type'         => 'popup',
									'title'        => 'Link',
									'icon'         => 'link_popup.gif',
									'script'       => 'browse_links.php?mode=wizard',
									'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
								)
							)
						),
					),

					/*
					 * Link field 2
					 */
					'link_2' => array (
						'exclude' => 0,
						'label' => 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.link',
						'config' => array (
							'type' => 'input',
							'size' => '30',
							'wizards'  => array(
								'_PADDING' => 2,
								'link'     => array(
									'type'         => 'popup',
									'title'        => 'Link',
									'icon'         => 'link_popup.gif',
									'script'       => 'browse_links.php?mode=wizard',
									'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
								)
							)
						),
					),

					/*
					 * Link field 3
					 */
					'link_3' => array (
						'exclude' => 0,
						'label' => 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.link',
						'config' => array (
							'type' => 'input',
							'size' => '30',
							'wizards'  => array(
								'_PADDING' => 2,
								'link'     => array(
									'type'         => 'popup',
									'title'        => 'Link',
									'icon'         => 'link_popup.gif',
									'script'       => 'browse_links.php?mode=wizard',
									'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
								)
							)
						),
					),

					/*
					 * Link field 4
					 */
					'link_4' => array (
						'exclude' => 0,
						'label' => 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.link',
						'config' => array (
							'type' => 'input',
							'size' => '30',
							'wizards'  => array(
								'_PADDING' => 2,
								'link'     => array(
									'type'         => 'popup',
									'title'        => 'Link',
									'icon'         => 'link_popup.gif',
									'script'       => 'browse_links.php?mode=wizard',
									'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
								)
							)
						),
					),

					/*
					 * Link field 5
					 */
					'link_5' => array (
						'exclude' => 0,
						'label' => 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.link',
						'config' => array (
							'type' => 'input',
							'size' => '30',
							'wizards'  => array(
								'_PADDING' => 2,
								'link'     => array(
									'type'         => 'popup',
									'title'        => 'Link',
									'icon'         => 'link_popup.gif',
									'script'       => 'browse_links.php?mode=wizard',
									'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
								)
							)
						),
					),

					/*
					 * Link field 6
					 */
					'link_6' => array (
						'exclude' => 0,
						'label' => 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.link',
						'config' => array (
							'type' => 'input',
							'size' => '30',
							'wizards'  => array(
								'_PADDING' => 2,
								'link'     => array(
									'type'         => 'popup',
									'title'        => 'Link',
									'icon'         => 'link_popup.gif',
									'script'       => 'browse_links.php?mode=wizard',
									'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
								)
							)
						),
					),

					/*
					 * Textfield 1
					 */
					'text_1' => Array (
						'label' => 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.text',
						'config' => Array (
							'type' => 'text',
							'cols' => '48',
							'rows' => '5',
						)
					),

					/*
					 * Textfield 2
					 */
					'text_2' => Array (
						'label' => 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.text',
						'config' => Array (
							'type' => 'text',
							'cols' => '48',
							'rows' => '5',
						)
					),

					/*
					 * Textfield 3
					 */
					'text_3' => Array (
						'label' => 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.text',
						'config' => Array (
							'type' => 'text',
							'cols' => '48',
							'rows' => '5',
						)
					),

					/*
					 * Textfield 4
					 */
					'text_4' => Array (
						'label' => 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.text',
						'config' => Array (
							'type' => 'text',
							'cols' => '48',
							'rows' => '5',
						)
					),

					/*
					 * Textfield 5
					 */
					'text_5' => Array (
						'label' => 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.text',
						'config' => Array (
							'type' => 'text',
							'cols' => '48',
							'rows' => '5',
						)
					),

					/*
					 * Textfield 6
					 */
					'text_6' => Array (
						'label' => 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.text',
						'config' => Array (
							'type' => 'text',
							'cols' => '48',
							'rows' => '5',
						)
					),

					/*
					 * Dropdown
					 */
					'dropdown' => array (
						'exclude' => 0,
						'label' => 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.dropdown',
						'config' => array (
							'type' => 'select',
							'items' => array (
								array('1', '1'),
								array('2', '2'),
								array('3', '3'),
							),
							'size' => 1,
							'maxitems' => 1,
							'multiple' => 0,
						)
					),

					/*
					 * Checkbox
					 */
					'checkbox' => array (
						'exclude' => 0,
						'label' => 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.checkbox',
						'config' => array (
							'type' => 'check',
							'items' => array (
								array('1', '1'),
								array('2', '2'),
								array('3', '3'),
							),
							'size' => 1,
						)
					),

					/*
					 * Radiobutton
					 */
					'radiobutton' => array (
						'exclude' => 0,
						'label' => 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.radio',
						'config' => array (
							'type' => 'radio',
							'items' => array (
								array('1', '1'),
								array('2', '2'),
								array('3', '3'),
							),
							'size' => 1,
						)
					),
);

t3lib_extMgm::addTCAcolumns('tt_content',$tempColumns,1);

/*
 * Singleimage configuration, upload field for 1 image.
 */
$TCA['tt_content']['columns']['singleimage_1']['label'] = 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.singleimage';
$TCA['tt_content']['columns']['singleimage_1']['exclude'] = 1;
$TCA['tt_content']['columns']['singleimage_1']['config']['show_thumbs'] = 1;
$TCA['tt_content']['columns']['singleimage_1']['config']['size'] = 1;
$TCA['tt_content']['columns']['singleimage_1']['config']['maxitems'] = 1;

$TCA['tt_content']['columns']['singleimage_2']['label'] = 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.singleimage';
$TCA['tt_content']['columns']['singleimage_2']['exclude'] = 1;
$TCA['tt_content']['columns']['singleimage_2']['config']['show_thumbs'] = 1;
$TCA['tt_content']['columns']['singleimage_2']['config']['size'] = 1;
$TCA['tt_content']['columns']['singleimage_2']['config']['maxitems'] = 1;

$TCA['tt_content']['columns']['singleimage_3']['label'] = 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.singleimage';
$TCA['tt_content']['columns']['singleimage_3']['exclude'] = 1;
$TCA['tt_content']['columns']['singleimage_3']['config']['show_thumbs'] = 1;
$TCA['tt_content']['columns']['singleimage_3']['config']['size'] = 1;
$TCA['tt_content']['columns']['singleimage_3']['config']['maxitems'] = 1;

$TCA['tt_content']['columns']['singleimage_4']['label'] = 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.singleimage';
$TCA['tt_content']['columns']['singleimage_4']['exclude'] = 1;
$TCA['tt_content']['columns']['singleimage_4']['config']['show_thumbs'] = 1;
$TCA['tt_content']['columns']['singleimage_4']['config']['size'] = 1;
$TCA['tt_content']['columns']['singleimage_4']['config']['maxitems'] = 1;

$TCA['tt_content']['columns']['singleimage_5']['label'] = 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.singleimage';
$TCA['tt_content']['columns']['singleimage_5']['exclude'] = 1;
$TCA['tt_content']['columns']['singleimage_5']['config']['show_thumbs'] = 1;
$TCA['tt_content']['columns']['singleimage_5']['config']['size'] = 1;
$TCA['tt_content']['columns']['singleimage_5']['config']['maxitems'] = 1;

$TCA['tt_content']['columns']['singleimage_6']['label'] = 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.singleimage';
$TCA['tt_content']['columns']['singleimage_6']['exclude'] = 1;
$TCA['tt_content']['columns']['singleimage_6']['config']['show_thumbs'] = 1;
$TCA['tt_content']['columns']['singleimage_6']['config']['size'] = 1;
$TCA['tt_content']['columns']['singleimage_6']['config']['maxitems'] = 1;

/*
 * Multiple images configuration, upload field for multiple image.
 */
$TCA['tt_content']['columns']['multipleimages_1']['label'] = 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.multiimage';
$TCA['tt_content']['columns']['multipleimages_1']['exclude'] = 1;

$TCA['tt_content']['columns']['multipleimages_2']['label'] = 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.multiimage';
$TCA['tt_content']['columns']['multipleimages_2']['exclude'] = 1;

$TCA['tt_content']['columns']['multipleimages_3']['label'] = 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.multiimage';
$TCA['tt_content']['columns']['multipleimages_3']['exclude'] = 1;

$TCA['tt_content']['columns']['multipleimages_4']['label'] = 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.multiimage';
$TCA['tt_content']['columns']['multipleimages_4']['exclude'] = 1;

$TCA['tt_content']['columns']['multipleimages_5']['label'] = 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.multiimage';
$TCA['tt_content']['columns']['multipleimages_5']['exclude'] = 1;

$TCA['tt_content']['columns']['multipleimages_6']['label'] = 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.multiimage';
$TCA['tt_content']['columns']['multipleimages_6']['exclude'] = 1;

/*
 * Single file upload field configuration
 */
$TCA['tt_content']['columns']['singlefile_1']['label'] = 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.singlefile';
$TCA['tt_content']['columns']['singlefile_1']['exclude'] = 1;
$TCA['tt_content']['columns']['singlefile_1']['config']['show_thumbs'] = 1;
$TCA['tt_content']['columns']['singlefile_1']['config']['disallowed_types'] = "";
$TCA['tt_content']['columns']['singlefile_1']['config']['maxitems'] = 1;
$TCA['tt_content']['columns']['singlefile_1']['config']['size'] = 1;

$TCA['tt_content']['columns']['singlefile_2']['label'] = 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.singlefile';
$TCA['tt_content']['columns']['singlefile_2']['exclude'] = 1;
$TCA['tt_content']['columns']['singlefile_2']['config']['show_thumbs'] = 1;
$TCA['tt_content']['columns']['singlefile_2']['config']['disallowed_types'] = "";
$TCA['tt_content']['columns']['singlefile_2']['config']['maxitems'] = 1;
$TCA['tt_content']['columns']['singlefile_2']['config']['size'] = 1;

$TCA['tt_content']['columns']['singlefile_3']['label'] = 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.singlefile';
$TCA['tt_content']['columns']['singlefile_3']['exclude'] = 1;
$TCA['tt_content']['columns']['singlefile_3']['config']['show_thumbs'] = 1;
$TCA['tt_content']['columns']['singlefile_3']['config']['disallowed_types'] = "";
$TCA['tt_content']['columns']['singlefile_3']['config']['maxitems'] = 1;
$TCA['tt_content']['columns']['singlefile_3']['config']['size'] = 1;

$TCA['tt_content']['columns']['singlefile_4']['label'] = 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.singlefile';
$TCA['tt_content']['columns']['singlefile_4']['exclude'] = 1;
$TCA['tt_content']['columns']['singlefile_4']['config']['show_thumbs'] = 1;
$TCA['tt_content']['columns']['singlefile_4']['config']['disallowed_types'] = "";
$TCA['tt_content']['columns']['singlefile_4']['config']['maxitems'] = 1;
$TCA['tt_content']['columns']['singlefile_4']['config']['size'] = 1;

$TCA['tt_content']['columns']['singlefile_5']['label'] = 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.singlefile';
$TCA['tt_content']['columns']['singlefile_5']['exclude'] = 1;
$TCA['tt_content']['columns']['singlefile_5']['config']['show_thumbs'] = 1;
$TCA['tt_content']['columns']['singlefile_5']['config']['disallowed_types'] = "";
$TCA['tt_content']['columns']['singlefile_5']['config']['maxitems'] = 1;
$TCA['tt_content']['columns']['singlefile_5']['config']['size'] = 1;

$TCA['tt_content']['columns']['singlefile_6']['label'] = 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.singlefile';
$TCA['tt_content']['columns']['singlefile_6']['exclude'] = 1;
$TCA['tt_content']['columns']['singlefile_6']['config']['show_thumbs'] = 1;
$TCA['tt_content']['columns']['singlefile_6']['config']['disallowed_types'] = "";
$TCA['tt_content']['columns']['singlefile_6']['config']['maxitems'] = 1;
$TCA['tt_content']['columns']['singlefile_6']['config']['size'] = 1;

/*
 * Mulitple files upload field configuration
 */
$TCA['tt_content']['columns']['multiplefiles_1']['label'] = 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.multifiles';
$TCA['tt_content']['columns']['multiplefiles_1']['exclude'] = 1;

$TCA['tt_content']['columns']['multiplefiles_2']['label'] = 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.multifiles';
$TCA['tt_content']['columns']['multiplefiles_2']['exclude'] = 1;

$TCA['tt_content']['columns']['multiplefiles_3']['label'] = 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.multifiles';
$TCA['tt_content']['columns']['multiplefiles_3']['exclude'] = 1;

$TCA['tt_content']['columns']['multiplefiles_4']['label'] = 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.multifiles';
$TCA['tt_content']['columns']['multiplefiles_4']['exclude'] = 1;

$TCA['tt_content']['columns']['multiplefiles_5']['label'] = 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.multifiles';
$TCA['tt_content']['columns']['multiplefiles_5']['exclude'] = 1;

$TCA['tt_content']['columns']['multiplefiles_6']['label'] = 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.multifiles';
$TCA['tt_content']['columns']['multiplefiles_6']['exclude'] = 1;

// Show go_tca
$TCA['tt_content']['types'][$_EXTKEY . '_piTCA']['showitem'] = 'CType;;;button;1-1-1, '.
																'singleimage_1, singleimage_2, singleimage_3, singleimage_4, singleimage_5, singleimage_6, '.
																'multipleimages_1, multipleimages_2, multipleimages_3, multipleimages_4, multipleimages_5, multipleimages_6, '.
																'input_1, input_2, input_3, input_4, input_5, input_6, '.
																'text_1;;;richtext:rte_transform[flag=rte_enabled|mode=ts], '.
																'text_2;;;richtext:rte_transform[flag=rte_enabled|mode=ts], '.
																'text_3;;;richtext:rte_transform[flag=rte_enabled|mode=ts], '.
																'text_4;;;richtext:rte_transform[flag=rte_enabled|mode=ts], '.
																'text_5;;;richtext:rte_transform[flag=rte_enabled|mode=ts], '.
																'text_6;;;richtext:rte_transform[flag=rte_enabled|mode=ts], '.
																'singlefile_1, singlefile_2, singlefile_3, singlefile_4, singlefile_5, singlefile_6, '.
																'multiplefiles_1, multiplefiles_2, multiplefiles_3, multiplefiles_4, multiplefiles_5, multiplefiles_6, '.
																'dropdown, checkbox, radiobutton, '.
																'link_1, link_2, link_3, link_4, link_5, link_6';

/*
 * Multiline Header
 */
$TCA['tt_content']['columns']['header']['config'] = array(
	'type' => 'text',
	'rows' => '2',
	'cols' => '56',
	'max' => '256'
);

?>