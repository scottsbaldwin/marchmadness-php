<?php
define('HTTP_OK',   200);
define('HTTP_NO_CONTENT',   204);
define('HTTP_BAD_REQUEST',  400);
define('HTTP_NOT_FOUND',  404);
define('HTTP_INTERNAL_ERROR',  500);
    
class Response {
	
	var $isError = false;
	var $errorMsg = "";
	var $httpCode = HTTP_OK;
	var $json = "";
	
	function Response() {
		
	}	
}
?>