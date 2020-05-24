<?= $this->Html->css('style'); ?>
<nav class="navbar navbar-expand navbar-light fixed-top bg-primary">
    <a class="navbar-brand" href="/pms/users/">
        <font color="white">PartsManagementSystem</font>
    </a>
    <div class="collapse navbar-collapse justify-content-end">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" onclick="window.location.href='/pms/parts/alter/' +'<?php echo $obj->PARTS_ID; ?>';">
                    <font color="white">編集</font>
                </a>
            </li>
        </ul>
    </div>
</nav>
<div class="container">
<div class="mx-auto">
    <div class="card">
        <div class="card-body">
            <center>
                <h4 class="card-title"><?= $obj->NAME ?>　<?= $obj->MODEL_NUMBER ?></h4>
            </center>
            <div class="card-text">
                <dl class="row mt-5 ml-5">
                    <dt class="col-3 ">部品を使う</dt>
                    <dd class="col">
                    <?= $this->Form->create('Search',array('class'=>'form-inline','url'=>['action'=>'use'])); ?>
                    <fieldset>
                        <?= $this->Form->hidden('id',array('value'=>$obj->PARTS_ID)) ?>
                        <?= $this->Form->text(('num'),['class'=>'form-control','type'=>'number','style'=>'width:70px','value'=>1,'action'=>'use','min'=>1,'max'=> 100]) ?>
                        <?= $this->Form->button(('使う'),['class'=>'btn btn-primary','type'=>'submit']) ?>
                    </fieldset>
                <?= $this->Form->end() ?>
                    </dd>
                </dl></div>
                 <dl class="row ml-5">
                    <dt class="col-3">部品を追加</dt>
                    <dd class="col">
                    <?= $this->Form->create('Search',array('class'=>'form-inline','url'=>['action'=>'purchase'])); ?>
                    <fieldset>
                        <?= $this->Form->hidden('id',array('value'=>$obj->PARTS_ID)) ?>
                        <?= $this->Form->text(('num'),['class'=>'form-control','type'=>'number','style'=>'width:70px','value'=>1,'action'=>'use','min'=>1]) ?>
                        <?= $this->Form->button(('追加'),['class'=>'btn btn-primary','type'=>'submit']) ?>
                    </fieldset>
                <?= $this->Form->end() ?>
                    </dd>
                </dl>
            <div class=" d-flex align-items-start">
                <table class="table mt-2">
                <thead>
                    <tr>
                        <th>属性</th>
                        <th>値</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <div class="p-2"><td><b>個数</b></td>
                            <td><?= $obj->stock->STOCK_NUM ?></td>
                        </div>
                    </tr>
                    <tr>    
                        <div class="p-2"><td><b>定格</b></td>
                            <td><?php foreach ($spec[(string)$obj->PARTS_ID] as $sp): ?>
                                <?= h($sp['spec_type']['SPEC_NAME']);?>:<?= h($sp['VALUE']); ?><?= h($sp['spec_type']['UNIT']);?><br>
                            <?php endforeach; ?></td>
                        </div>
                    </tr>
                    <tr>
                        <div class="p-2"><td><b>場所</b></td>
                            <td>
                                <?php 
                                if(strcmp($obj->stock->DRAWER_ID,'id')==0) 
                                    echo $obj->stock->DRAWER_ID.'('.$obj->PARTS_ID.')';
                                else
                                    echo $obj->stock->DRAWER_ID;
                                ?>
                            </td>
                        </div>
                    </tr>
                    <tr>
                        <div class="p-2"><td><b>データシート</b></td>
                            <td>
                                <?php 
                                if($is_exist)
                                    echo $this->Html->link($filename,"/datasheets/".$filename);
                                else
                                    echo 'データシートがありません';
                                ?>
                            </td>
                        </div>
</tr>
                    <tr>
                        <div class="p-2"><td><b>価格</b></td>
                        <td>
                            <?php foreach ($purchase[(string)$obj->PARTS_ID] as $pu): ?>
                                    購入先:<?= h($pu['shop']['SHOP_NAME']); ?>
                                    購入最低個数:<?= h($pu['MIN_NUM']); ?>
                                    セット数:<?= h($pu['SET_NUM']); ?>
                                    単価:<?= h($pu['UNIT_PRICE']); ?>円
                                    <br>
                            <?php endforeach; ?></td>
                    
                        </div> 
</tr>
                    <tr>
                        <div class="p-2"><td><b>メーカー</b></td>
                        <td><?= $maker->MAKER_NAME ?></td>
                        </div> 
                    </tr>
                    <tr>
                    
                    <tr><td><b>関連部品</b></td>
                    <td><?php 
                        if($is_exist_relation){?>
                            <?php foreach($relation as $re): ?>
                            <div onclick="window.location.href='/pms/parts/info?name=' +'<?php echo $re->parts['PARTS_ID']; ?>';"><?= h($re->parts['NAME'])?>　<?= h($re->parts['MODEL_NUMBER'])?></div><br>
                            <?php endforeach;?>
                        <?php
                        }
                        else{
                            echo '関連部品はありません';
                        }
                    ?></td>
                    </tr>
                    </div>
                </div>
                </div>
        </div>
    </div>
</div>