<?= $this->Html->css('style'); ?>
<nav class="navbar navbar-expand navbar-light fixed-top bg-primary">
    <a class="navbar-brand" href="/pms/users">
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
    <div class="card" style="width: 30rem;">
        <div class="card-body">
            <div class="card-text">
                <?= $this->Form->create($entity,['class'=>'form-horizontal','url'=>['action'=>'editRecord']]) ?>
                <fieldset>
                <div class="form-group">
                        <?= $this->Form->hidden('id') ?>
                        <?= $this->Form->text(('name'),['class'=>'form-control mt-2','style'=>'width:423px;','placeholder'=>'部品名','required'=>true]) ?>
                        <?= $this->Form->text(('model_number'),['class'=>'form-control mt-2','style'=>'width:423px;','placeholder'=>'型番','required'=>true]) ?>
                        <?= $this->Form->text(('rated'),['class'=>'form-control mt-2','style'=>'width:423px;','placeholder'=>'定格','required'=>true]) ?>
                        <?= $this->Form->text(('number'),['class'=>'form-control mt-2','style'=>'width:423px;','placeholder'=>'個数','required'=>true]) ?>
                        <?= $this->Form->text(('place'),['class'=>'form-control mt-2','style'=>'width:423px;','placeholder'=>'収納場所','required'=>true]) ?>
                        <?= $this->Form->button(__('Submit'),['class'=>'mt-1 btn btn-lg btn-primary btn-block']); ?>
                </div>
                </fieldset>
            </div>
        </div>
    </div>
</div>