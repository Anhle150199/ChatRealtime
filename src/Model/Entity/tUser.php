<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

class tUser extends Entity
{
    protected $_accessible = [
        '*' => true,
        'name' => true,
        'email' => true,
        'password' => true,
    ];
}
