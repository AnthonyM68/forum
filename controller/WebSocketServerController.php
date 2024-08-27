<?php


/**
 * FORUM CONTROLLER
 */

namespace Controller;
require_once 'vendor/autoload.php';

use App\AbstractController;
use App\ControllerInterface;
use Ratchet\Client\Factory;

class WebSocketServerController extends AbstractController implements ControllerInterface
{

    public function index()
    {      
        echo "test";
        die;
    }
    public function send()
    {
        $loop = React\EventLoop\Factory::create();
        $connector = new Ratchet\Client\Factory($loop);

        $connector('ws://echo.websocket.org')->then(function(Ratchet\Client\WebSocket $conn) {
            $conn->on('message', function($msg) {
                echo "Received: {$msg}\n";
            }); 

            $conn->send('Hello World!');
        }, function($e) use ($loop) {
            echo "Could not connect: {$e->getMessage()}\n";
            $loop->stop();
        }); 
    }
}
