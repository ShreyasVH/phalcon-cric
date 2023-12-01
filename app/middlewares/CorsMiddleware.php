<?php

namespace app\middlewares;

use Phalcon\Mvc\Micro\MiddlewareInterface;

class CorsMiddleware implements MiddlewareInterface
{
    public function call($app)
    {
        // Add CORS headers
        $allowedOrigins = [
            'https://cric.react.com',
            'https://cric.vue.com',
            'https://cric.angular.com',
            'https://cric.sveltekit.com',
            'https://cric.solid.com'
        ];
        $origin = $app->request->getHeader('Origin');
        if (in_array($origin, $allowedOrigins)) {
            $app->response->setHeader('Access-Control-Allow-Origin', $origin);
            $app->response->setHeader('Access-Control-Allow-Methods', 'GET, PUT, POST, DELETE, OPTIONS');
            $app->response->setHeader('Access-Control-Allow-Headers', 'Accept, Origin, X-Requested-With, Content-Type, Referer, User-Agent, Access-Control-Allow-Origin');

            // Handle preflight requests
            if ($app->request->getMethod() == 'OPTIONS') {
                $app->response->setStatusCode(200, 'OK');
                $app->response->send();
                exit;
            }
        }


        return true;
    }
}