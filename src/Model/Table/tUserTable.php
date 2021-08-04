<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class tUserTable extends Table
{
    public function initialize(array $config): void
    {
        $this->setTable('t_user');
    }
}
