<?php
declare(strict_types=1);

namespace App\Actions;

use App\Database\Models\UserModel;
use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * Class HomeAction
 * @package App\Actions
 */
class HomeAction extends ActionBase
{
    public function run()
    {
        $capsule = $this->getContainer()->get(Capsule::class);
        $blueprint = $capsule->getDatabaseManager()->getSchemaBuilder();
        $user = new UserModel();

        $this->setData([

            UserModel::all()
        ]);
    }
}
