<?php

/**
 * @author Mohsin Khan
 * @copyright 2011
 * @drag to develop
 * @Created on 14 Feb 2012
 */
echo "<pre>";
print_r($_GET);
echo "</pre>";



$piName = $_GET['piName'];
$extNameU = $_GET['extName'];
$extName = $extNameU;
$extNameUexplode = explode("_",$extName);
$extName = $extNameUexplode[0] . $extNameUexplode[1];

$filePath = $_GET['filePath'];
echo "</br>" . $filePath.$extNameU;
echo "</br>" . $extName;
echo "</br>" . $extNameU;
echo "</br>" . $piName;
// Creating Extension if no exisists
if(!is_dir($filePath.$extNameU))
{
	mkdir($filePath.$extNameU, 0700);

	$file1 = 'temp/extFiles/ext_emconf.php';
	$newFile1 = $filePath."$extNameU/ext_emconf.php";
	$file2 = 'temp/extFiles/ext_icon.gif';
	$newFile2 = $filePath."$extNameU/ext_icon.gif";
	$file3 = 'temp/extFiles/ext_localconf.php';
	$newFile3 = $filePath."$extNameU/ext_localconf.php";
	$file4 = 'temp/extFiles/ext_tables.php';
	$newFile4 = $filePath."$extNameU/ext_tables.php";
	$file5 = 'temp/extFiles/ext_tables.sql';
	$newFile5 = $filePath."$extNameU/ext_tables.sql";
	$file6 = 'temp/extFiles/ext_typoscript_setup.txt';
	$newFile6 = $filePath."$extNameU/ext_typoscript_setup.txt";
	$file7 = 'temp/extFiles/locallang_db.xml';
	$newFile7 = $filePath."$extNameU/locallang_db.xml";

	//coping files ext_emconf.php
	$handle = fopen($file1, 'r') or die("can't open file");
	$data = fread($handle,filesize($file1));
	########## Finding and replacing ####################
	$data = str_replace("Gosign Teaser",$extNameU,$data);
	$handle = fopen($newFile1, 'w') or die('Cannot open file:  '.$newFile1);
	fwrite($handle, $data);
	fclose($handle);
	//coping files ext_icon.php
	$handle = fopen($file2, 'r') or die("can't open file");
	$data = fread($handle,filesize($file2));
	fclose($handle);
	$handle = fopen($newFile2, 'w') or die('Cannot open file:  '.$newFile2);
	fwrite($handle, $data);
	fclose($handle);
	//coping files ext_localconf.php
	$handle = fopen($file3, 'r') or die("can't open file");
	$data = fread($handle,filesize($file3));
	fclose($handle);
	$handle = fopen($newFile3, 'w') or die('Cannot open file:  '.$newFile3);
	fwrite($handle, $data);
	fclose($handle);
	//coping files ext_tables.php
	$handle = fopen($file4, 'r') or die("can't open file");
	$data = fread($handle,filesize($file4));
	fclose($handle);
	$handle = fopen($newFile4, 'w') or die('Cannot open file:  '.$newFile4);
	fwrite($handle, $data);
	fclose($handle);
	//coping files ext_tables.sql
	$handle = fopen($file5, 'r') or die("can't open file");
	$data = fread($handle,filesize($file5));
	fclose($handle);
	$handle = fopen($newFile5, 'w') or die('Cannot open file:  '.$newFile5);
	fwrite($handle, $data);
	fclose($handle);
	//coping files ext_typoscript_setup.txt
	$handle = fopen($file6, 'r') or die("can't open file");
	$data = fread($handle,filesize($file6));
	fclose($handle);
	$handle = fopen($newFile6, 'w') or die('Cannot open file:  '.$newFile6);
	fwrite($handle, $data);
	fclose($handle);
	//coping files locallang_db.xml
	$handle = fopen($file7, 'r') or die("can't open file");
	$data = fread($handle,filesize($file7));
	fclose($handle);
	$handle = fopen($newFile7, 'w') or die('Cannot open file:  '.$newFile7);
	fwrite($handle, $data);
	fclose($handle);
	echo "Extension Created\n";
}

// File handling
// Reading Element File
$elementFile = "temp/class.tx_goteaser_pi1.php";
$handle = fopen($elementFile, 'r') or die("can't open file");
$elementData = fread($handle,filesize($elementFile));
fclose($handle);
########## Finding and replacing ####################
$newData = str_replace("pi1",$piName,$elementData);
$newData2 = str_replace("goteaser",$extName,$newData);
$newData3 = str_replace("go_teaser",$extNameU,$newData2);
$elementData = $newData3;
##########################################################
// Reading Template File
$templateFile = "temp/template.html";
$handle = fopen($templateFile, 'r') or die("can't open file");
$templateData = fread($handle,filesize($templateFile));
fclose($handle);

// Reading addinExtTables.txt file
$addinExtFile = "temp/addinExtTables.txt";
$handle = fopen($addinExtFile, 'r') or die("can't open file");
$addinExtData = fread($handle,filesize($addinExtFile));
fclose($handle);
########## Finding and replacing ####################
$newData = str_replace("piMohsin",$piName,$addinExtData);
$newData2 = str_replace("goteaser",$extName,$newData);
$newData3 = str_replace("go_mohsin",$extNameU,$newData2);
$addinExtData = $newData3;

// Reading ext_tables.php file
$tablesFile = $filePath."$extNameU/ext_tables.php";
$handle = fopen($tablesFile, 'r') or die("can't open file");
$tablesData = fread($handle,filesize($tablesFile));
fclose($handle);

// Reading locallang_db.xml file
$locallangFile = $filePath."$extNameU/locallang_db.xml";
$handle = fopen($locallangFile, 'r') or die("can't open file");
$locallangData = fread($handle,filesize($locallangFile));
fclose($handle);

// Reading ext_localconf.php file
$localconfFile = $filePath."$extNameU/ext_localconf.php";
$handle = fopen($localconfFile, 'r') or die("can't open file");
$localconfData = fread($handle,filesize($localconfFile));
fclose($handle);
//##########################################################################\\

 $i = 1;
 $totalCount = count($_GET);
 $markerNames = array();
 $TCAnames = array();
 $localangLable = array();
 $extra = array();
 $TCA = "";
 $markers = "";
 $TCAforTables = "";
 $locallang = "";
 $itrationCount = 1;
 foreach($_GET as $key => $value)
 { 
	// minus a number if you add a new field in the form of the extension 
	if($i <= $totalCount-4)
	{
		if($itrationCount == 1)
		{
			$TCAnames[$key] = str_replace(' ', '', $value);
			$localangLable[$key] = $value;
		} else if($itrationCount == 2)
		{
			$markerNames[$key] = $value;
		} else if($itrationCount == 3)
		{
			$extra[$key] = $value;
		}

		if($itrationCount < 3)
		{
			$itrationCount++;
		}
		else
		{
			$itrationCount = 1;
		}
	}
	else
	{
		 break;
	 }
	 $i++;
 }

foreach($TCAnames as $key => $value)
{
		if($key != '--div--'){
			echo $key . "<br/>";
			$TCA .= "\n\t\t\$mustache->" . strtolower($markerNames[$key."Marker"]) . " = " . TCAoptions(optionsFieldName($key),$extra[optionsFieldName($key)],$key);
			$markers .= "\n\t{{{" . $markerNames[$key."Marker"] . "}}}";
		}
		if($extra[optionsFieldName($key)] == "yes"){
			$TCAforTables .= "\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t,$key;LLL:EXT:$extNameU/locallang_db.xml:tt_content.$piName.$value;;richtext:rte_transform[flag=rte_enabled|mode=ts]";
		} else {
			$TCAforTables .= "\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t,$key;LLL:EXT:$extNameU/locallang_db.xml:tt_content.$piName.$value";
		}

	
		$locallang .= "\n\t\t\t<label index=\"tt_content.$piName.$value\">".$localangLable[$key]."</label>";
}

echo "<pre>";
echo $TCA;
echo "</pre>";

//die('Die here');




$dirName = $filePath.$extNameU ."/". $piName;
if(is_dir($dirName))
{
	die("Element Directory already exisit");

} else {
	mkdir($dirName, 0700);
	mkdir($dirName.'/templates', 0700);
	echo "Directory is Created : " . $piName;
	//Creating Elemint PHP file
		$partElementData = readBeforeAfter("\t\t\$mustache->setPlugin(\$this);",$elementData);
		$elementDataWrite = $partElementData[0] . $TCA . $partElementData[1];
		$my_file = $filePath."$extNameU/$piName/class.tx_$extName"."_$piName.php";
		$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
		fwrite($handle, $elementDataWrite);
		fclose($handle);
	//Creating Element Template file
		$partTemplateData = readBeforeAfter("<!-- maintemplate BEGIN -->",$templateData);
		$templateDataWrite = $partTemplateData[0] . $markers . $partTemplateData[1];
		$my_file = $filePath."$extNameU/$piName/templates/template.mustache.html";
		$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
		fwrite($handle, $templateDataWrite);
		fclose($handle);
	//Creating ext_tables.php file;
		$partTableData = explode("?>",$tablesData);
		$addinExtDataPart = readBeforeAfter("CType;;;button;1-1-1",$addinExtData);
		$tablesDataExt = $addinExtDataPart[0] . $TCAforTables . $addinExtDataPart[1];
		$tablesDataWrite = $partTableData[0] . $tablesDataExt . "\n?>";
		$my_file = $filePath."$extNameU/ext_tables.php";
		$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
		fwrite($handle, $tablesDataWrite);
		fclose($handle);
	//Creating ext_localconf.php file
		$partconfData = explode("?>",$localconfData);
		$addExtension = "t3lib_extMgm::addPItoST43(\$_EXTKEY,'$piName/class.tx_$extName"."_$piName.php','_$piName','CType',1);";
		$confDataWrite = $partconfData[0] . $addExtension . "\n?>";
		$my_file = $filePath."$extNameU/ext_localconf.php";
		$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
		fwrite($handle, $confDataWrite);
		fclose($handle);
	//Creating locallang_db.xml file
		$locallang .= "\n\t\t\t<label index=\"tt_content.$piName.CType\"> ". $piName ." </label>";
		$partLocallangData = readBeforeAfter("<languageKey index=\"default\" type=\"array\">",$locallangData);
		$templateDataWrite = $partLocallangData[0] . $locallang . $partLocallangData[1];
		$my_file = $filePath."$extNameU/locallang_db.xml";
		$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
		fwrite($handle, $templateDataWrite);
		fclose($handle);

}











////////////////FUNCTIONS\\\\\\\\\\\\\\\\\\\\\\\

function readBeforeAfter($point,$data)
{
		$explode = explode($point,$data);
		$explode[0] .= $point;
		return $explode;
}

function TCAoptions($key,$value,$value2)
{
	echo $value . "<br>";
	if(preg_match("/RTE/i",$key) && $value == 'yes')
		return "\$this->pi_RTEcssText(\$this->cObj->data['".$value2."']);";
	else if(preg_match("/RTE/i",$key) && $value == 'no')
		return "\$this->cObj->data['".$value2."'];";
	else if(preg_match("/LINK/i",$key) && $value == 'yes')
		return "\$this->cObj->getimageLink_URL('".$value2."');";
	else if(preg_match("/LINK/i",$key) && $value == 'no')
		return "\$this->getImage('".$value2."');";
	else if(preg_match("/input/i",$key))
		return "\$this->cObj->data['".$value2."'];";
	else if(preg_match("/singlefile/i",$key))
		return "\$this->getDamImagesFiles('tt_content',\$this->cObj->data['uid'],'".$value2."');";
	else if(preg_match("/link/i",$key))
		return "\$this->cObj->getTypoLink_URL('".$value2."');";
}

function optionsFieldName($key)
{
	if(preg_match("/text_/i",$key))
		return $key."RTE";
 	else if(preg_match("/singleimage_/i",$key))
		return $key."LINK";
	else
		return $key;
}
?>