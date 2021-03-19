<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentParameters = array(
	"PARAMETERS" => array(
		"PRODUCT_IBLOCK_ID" => array(
			"NAME" => GetMessage("PRODUCT_IBLOCK_ID_MESS"),
			"TYPE" => "STRING",
		),
        "PRODUCT_CODE" => array(
			"NAME" => GetMessage("PRODUCT_CODE_MESS"),
			"TYPE" => "STRING",
		),
        "CACHE_TIME"  =>  array("DEFAULT"=>36000000),

	),
);
?>