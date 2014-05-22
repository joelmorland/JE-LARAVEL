<?php

class API extends Eloquent {
    private $host;
    private $headers=array();
    private $curlHandler;
    private $argument;
    private $result;
    public $url;
    public function __construct($host,$headers)
    {
        $this->host=$host;
        $this->headers=$headers;
    }
    
    public function initCurl()
    {
        if (!function_exists('curl_init') || !isset($this->url))
            return false;
            
        if (is_object($this->curlHandler)) return false;

        $this->curlHandler=curl_init();
        curl_setopt($this->curlHandler, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->curlHandler, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($this->curlHandler, CURLOPT_HEADER, 0);
        curl_setopt($this->curlHandler, CURLOPT_HTTPGET, 1);
        curl_setopt($this->curlHandler, CURLOPT_SSL_VERIFYPEER, 0); 
        curl_setopt($this->curlHandler, CURLOPT_URL, $this->url);  
        curl_setopt($this->curlHandler, CURLOPT_VERBOSE, true);
    }
    
    public function createUrl()
    {
        $this->url=$this->host.$this->argument;
    }
    
    public function fetchResults($argument)
    {
        $this->argument=$argument;
        $this->createUrl();
        $this->initCurl();
        $this->result = curl_exec($this->curlHandler);
        
        if ($this->wasSuccess())
            return $this->result;
        else
            exit(json_encode(array('error'=>'No Results')));
    }
    
    public function wasSuccess()
    {
        $http_status = curl_getinfo($this->curlHandler, CURLINFO_HTTP_CODE);
        $responseLength = curl_getinfo($this->curlHandler, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
        if ($http_status==200&&$responseLength>0)
            return true;
         
         exit(print_r(curl_getinfo($this->curlHandler)));  
    }        
}
