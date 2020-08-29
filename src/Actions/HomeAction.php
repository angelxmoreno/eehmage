<?php
declare(strict_types=1);

namespace App\Actions;

/**
 * Class HomeAction
 * @package App\Actions
 */
class HomeAction extends ActionBase
{
    public function run()
    {
        $authorization = $this->getRequest()->getHeaderLine('Authorization');
        $apiKey = str_replace('Bearer ', '', $authorization);
        $apiOk = $apiKey === $_ENV['APP_API_KEY'];
        $this->setData([
            'status' => 'ok',
            'auth' => $apiOk
        ]);
    }
}
