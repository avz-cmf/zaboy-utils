<?php
/**
 * Zaboy lib (http://zaboy.org/lib/)
 * 
 * @copyright  Zaboychenko Andrey
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace zaboy\utils\Middleware;

use Zend\Diactoros\Response\JsonResponse;
use zaboy\middleware\Middlewares\StoreMiddlewareAbstract;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Index.html
 * 
 * @category   Utils
 * @package    Utils
 */
class MainHtml
{
    
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable|null $next
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $htmlUp =
<<<HTML_UP
<!DOCTYPE html>
<html>
<head>
    <title>Test Grid Store Observation</title>      
        
    <meta charset="utf-8">
    <meta name="viewport" content="width=570">
                
    <style>
        @import "./js/dojo/resources/dojo.css";
        @import "./js/dgrid/css/dgrid.css";
        @import "./js/dijit/themes/claro/claro.css";
    </style>

    <script src="./js/dojo/dojo.js"
        data-dojo-config="async: true, parseOnLoad: true">
    </script>
</head>
<body class="claro">            
HTML_UP
        ;        
        $response = $response->write($htmlUp . PHP_EOL);    
        if ($next) {
            $response =  $next($request, $response);
        }
        //Your body content will be here
        $htmlDown =
<<<HTML_DOWN
</body>
</html>
HTML_DOWN
        ;
        $response = $response->write($htmlDown);    
        return $response;      
    }
    
    
}