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
 * REST store Middleware with Request Headers
 * 
 * @category   Utils
 * @package    Utils
 */
class HeadWithDojo
{
    
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable|null $next
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $response = $response->write(
<<<DOJO_HEAD
<!DOCTYPE html>
<html>
<head>
    <title>Tutorial: Hello dgrid!</title>
    <meta  charset=utf-8">   
    <script 
        src='./js/dojo/dojo.js' 
        data-dojo-config="async: true, parseOnLoad: true">
    </script>
    
    <link rel="stylesheet" href="./js/dijit/themes/claro/claro.css">
    <link rel="stylesheet" href="./js/dgrid/css/dgrid.css">
    <link rel="stylesheet" href="./js/dgrid/css/skins/claro.css">

</head>
<body class="claro">
<h2>Headers</h2>                
    <div id="HeadesrsGrid"></div>
                
    <script>
        require([
                'res/RequestHeadersGrid'
        ], function (RequestHeadersGrid) {
                RequestHeadersGrid.startup();
        });
    </script>
    
<h2>IndexPhpTable</h2>
    <div id="IndexPhpTable"></div>
                
    <script>
        require([
                'res/IndexPhpTable'
        ], function (IndexPhpTable) {
                IndexPhpTable.startup();
        });
    </script>          
   
</body>
</html>
DOJO_HEAD
        );    
        
        if ($next) {
            return $next($request, $response);
        }

        return $response;      
    }
}