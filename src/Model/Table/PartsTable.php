<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class PartsTable extends Table
{
    public function initialize(array $config)
    {
        $this->hasOne('Stocks',['joinType'=>'INNER']);
        $this->hasOne('Specs',['joinType'=>'INNER','foreignKey'=>'PARTS_ID']);
    }
}