<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?=time();?>
<ul><b>Избранных элементов</b>
<?foreach($arResult['CUR_USER_PROD'] as $curProd):?>
    <li>
        <?=$curProd['NAME'] . ' - ' . $curProd['PRICE'] . ' - ' . $curProd['MATERIAL'] .  ' - ' . $curProd['ARTNUMBER']?>
    </li>
<?endforeach;?>
</ul>

<ul><b>Вам понравится</b>
<?foreach($arResult['OTHER_USER_PROD'] as $othProd):?>
    <li>
        <?=$othProd['NAME'] . ' - ' . $othProd['PRICE'] . ' - ' . $othProd['MATERIAL'] .  ' - ' . $othProd['ARTNUMBER'] .
        '</br>В избранном у: ' . implode(', ',$othProd['LOGIN']);
        ?>
        
    </li>
<?endforeach;?>
</ul>