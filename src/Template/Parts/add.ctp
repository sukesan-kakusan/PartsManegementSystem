<?= $this->Html->css('style'); ?>
<script type="text/javascript" src="/js/jquery-3.3.1.min.js"></script>
<script type="text/javascript">
$(function() {
 
 $('#new_rated').click(function(){

 var tr_form = '' +
    '<div class="form-inline">' +
   '<?= $this->Form->text(('rated'),['name'=>'new_spec_name[]','class'=>'form-control mt-1','style'=>'width:170px','placeholder'=>'スペック名']) ?>' +
   '<?= $this->Form->text(('rated'),['name'=>'new_spec_value[]','class'=>'form-control mt-1','style'=>'width:200px','placeholder'=>'スペック値']) ?>'+
   '<?= $this->Form->text(('unit'),['name'=>'new_spec_unit[]','class'=>'form-control mt-1','style'=>'width:60px','placeholder'=>'単位']) ?>' +
   '</div>';
   
   $(tr_form).appendTo($('.insert'));
});
});
</script>


<nav class="navbar navbar-expand navbar-light fixed-top bg-primary">
    <a class="navbar-brand" href="/pms/users/">
        <font color="white">PartsManagementSystem</font>
    </a>
    <div class="collapse navbar-collapse justify-content-end">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="/pms/users/">
                    <font color="white">戻る</font>
                </a>
            </li>
        </ul>
    </div>
</nav>

<div class="mx-auto" style="width: 500px;">
    <?php if($flag){?>
    <div class="card" style="width: 30rem;">
        <div class="card-body">
            <div class="card-text">
                <?= $this->Form->create($part,['class'=>'form-horizontal','type'=>'file']) ?>
                <fieldset>
                <div class="form-group">
                    <!-- form class="form-inline"-->
                        <?= $this->Form->hidden('word',array('value'=>$key)) ?>
                        <?= $this->Form->hidden('status',array('value'=>'input2'))?>
                        <?= $this->Form->text(('id'),['class'=>'form-control mt-2','placeholder'=>'ID','required'=>true,'value'=>$new_id]) ?>
                        <?= $this->Form->text(('name'),['class'=>'form-control mt-2','placeholder'=>'部品名','value'=>$key,'required'=>true]) ?>
                        <?= $this->Form->text(('model_number'),['class'=>'form-control mt-2','placeholder'=>'型番','required'=>true]) ?>
                        <?= $this->Form->text(('number'),['class'=>'form-control mt-2','placeholder'=>'個数','required'=>true]) ?>
                        <?= $this->Form->text(('place'),['class'=>'form-control mt-2','placeholder'=>'収納場所','required'=>true]) ?>
                        <?= $this->Form->text(('shop_name'),['class'=>'form-control mt-2','placeholder'=>'購入先','required'=>true]) ?>
                        <?= $this->Form->text(('min_num'),['class'=>'form-control mt-2','placeholder'=>'購入最低個数','required'=>true]) ?>
                        <?= $this->Form->text(('set_num'),['class'=>'form-control mt-2','placeholder'=>'セット個数','required'=>true]) ?>
                        <?= $this->Form->text(('unit_price'),['class'=>'form-control mt-2','placeholder'=>'単価','required'=>true]) ?>
                        <?= $this->Form->text(('shop_model_number'),['class'=>'form-control mt-2','placeholder'=>'購入先型番','required'=>true]) ?>
                        <?= $this->Form->text(('maker'),['class'=>'form-control mt-2','placeholder'=>'メーカ','required'=>true]) ?>
                        <?php if(!$new_flag){ ?>
                        <?= $this->Form->text(('parts_type'),['class'=>'form-control mt-2','placeholder'=>'部品種類','value'=>$parts_type_name,'required'=>true]) ?>
                        <?php }else{?>
                        <?= $this->Form->text(('parts_type'),['class'=>'form-control mt-2','placeholder'=>'部品種類','required'=>true]) ?>
                        <?= $this->Form->input(' ',['options'=>$prefix_array,'empty'=>'部品名接頭辞を選んでください','class'=>'form-control','name'=>'prefix']) ?>
                        <?php }?>
                        <?= $this->Form->file(('datasheet'),['class'=>'form-control-file mt-2']) ?>

                        <h5 class="mt-2"><b><?= h('定格') ?></b></h5>
                        <?php foreach($spec_array as $obj): ?>
                            <div class="form-inline">
                                <?= $this->Form->hidden('spec_type_id',array('value'=>$obj['spec_type_id'])) ?>
                                <?= $this->Form->text(('rated'),['name'=>$obj['spec_type_id'].'rated','class'=>'form-control mt-2','style'=>'width:350px','placeholder'=>$obj['spec_type']]) ?>
                                <?= $this->Form->text(('unit'),['name'=>$obj['spec_type_id'].'unit','class'=>'form-control mt-2','style'=>'width:60px','value'=>$obj['unit']]) ?>
                            </div>
                        <?php endforeach;?>
                        <?php if(!$new_flag){ ?><h5 class="mt-2"><b><?= h('新規') ?></b></h5><?php }?>
                        <div class="form-inline">
                            <?= $this->Form->text(('rated'),['name'=>'new_spec_name[]','class'=>'form-control mt-2','style'=>'width:170px','placeholder'=>'スペック名']) ?>
                            <?= $this->Form->text(('rated'),['name'=>'new_spec_value[]','class'=>'form-control mt-2','style'=>'width:200px','placeholder'=>'スペック値']) ?>
                            <?= $this->Form->text(('unit'),['name'=>'new_spec_unit[]','class'=>'form-control mt-2','style'=>'width:60px','placeholder'=>'単位']) ?>
                        </div>
                        <div class="insert"></div>
                        <button type="button" class="btn mt-2" id="new_rated">+</button>
                        <?= $this->Form->button(__('Submit'),['class'=>'mt-1 btn btn-primary btn-block','name'=>'add']); ?>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
    <?php }else{?>
        <?= $this->Form->create('name',array('class'=>'form-inline','method'=>'post','action'=>'add')); ?>
            <fieldset>
            <div class="form-group">
                <?= $this->Form->hidden('status',array('value'=>'input1'))?>
                <?= $this->Form->text(('word'),['class' => 'form-control','style'=>'width:400px;','placeholder'=>'追加する部品の名称']) ?>
                <?= $this->Form->button(('search'),['class' => 'btn btn-primary']) ?>
            </div>
            </fieldset>
        <?= $this->Form->end() ?>
    <?php }?>
</div>