<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class Stock extends Entity {
    protected $_accssible = [
        '*' => true,
    ];
}