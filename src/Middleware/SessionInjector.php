<?php
/**
 * Zaboy lib (http://zaboy.org/lib/)
 * 
 * @copyright  Zaboychenko Andrey
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace zaboy\utils\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Stratigility\MiddlewareInterface;
use Zend\Session;

/**
 * Session store Middleware
 * 
 * @todo https://Aladdin:OpenSesame@www.example.com/index.html
 * @see https://en.wikipedia.org/wiki/Basic_access_authentication
 * 
 * @category   Utils
 * @package    Utils
 */
class SessionInjector implements MiddlewareInterface
{
    /**
     * @var Zend\Session\Container
     */
    protected $session;

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable|null $next
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
//session_id('rnt1h7rrap9lej18ads5va8m75')   ;     
//$response = $response->write('Sessio ID: ' .  $_SESSION['sessionID'] . '</br>' . PHP_EOL);

        $session = new Session\Container(__CLASS__);  
        $sessionCounter = $session->offsetGet('Session-Counter');
        $sessionCounter = isset($sessionCounter) ? $sessionCounter + 1 : 0;
        $session->offsetSet('Session-Counter', $sessionCounter);
        
        $responseBody = $request->getAttribute('Response-Body');
        $isPrimaryKeyValue = null !== $request->getAttribute('Primary-Key-Value');
        if ($isPrimaryKeyValue) {
            $responseBody['Session-Counter'] = $sessionCounter;
            $request = $request->withAttribute('Response-Body', $responseBody);             
        }else{
            foreach ($responseBody as $row) {
                $row['Session-Counter'] = $sessionCounter;
                $rowset[] = $row;
                $request = $request->withAttribute('Response-Body', $rowset);                
            }
        }

        if ($next) {
            return $next($request, $response);
        }
        return $response;   
    }
}