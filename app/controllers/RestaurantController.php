<?php

class RestaurantController extends JustEatController {

    public $areacode, $restaurantId, $menuId, $categoryId;
    protected $urlConfig=array('search'              =>  '/restaurants?q=$areacode',
                                'menus'              =>  '/restaurants/$restaurantId/menus',
                                'menuProducts'       =>  '/menus/$menuId/productcategories',
                                'categoryProducts'   =>  '/menus/$menuId/productcategories/$categoryId/products ');
    public function __construct($api)
    {
        $this->api = $api;
    }
    public function setAreacode($areacode)
    {
        $this->areacode=$areacode;
    }
    public function setRestaurantId($restaurantId)
    {
        $this->restaurantId=$restaurantId;
    }
    public function areaSearch()
    {
        $this->verifyAreacode();
        $this->getUrlFromConfig('search');
        return $this->api->fetchResults($this->url);
    }
    public function verifyAreacode()
    {
        if ($this->validUkPostcode($this->areacode)===false)
            return $this->jsonError('Invalid postcode');          
    }
    public function getMenu()
    {
        $this->getUrlFromConfig('menus');
        return $this->api->fetchResults($this->url);
    }
    public function getMenuProducts()
    {
        $this->getUrlFromConfig('menuProducts');
        return $this->api->fetchResults($this->url);
    }
    public function getCategoryProducts()
    {
        $this->getUrlFromConfig('categoryProducts');
        return $this->api->fetchResults($this->url);
    }
    public function getFirstMenuId()
    {
        $menu = $this->getMenu();
        //lets get the menuId
        $menu = json_decode($menu);
        
        if (!isset($menu->Menus[0]))
            return;
            
        $this->menuId=$menu->Menus[0]->Id;            
    }
    public function getDefaultMenuProducts()
    {
        $this->getFirstMenuId();
        //lets get the products
        if (isset($this->menuId))
            return $this->getMenuProducts();
    } 

}
