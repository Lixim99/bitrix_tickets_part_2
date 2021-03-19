<?
AddEventHandler("main", "OnEpilog", "OnEpilogHandler");
function OnEpilogHandler(){
    if(ERROR_404  == 'Y'){
        CEventLog::Add(array(
         "SEVERITY" => "INFO",
         "AUDIT_TYPE_ID" => "ERROR_404",
         "MODULE_ID" => "main",
         "DESCRIPTION" => $_SERVER['REQUEST_URI'],
      ));
    }
}

AddEventHandler("main", "OnAfterUserUpdate", Array("MyClass", "OnAfterUserUpdateHandler"));

class MyClass
{
    function OnAfterUserUpdateHandler(&$arFields)
    {
        if(is_object($GLOBALS['CACHE_MANAGER'])){
                $GLOBALS['CACHE_MANAGER']->ClearByTag('users_checker');
            }
    }
       
}
?>