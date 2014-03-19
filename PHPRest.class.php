<?php
require_once("MarchMadness.class.php");
require_once("Response.class.php");

class PHPRest {
    
    /**
     * Parsed configuration file
     * @var str[]
     */
    var $config;
    
    /**
     * The HTTP request method used.
     * @var str
     */
    var $method = 'GET';
	
    /**
     * The HTTP request data sent (if any).
     * @var str
     */
    var $requestData = NULL;
	
	/**
	 * The URL extension stripped off of the request URL
	 * @var str
	 */
	var $extension = NULL;
	
    /**
     * The resource to act on
     * @var str
     */
    var $resource = NULL;

    /**
     * The ID of the resource.
     * @var str[]
     */
    var $uid = NULL;
    
    /**
     * Type of display, database, table or row.
     */
    var $display = NULL;
    
    /**
     * The March Madness provider
     */
    var $marchMadness = NULL;
    
    /*
     * The response to render back to the client
     */
    var $response = NULL;
    
    /**
     * Constructor. Parses the configuration file "phprestsql.ini", grabs any request data sent, records the HTTP
     * request method used and parses the request URL to find out the resource.
     * @param str iniFile Configuration file to use
     */
    function PHPRest($iniFile = 'phprest.ini') {
        $this->config = parse_ini_file($iniFile, TRUE);
        
        if (isset($_SERVER['REQUEST_URI']) && isset($_SERVER['REQUEST_METHOD'])) {
        
            if (isset($_SERVER['CONTENT_LENGTH']) && $_SERVER['CONTENT_LENGTH'] > 0) {
                $this->requestData = '';
                $httpContent = fopen('php://input', 'r');
                while ($data = fread($httpContent, 1024)) {
                    $this->requestData .= $data;
                }
                fclose($httpContent);
            }
            
            $urlString = substr($_SERVER['REQUEST_URI'], strlen($this->config['settings']['baseURL']));
			$urlParts = explode('/', $urlString);
			
			$lastPart = array_pop($urlParts);
			$dotPosition = strpos($lastPart, '.');
			if ($dotPosition !== FALSE) {
				$this->extension = substr($lastPart, $dotPosition + 1);
				$lastPart = substr($lastPart, 0, $dotPosition);
			}
			array_push($urlParts, $lastPart);
			
			if (isset($urlParts[0]) && $urlParts[0] == '') {
				array_shift($urlParts);
			}
			
            if (isset($urlParts[0])) $this->resource = $urlParts[0];
            if (count($urlParts) > 1 && $urlParts[1] != '') {
                array_shift($urlParts);
                foreach ($urlParts as $uid) {
                    if ($uid != '') {
                        $this->uid[] = $uid;
                    }
                }
            }
            
            $this->method = $_SERVER['REQUEST_METHOD'];
            
        }
        
        $this->marchMadness = new MarchMadness();
    }
    
    /**
     * Execute the request.
     */
    function exec() {
        
        switch ($this->method) {
            case 'GET':
                $this->get();
                break;
            case 'POST':
                $this->post();
                break;
            case 'DELETE':
                $this->delete();
                break;
        }
    }
    
    function doRequest() {
    	// look up the mapping
        $handler = $this->config[$this->method][$this->resource];
        // if there is no handler, return method not supported
        if (!isset($this->config[$this->method][$this->resource]) || $this->config[$this->method][$this->resource] == '') {
        	$this->methodNotAllowed($this->method);
        } else {
        	// there is a handler, call the handler with the param data
        	$response = $this->marchMadness->$handler($this->uid, $this->requestData);
        	// interpret and return the response
        	$this->response = $response;
        	
        	// handle the response
        	switch ($response->httpCode) {
        		case HTTP_BAD_REQUEST:
        			$this->badRequest();
        			break;
        		case HTTP_INTERNAL_ERROR:
        			$this->internalServerError();
        			break;
        		case HTTP_NOT_FOUND:
        			$this->notFound();
        			break;
        		case HTTP_NO_CONTENT:
        			$this->noContent();
        			break;
        		default:
        			$this->generateResponseData();
        			break;
        	}
        }
    }
    
    /**
     * Execute a GET request. 
     */
    function get() {
        if ($this->resource) {
        	$this->doRequest();
        } else {
        	$this->badRequest();
        }
    }

    /**
     * Execute a POST request.
     */
    function post() {
    	if ($this->resource && $this->uid) {
        	$this->doRequest();
        } else {
        	$this->badRequest();
        }
    }
	
    /**
     * Execute a DELETE request. 
     */
    function delete() {
    	if ($this->resource && $this->uid) {
        	$this->doRequest();
        } else {
        	$this->notFound();
        }
    }
    
    /**
     * Parse the HTTP request data.
     * @return str[] Array of name value pairs
     */
    function parseRequestData() {
        $values = array();
        $pairs = explode("\n", $this->requestData);
        foreach ($pairs as $pair) {
            $parts = explode('=', $pair);
            if (isset($parts[0]) && isset($parts[1])) {
                $values[$parts[0]] = ereg_replace("'", "''", $parts[1]);
            }
        }
        return $values;
    }
    
    /**
     * Generate the HTTP response data.
     */
    function generateResponseData() {
		if ($this->extension) {
			if (isset($this->config['mimetypes'][$this->extension])) {
				$mimetype = $this->config['mimetypes'][$this->extension];
				if (isset($this->config['renderers'][$mimetype])) {
					$renderClass = $this->config['renderers'][$mimetype];
				}
			}
		} elseif (isset($_SERVER['HTTP_ACCEPT'])) {
       		     $accepts = explode(',', $_SERVER['HTTP_ACCEPT']);
    		        $orderedAccepts = array();
       		     foreach ($accepts as $key => $accept) {
       		         $exploded = explode(';', $accept);
       		         if (isset($exploded[1]) && substr($exploded[1], 0, 2) == 'q=') {
       		             $orderedAccepts[substr($exploded[1], 2)][] = $exploded[0];
       		         } else {
       		             $orderedAccepts['1'][] = $exploded[0];
       		         }
       		     }
       		     krsort($orderedAccepts);
       		     foreach ($orderedAccepts as $acceptArray) {
       		         foreach ($acceptArray as $accept) {
       		             if (isset($this->config['renderers'][$accept])) {
       		                 $renderClass = $this->config['renderers'][$accept];
       		                 break 2;
       		             } else {
       		                 $grep = preg_grep('/'.str_replace(str_replace($accept, '*', '.*'), "/", "\/").'/', array_keys($this->config['renderers']));
       		                 if ($grep) {
       		                     $renderClass = $this->config['renderers'][$grep[0]];
       		                     break 2;
       		                 }
       		             }
       		         }
       		     }
       		 } else {
       		     $renderClass = array_shift($this->config['renderers']);
       		 }
		if (isset($renderClass)) {
			require_once($renderClass);
			$renderer = new PHPRestRenderer();
			$renderer->render($this);
		} else {
			$this->notAcceptable();
			exit;
		}
    }
        
    /**
     * Send a HTTP 201 response header.
     */
    function created($url = FALSE) {
        header('HTTP/1.0 201 Created');
        if ($url) {
            header('Location: '.$url);   
        }
    }
    
    /**
     * Send a HTTP 204 response header.
     */
    function noContent() {
        header('HTTP/1.0 204 No Content');
    }
    
    /**
     * Send a HTTP 400 response header.
     */
    function badRequest() {
        header('HTTP/1.0 400 Bad Request');
    }
    
    /**
     * Send a HTTP 401 response header.
     */
    function unauthorized($realm = 'PHPRestSQL') {
        header('WWW-Authenticate: Basic realm="'.$realm.'"');
        header('HTTP/1.0 401 Unauthorized');
    }
    
    /**
     * Send a HTTP 404 response header.
     */
    function notFound() {
        header('HTTP/1.0 404 Not Found');
    }
    
    /**
     * Send a HTTP 405 response header.
     */
    function methodNotAllowed($allowed = 'GET, HEAD') {
        header('HTTP/1.0 405 Method Not Allowed');
        header('Allow: '.$allowed);
    }
    
    /**
     * Send a HTTP 406 response header.
     */
    function notAcceptable() {
        header('HTTP/1.0 406 Not Acceptable');
        echo join(', ', array_keys($this->config['renderers']));
    }
    
    /**
     * Send a HTTP 411 response header.
     */
    function lengthRequired() {
        header('HTTP/1.0 411 Length Required');
    }
    
    /**
     * Send a HTTP 500 response header.
     */
    function internalServerError() {
        header('HTTP/1.0 500 Internal Server Error');
    }
}
?>