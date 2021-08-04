<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class tFeedTable extends Table
{
    public function initialize(array $config): void
    {
        $this->setTable('t_feed');
        $this->setEntityClass('App\Model\Entity\tFeed');
    }
}
