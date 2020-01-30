<?php

use Oip\ProductFeature\ProductFeature;
use Oip\ProductFeature\ProductFeatureValue;

/** @var array $arResult */

// Вывод ошибок, если они были
if (isset($arResult["ERRORS"])) { ?>
    <h3>Возникли ошибки:</h3>
    <ul>
        <? foreach ($arResult["ERRORS"] as $error) { ?>
            <li><?= $error ?></li>
        <? } ?>
    </ul>
    <? return;
}

$productsInfo = $arResult["productsInfo"];
/** @var ProductFeature[] $productFeatures */
$productFeatures = $arResult["productFeatures"];
/** @var array $productInfo */
$productInfo = array_shift($productsInfo);
if (!isset($productInfo)) {
    echo "Не удалось получить информацию о характеристиках товара";
    return;
}
?>

<h3>Товар ID <?=$productInfo["productFeatures"][array_key_first($productInfo["productFeatures"])]->getProductId()?></h3>
<p class="uk-margin-remove">Кастомные характеристики:</p>

<? /** @var ProductFeatureValue $productFeatureValue */
foreach ($productInfo["productFeatures"] as $productFeatureValue): ?>

    <li>
        <?=isset($productFeatures[$productFeatureValue->getFeatureCode()]) ?
            $productFeatures[$productFeatureValue->getFeatureCode()]->getName() :
            $productFeatureValue->getFeatureCode() ?> [код хар-ки: <?=$productFeatureValue->getFeatureCode()?>]:
                <?=($productFeatureValue->getValue() !== null && trim($productFeatureValue->getValue()) != "") ?
                    $productFeatureValue->getValue() :  "Не установлено"?>
    </li>

<?endforeach;?>

