<?php

/**
 * This class renders the REST response data as JSON.
 */
class PHPRestRenderer {
   
    /**
     * @var PHPRest PHPRest
     */
    var $PHPRest;


    var $contentType = "application/json";
   
    /**
     * Constructor.
     * @param PHPRest PHPRest
     */
    function render($PHPRest) {
        $this->PHPRest = $PHPRest;
        header('Content-Type: '.$this->contentType);
    	echo $this->PHPRest->response->json;
    }
}
