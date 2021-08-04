<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

class tFeed extends Entity
{
    protected $_accessible = [
        '*' => true,
        'name' => true,
        'message' => true,
        
    ];
}
