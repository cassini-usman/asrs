<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');


t3lib_div::loadTCA("tt_content");
$tempColumns = array( 'header_rte' => Array (
						'l10n_mode' => 'prefixLangTitle',
						'l10n_cat' => 'text',
						'label' => 'LLL:EXT:go_content/locallang_db.xml:tt_content.header_rte',
						'config' => Array (
							'type' => 'text',
							'cols' => '48',
							'rows' => '5',
							'wizards' => Array(
								'_PADDING' => 4,
								'_VALIGN' => 'middle',
								'RTE' => Array(
									'notNewRecords' => 1,
									'RTEonly' => 1,
									'type' => 'script',
									'title' => 'LLL:EXT:cms/locallang_ttc.php:bodytext.W.RTE',
									'icon' => 'wizard_rte2.gif',
									'script' => 'wizard_rte.php',
								),
								'table' => Array(
									'notNewRecords' => 1,
									'enableByTypeConfig' => 1,
									'type' => 'script',
									'title' => 'Table wizard',
									'icon' => 'wizard_table.gif',
									'script' => 'wizard_table.php',
									'params' => array('xmlOutput' => 0)
								),
								'forms' => Array(
									'notNewRecords' => 1,
									'enableByTypeConfig' => 1,
									'type' => 'script',
			#						'hideParent' => array('rows' => 4),
									'title' => 'Forms wizard',
									'icon' => 'wizard_forms.gif',
									'script' => 'wizard_forms.php?special=formtype_mail',
									'params' => array('xmlOutput' => 0)
								)
							),
							'softref' => 'typolink_tag,images,email[subst],url'
						)
					),
					'go_content_image' => txdam_getMediaTCA('image_field', 'go_content_image'),
					'go_content_image2' => txdam_getMediaTCA('image_field', 'go_content_image2'),
					'go_content_linktext' => array (
						'exclude' => 1,
						'label' => 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.go_content_linktext',
						'config' => array (
							'type' => 'input',
							'size' => '20',
							'max' => '64',
							'softref' => 'email[subst]'
						)
					),
					
					'go_teaser_layout' => array (
						'exclude' => 0,
						'label' => 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.tx_goteaser.piTeaser_layout',
						'config' => array (
							'type' => 'select',
							'items' => array (
								array('LLL:EXT:go_teaser/locallang_db.xml:tt_content.tx_goteaser.piTeaser_layout.I.0', '0', t3lib_extMgm::extRelPath('go_teaser').'res/selicon_tt_content_tx_goteaser.piTeaser_layout_0.gif'),
								array('LLL:EXT:go_teaser/locallang_db.xml:tt_content.tx_goteaser.piTeaser_layout.I.1', '1', t3lib_extMgm::extRelPath('go_teaser').'res/selicon_tt_content_tx_goteaser.piTeaser_layout_1.gif'),
								array('LLL:EXT:go_teaser/locallang_db.xml:tt_content.tx_goteaser.piTeaser_layout.I.2', '2', t3lib_extMgm::extRelPath('go_teaser').'res/selicon_tt_content_tx_goteaser.piTeaser_layout_2.gif')
							),
							'size' => 1,
							'maxitems' => 1,
						)
					),
					'go_teaser_headercolor' => array (		
						'exclude' => 0,		
						'label' => 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.tx_goteaser_headercolor',		
						'config' => array (
							'type' => 'select',
							'items' => array (
								array('LLL:EXT:go_teaser/locallang_db.xml:tt_content.tx_goteaser_headercolor.I.0', '#000000', ''),
								array('LLL:EXT:go_teaser/locallang_db.xml:tt_content.tx_goteaser_headercolor.I.1', '#ffffff', ''),
								array('LLL:EXT:go_teaser/locallang_db.xml:tt_content.tx_goteaser_headercolor.I.2', '#af1f1e', ''),
								array('LLL:EXT:go_teaser/locallang_db.xml:tt_content.tx_goteaser_headercolor.I.3', '#db9950', ''),
								array('LLL:EXT:go_teaser/locallang_db.xml:tt_content.tx_goteaser_headercolor.I.4', '#008031', ''),
								array('LLL:EXT:go_teaser/locallang_db.xml:tt_content.tx_goteaser_headercolor.I.5', '#b86024', ''),
								array('LLL:EXT:go_teaser/locallang_db.xml:tt_content.tx_goteaser_headercolor.I.6', '#0a3a6e', ''),
								array('LLL:EXT:go_teaser/locallang_db.xml:tt_content.tx_goteaser_headercolor.I.7', '#207baf', ''),
								array('LLL:EXT:go_teaser/locallang_db.xml:tt_content.tx_goteaser_headercolor.I.8', '#949494', ''),
							),
							'itemsProcFunc' => 'EXT:go_pages/lib/class.user_go_tcamodify.php:user_go_tcamodify->getSelectIconColor',
							'iconsInOptionTags' => 1,
							'size' => 1,	
							'maxitems' => 1,
						)
					),
		 'go_teaser_colorpick' => Array (
			'label' => 'Hintergrund-Farbauswaehler',
			'config' => Array (
			'type' => 'input',
			'size' => '10',
			'wizards' => array(
		             'colorpick' => array(
		                 'type' => 'colorbox',
		                 'title' => 'Color picker',
		                 'script' => 'wizard_colorpicker.php',
		                 'dim' => '20x20',
		                 'tableStyle' => 'border: solid 1px black; margin-left: 20px;',
		                 'JSopenParams' => 'height=550,width=365,status=0,menubar=0,scrollbars=1',
		                 'exampleImg' => 'gfx/wizard_colorpickerex.jpg',
		             )
		         )
				 )
				 ),
					'subheader' => Array (
						'l10n_mode' => 'prefixLangTitle',
						'l10n_cat' => 'text',
						'label' => 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.header_rte',
						'config' => Array (
							'type' => 'text',
							'cols' => '48',
							'rows' => '5',
						)
					),
					'go_teaser_line_above' => array (        
						'exclude' => 0,        
						'label' => 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.line_above',        
						'config' => array (
							'type' => 'check',
							'default' => 0,
						)
					),
					'go_teaser_line_below' => array (        
						'exclude' => 0,        
						'label' => 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.line_below',        
						'config' => array (
							'type' => 'check',
							'default' => 1,
						)
					),

);



t3lib_extMgm::addTCAcolumns('tt_content',$tempColumns,1);


$TCA['tt_content']['columns']['go_content_image']['label'] = 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.go_content_image';
$TCA['tt_content']['columns']['go_content_image']['exclude'] = 1;
$TCA['tt_content']['columns']['go_content_image']['config']['show_thumbs'] = 1;
$TCA['tt_content']['columns']['go_content_image']['config']['size'] = 1;
$TCA['tt_content']['columns']['go_content_image']['config']['maxitems'] = 1;
$TCA['tt_content']['columns']['go_content_image']['config']['minitems'] = 0;
$TCA['tt_content']['columns']['go_content_image']['config']['autoSizeMax'] = 1;

$TCA['tt_content']['columns']['go_content_image2']['label'] = 'LLL:EXT:go_teaser/locallang_db.xml:tt_content.go_content_image2';
$TCA['tt_content']['columns']['go_content_image2']['exclude'] = 1;
$TCA['tt_content']['columns']['go_content_image2']['config']['show_thumbs'] = 1;
$TCA['tt_content']['columns']['go_content_image2']['config']['size'] = 1;
$TCA['tt_content']['columns']['go_content_image2']['config']['maxitems'] = 1;
$TCA['tt_content']['columns']['go_content_image2']['config']['minitems'] = 0;
$TCA['tt_content']['columns']['go_content_image2']['config']['autoSizeMax'] = 1;


// #
// ### piTeaser
// #
$TCA['tt_content']['palettes'][$_EXTKEY . '_piTeaser_bigImage']['showitem'] = 'go_teaser_layout';
$TCA['tt_content']['types'][$_EXTKEY . '_piTeaser']['showitem'] = 'CType;;;button;1-1-1, go_teaser_layout, header_rte, go_teaser_headercolor, bodytext;;;richtext:rte_transform[flag=rte_enabled|mode=ts];2-2-2,subheader;LLL:EXT:go_teaser/locallang_db.xml:tt_content.piTeaser.subheaser,
																	--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.media,go_content_image2;LLL:EXT:go_teaser/locallang_db.xml:tt_content.piTeaser_background,go_content_image;LLL:EXT:go_teaser/locallang_db.xml:tt_content.piTeaser_foreground;go_content_piTeaser_bigImage;;3-3-3,go_teaser_colorpick,
																	--div--;LLL:EXT:go_imageedit_be/locallang_db.xml:tabLabel, tx_goimageeditbe_croped_image,
																	--div--;LLL:EXT:go_teaser/locallang_db.xml:tab_verweis,go_content_linktext, image_link,
																	--div--;LLL:EXT:cms/locallang_tca.xml:pages.tabs.access, starttime, endtime, fe_group';

																	
$TCA['tt_content']['imageedit'][$_EXTKEY.'_piTeaser']= Array
											(
											"debug" => 0,						//gibt einige Debugwerte aus
											"imgPath" => '../uploads/pics/', 	// vom Backend aus gesehen
											"rootImgPath" => 'uploads/pics/', 	// vom Frontend aus
											
											//Backend
											"selector" => Array(
												"allowCustomRatio" => 1,		//dieses Flag lässt den benutzer 
																				//das Format des Selectors frei bestimmen
											),
											
											"menu" => Array(					
												"displayType" => 0,					// 	1 : HTML-SELECT-BOX;  	
																					//	0 : BUTTONS (nachfolgende Einstellungen)
												"showImageName" => 1,				//Zeigt den Namen des Bildes an
												"showThumbnail" => 1,				//Zeigt ein Thumbnail 
												"showThumbnail_size" => "150x120",	//diesen Ausmaßes
												"showResolution" => 1,				//Zeigt die Auflösung der Bilder im Selector an
												
												"maxImages" =>2,
											),
											
											"adjustResolution" => Array(
												"enabled" => 1,					//Bild runterrechnen ( 1 ) wenn > maxDisplayedWidth & maxDisplayedHeight
												"maxDisplayedWidth" => "700",		//hoechste unangetastete im Backend Angezeigte Auflösung
												"maxDisplayedHeight" => "400",
											),
	
											);
																	
t3lib_extMgm::addPlugin(array(
	'LLL:EXT:go_teaser/locallang_db.xml:tt_content.piTeaser.CType',
	$_EXTKEY . '_piTeaser',
	t3lib_extMgm::extRelPath($_EXTKEY) . 'piTeaser/icon.gif')
,'CType');


?>