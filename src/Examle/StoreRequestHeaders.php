<?php
/**
 * Zaboy lib (http://zaboy.org/lib/)
 * 
 * @copyright  Zaboychenko Andrey
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace zaboy\utils\Example;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use zaboy\rest\Middleware\StoreMiddleware;

/**
 * REST DbStore Middleware
 * 
 * @category   Utils
 * @package    Utils
 */
class DbStoreMiddleware extends StoreMiddleware
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable|null $next
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        if ($request->getMethod() !== 'GET') {
            if ($next) {
                return $next($request, $response);
            }
            return $response;
        } 
        $sessionCounter = $request->getAttribute('Session-Counter');
        //var_dump($request);
        $sessionCounter = isset($sessionCounter) ? $sessionCounter : -1;
        $id = 0;        
        $this->dataStore->create(
            [
              'id' => $id,
              'Header_Name' => '______URI_______', 
              'Header_Value' => $request->getUri()->__toString()
            ]
        );
        
        $headers = $request->getHeaders();
        foreach ($headers as $headerName => $headerValue) {
            $id = $id +1;            
            $this->dataStore->create(
                [
                   'id' => $id,
                   'Header_Name' => $headerName, 
                   'Header_Value' => $headerValue
                ]
            );        
       }
       
       return parent::__invoke($request, $response, $next);
       
    }
}