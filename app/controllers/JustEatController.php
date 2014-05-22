<?php

class JustEatController extends \BaseController {
    public $api;
    protected $urlConfig;
    protected $url;
    public function __construct($api)
    {
        $this->api=$api;
    }
    
    public function getUrlFromConfig($urlConfigIndex)
    {
        $this->url = $this->urlConfig[$urlConfigIndex];
        $this->populateUrl();
    }    
    
    public function populateUrl()
    {
        //lets match any variables in the URL and replace with any set class variables
        preg_match_all('/(?<=\$)[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/',$this->url,$matches);
        foreach (array_values($matches[0]) AS $m)
            if (isset($this->$m))
                $this->url = str_replace('$'.$m,$this->$m,$this->url);
    }
    
    public function validUkPostcode($postcode){
        return true;
    }
    
    public function jsonError($message)
    {
        exit(json_encode(array('error'=>$message)));
    }
}
