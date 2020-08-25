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
        $name = $this->getArg('name', 'John Doe');
        $this->setData(compact('name'));
    }
}
