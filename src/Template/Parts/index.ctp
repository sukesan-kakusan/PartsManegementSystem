<?= $this->Html->css('style'); ?>
<?php use Cake\Routing\Router; ?>
<?php $url = Router::reverse($this->request, false); ?>
<nav class="navbar navbar-expand navbar-light fixed-top bg-primary">
    <a class="navbar-brand" href="/pms/users/">
        <font color="white">PartsManagementSystem</font>
    </a>
    <div class="collapse navbar-collapse justify-content-end">
        <ul class="navbar-nav">
            <?= $this->Form->create('Search',array('class'=>'form-inline','method'=>'get','action'=>'index')); ?>
                <fieldset>
                <?= $this->Form->hidden('type',array('value'=>'fuzzy')) ?>
                    <?= $this->Form->text(('word'),['class'=>'form-control mr-sm-2','action'=>'index','type'=>'search']) ?>
                    <?= $this->Form->hidden('page',array('value'=>0)) ?>
                    <?= $this->Form->button(('search'),['class'=>'btn btn-info my-2 my-sm-0','type'=>'submit']) ?>
                </fieldset>
            <?= $this->Form->end() ?>
            <li class="nav-item">
                <a class="nav-link " href="/pms/users/logout">
                    <font color="white">ログアウト</font>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/pms/parts/add">
                    <font color="white">部品を追加</font>
                </a>
            </li>
        </ul>
    </div>
</nav>

<div class="container">
<div class="mx-auto">
    <b><?= h($total); ?>件</b>(<?=ceil($total/6)?>ページ中<?= h($page)+1?>ページ)
    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <!--th>ID</th-->
                        <th>部品名</th>
                        <th>型番</th>
                        <th>定格</th>
                        <th>場所</th>
                        <th>個数</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $obj): ?>           
                        <tr>
                            <!--td>< ?= $obj->PARTS_ID ?></td-->
                            <td onclick="window.location.href='/pms/parts/info?name=' +'<?php echo $obj->PARTS_ID; ?>';"><?= h($obj->NAME) ?></td>
                            <td onclick="window.location.href='/pms/parts/info?name=' +'<?php echo $obj->PARTS_ID; ?>';"><?= h($obj->MODEL_NUMBER) ?></td>
                            <td onclick="window.location.href='/pms/parts/info?name=' +'<?php echo $obj->PARTS_ID; ?>';">
                                <?php foreach ($spec[(string)$obj->PARTS_ID] as $sp): ?>
                                    <?= h($sp['spec_type']['SPEC_NAME']);?>:<?= h($sp['VALUE']); ?><?= h($sp['spec_type']['UNIT']);?>
                                    <br>
                                <?php endforeach; ?>
                            </td> 
                            <td onclick="window.location.href='/pms/parts/info?name=' +'<?php echo $obj->PARTS_ID; ?>';"><?php 
                                if(strcmp($obj->stock->DRAWER_ID,'id')==0) 
                                    echo $obj->stock->DRAWER_ID.'('.$obj->PARTS_ID.')';
                                else
                                    echo $obj->stock->DRAWER_ID;
                            ?></td>
                            <td onclick="window.location.href='/pms/parts/info?name=' +'<?php echo $obj->PARTS_ID; ?>';"><?= h($obj->stock->STOCK_NUM) ?></td>  
                            <td class="m-0 p-0">
                            <?= $this->Form->create('Search',array('class'=>'form-inline','url'=>['action'=>'use'])); ?>
                            <fieldset>
                                <?= $this->Form->hidden('id',array('value'=>$obj->PARTS_ID)) ?>
                                <?= $this->Form->text(('num'),['class'=>'form-control','type'=>'number','style'=>'width:70px','value'=>1,'action'=>'use','min'=>1,'max'=> 100]) ?>
                                <?= $this->Form->button(('使う'),['class'=>'btn btn-primary','type'=>'submit']) ?>
                            </fieldset>
                            <?= $this->Form->end() ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="mx-auto">
        <nav aria-label="ページ送り">
  <ul class="pagination mt-2">
    <li class="page-item"><a class="page-link" href=<?php echo $back ?>>前へ</a></li>
    <li class="page-item"><a class="page-link" href=<?php echo $next ?>>次へ</a></li>
  </ul>
</nav>
</div>
    </div>
</div>
</div>