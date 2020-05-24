<?= $this->Html->css('style'); ?>

<!--ナビゲーションバー-->
<nav class="navbar navbar-expand navbar-light fixed-top bg-primary">
    <a class="navbar-brand" href="#">
        <font color="white">PartsManagementSystem</font>
    </a>
    <div class="collapse navbar-collapse justify-content-end">
        <ul class="navbar-nav">
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

<!-- 入力フォーム -->
<div class="mx-auto" style="width: 530px">
    <ul class="nav nav-pills mt-4">
        <li class="nav-item">
            <a href="#tab1" class="nav-link active" data-toggle="pill">簡易検索</a>
        </li>
        <li class="nav-item">
            <a href="#tab2" class="nav-link" data-toggle="pill">詳細検索</a>
        </li>
        <li class="nav-item">
            <a href="#tab3" class="nav-link" data-toggle="pill">引出検索</a>
        </li>
    </ul>
    <div class="tab-content">
        <div id="tab1" class="tab-pane active">
            <div class="card border-primary">
                <div class="card-body">
                    <?= $this->Form->create('User',array('class'=>'form-inline','method'=>'get','action'=>'../parts/index')); ?>
                    <fieldset>
                    <div class="form-group">
                        <?= $this->Form->hidden('type',array('value'=>'fuzzy')) ?>
                        <?= $this->Form->text(('word'),['class' => 'form-control','style'=>'width:400px;']) ?>
                        <?= $this->Form->hidden('page',array('value'=>0)) ?>
                        <?= $this->Form->button(('search'),['class' => 'btn btn-primary']) ?>
                    </div>
                    </fieldset>
                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>
        <div id="tab2" class="tab-pane">
            <div class="card border-primary">
                <div class="card-body">
                    <?= $this->Form->create('detail',array('class'=>'form-inline','method'=>'get','action'=>'../parts/index')); ?>
                    <fieldset>
                    <div class="form-group">
                        <?= $this->Form->hidden('type',array('value'=>'detailed')) ?>
                        <?= $this->Form->text(('text1'),['class'=>'form-control','style'=>'width:390px','placeholder'=>"部品名"]) ?>
                        <?= $this->Form->text(('text2'),['class'=>'form-control mt-2','style'=>'width:390px','placeholder'=>"値"]) ?>
                        <?= $this->Form->text(('text3'),['class'=>'form-control mt-2','style'=>'width:390px','placeholder'=>"単位"]) ?>
                        <?= $this->Form->hidden('page',array('value'=>0)) ?>
                        <?= $this->Form->button(('search'),['class' => 'btn btn-primary m-auto']) ?>
                    </div>
                    </fieldset>
                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>
        <div id="tab3" class="tab-pane">
        <div class="card border-primary">
                <div class="card-body">
                    <?= $this->Form->create('User',array('class'=>'form-inline','method'=>'get','action'=>'../parts/index')); ?>
                    <fieldset>
                    <div class="form-group">
                        <?= $this->Form->hidden('type',array('value'=>'drawer')) ?>
                        <?= $this->Form->text(('word'),['class' => 'form-control','style'=>'width:400px;']) ?>
                        <?= $this->Form->hidden('page',array('value'=>0)) ?>
                        <?= $this->Form->button(('search'),['class' => 'btn btn-primary']) ?>
                    </div>
                    </fieldset>
                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>
    </div>
</div>
