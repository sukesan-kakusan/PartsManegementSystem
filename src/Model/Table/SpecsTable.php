<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class SpecsTable extends Table
{
    public function initialize(array $config)
    {
        $this->belongsTo('Spec_types',['joinType'=>'INNER']);
    }
}