<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class RelationalPartsTable extends Table
{
    public function initialize(array $config)
    {
        $this->belongsTo('parts',['joinType'=>'INNER','foreignKey'=>'related_parts_id','bindingKey'=>'parts_id']);
    }
}