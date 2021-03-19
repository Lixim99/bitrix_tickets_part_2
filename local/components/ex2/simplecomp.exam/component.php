<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader,
    Bitrix\Iblock;
    
CModule::IncludeModule("iblock");

if(!$productID = (int) $arParams['PRODUCT_IBLOCK_ID']) return false;
if(!$productCode = trim($arParams['PRODUCT_CODE'])) return false;

GLOBAL $USER;

if ($this->StartResultCache(false, $USER->GetID()))
{
    GLOBAL $CACHE_MANAGER;
    $CACHE_MANAGER->StartTagCache('');
    
    //CUR USER PROD
    $arCurUserProducts = array();
    $arSameUsers = array();
    $arSelect = Array("ID", "NAME", "IBLOCK_ID");
    $arFilter = Array("IBLOCK_ID"=>$productID, "ACTIVE"=>"Y", 'PROPERTY_' . $productCode =>$USER->GetID());
    $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
    while($ob = $res->GetNextElement())
    {
        $arFields = $ob->GetFields();
        $arFields['PROPERTIES'] = $ob->GetProperties();
        $arCurUserProducts[$arFields["ID"]] = array(
            'NAME' => $arFields["NAME"],
            'PRICE' => $arFields['PROPERTIES']['PRICE']['VALUE'],
            'MATERIAL' => $arFields['PROPERTIES']['MATERIAL']['VALUE'],
            'ARTNUMBER' => $arFields['PROPERTIES']['ARTNUMBER']['VALUE'],
            $productCode => $arFields['PROPERTIES'][$productCode]['VALUE'],
        );
        
        $arSameUsers = array_merge($arSameUsers, array_diff($arFields['PROPERTIES'][$productCode]['VALUE'], array($USER->GetID())));
        
    }
    
    //user
    $arSameUSersLogin = array();
    $filter = array("!ID" => $USER->GetID());
    $rsUsers = CUser::GetList(($by="personal_country"), ($order="desc"), $filter, array('FIELDS'=>array('ID', "LOGIN"))); 
    while($arUser = $rsUsers->GetNext()){
        $arSameUSersLogin[$arUser['ID']] = array('LOGIN' => $arUser["LOGIN"]);
    }
    
    //OTHER USER PROD
    $arOtherUserProducts = array();
    $arSelect = Array("ID", "NAME", "IBLOCK_ID");
    $arFilter = Array("IBLOCK_ID"=>$productID, "ACTIVE"=>"Y", 'PROPERTY_' . $productCode => array_unique($arSameUsers));
    $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
    while($ob = $res->GetNextElement())
    {
        $arFields = $ob->GetFields();
        $arFields['PROPERTIES'] = $ob->GetProperties();
        if(!in_array($USER->GetID(),$arFields['PROPERTIES'][$productCode]['VALUE'])){
            $arOtherUserProducts[$arFields["ID"]] = array(
                'NAME' => $arFields["NAME"],
                'PRICE' => $arFields['PROPERTIES']['PRICE']['VALUE'],
                'MATERIAL' => $arFields['PROPERTIES']['MATERIAL']['VALUE'],
                'ARTNUMBER' => $arFields['PROPERTIES']['ARTNUMBER']['VALUE'],
            );
            foreach($arFields['PROPERTIES'][$productCode]['VALUE'] as $userID){
                $arOtherUserProducts[$arFields["ID"]]['LOGIN'][] = $arSameUSersLogin[$userID]['LOGIN'];
            }
        }
    }
    
    $arResult['CUR_USER_PROD'] = $arCurUserProducts;
    $arResult['OTHER_USER_PROD'] = $arOtherUserProducts;
    $arResult['COUNT_CUR_USER_PROD'] = count($arCurUserProducts);
    
    $this->setResultCacheKeys(array('COUNT_CUR_USER_PROD'));
    $this->IncludeComponentTemplate();
    
    $CACHE_MANAGER->RegisterTag('users_checker');
    $CACHE_MANAGER->EndTagCache();
}
$APPLICATION->SetPageProperty('title', 'Избранных элементов ' . $arResult["COUNT_CUR_USER_PROD"]); 
?>