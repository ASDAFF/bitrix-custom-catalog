<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Oip\Custom\Component\Iblock\Element;

use Oip\RelevantProducts\DataWrapper;
use Oip\RelevantProducts\DBDataSource;
use Oip\CacheInfo;

use Oip\GuestUser\Repository\CookieRepository;
use Oip\GuestUser\Service;
use Oip\GuestUser\IdGenerator\DBIdGenerator;

use Bitrix\Main\Config\Configuration;

\CBitrixComponent::includeComponentClass("oip:iblock.element.list");

class COipIblockElementOne extends COipIblockElementList {

    public function executeComponent()
    {
        $this->execute();

        if(empty($this->rawData)) {
            $this->arResult["ERRORS"][] = "Ошибка: элемент не найден";
        }
        else {
            $this->arResult["ELEMENT"] = new Element(reset($this->rawData));
        }

        $this->includeComponentTemplate();

        $this->addElementView($this->arResult["ELEMENT"]->getId());


        return ($this->arResult["ELEMENT"]) ? $this->arResult["ELEMENT"]->getId() : null;
    }

    /**
     * @inheritdoc
    */
    protected function initParams($arParams)
    {
        $arParams = parent::initParams($arParams);
        $this->setDefaultParam($arParams["ELEMENT_CODE"],"");

        try {
            if(!$arParams["ELEMENT_CODE"] && !is_set($arParams["ELEMENT_ID"])) {
                throw new \Bitrix\Main\ArgumentNullException("ELEMENT_ID");
            }

            if(!$arParams["ELEMENT_CODE"] && !intval($arParams["ELEMENT_ID"])) {
                throw new \Bitrix\Main\ArgumentTypeException("ELEMENT_ID");
            }
        }
        catch (\Bitrix\Main\ArgumentException $e) {
            $this->arResult["EXCEPTION"] = $e->getMessage();
        }

        return $arParams;
    }

    /**
     * @inheritdoc
     */
    protected function consistFilter() {
        $filter = parent::consistFilter();

        if($this->getParam("ELEMENT_CODE")) {
           $filter["CODE"] = $this->getParam("ELEMENT_CODE");
        }
        else {
            $filter["ID"] = $this->getParam("ELEMENT_ID");
        }

        if($this->arParams["SECTION_ID"]) {
           unset($filter["SECTION_ID"]);
        }

        return $filter;
    }

    private function addElementView($elementID) {
        try {

            global $DB;
            global $USER;

            $cacheInfo = new CacheInfo();
            $ds = new DBDataSource($DB, $cacheInfo);
            $dw = new DataWrapper($ds);

            $userID = $USER->GetID();

            if(!$USER->IsAuthorized()) {
                $cookieName = Configuration::getValue("oip_guest_user")["cookieName"];
                $cookieExpired = Configuration::getValue("oip_guest_user")["cookieExpired"];
                $rep = new CookieRepository($cookieName, $cookieExpired);
                $idGen = new DBIdGenerator($ds);
                $gus = new Service($rep, $idGen);
                $userID = $gus->getUser()->getId();
            }

            $dw->addProductView((int)$userID, (int)$elementID);

        }
        catch(\Exception $exception) {
            echo "<p>Не удалось обработать просмотр товара: {$exception->getMessage()}</p>";
        }
    }
}