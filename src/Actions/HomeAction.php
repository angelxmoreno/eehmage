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
        $this->setData([
            'status' => 'ok'
        ]);
    }
}
