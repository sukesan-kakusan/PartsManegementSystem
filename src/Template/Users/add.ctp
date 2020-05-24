<!-- 背景情報の取り込み -->
<?= $this->Html->css('style'); ?>
<!-- 中央揃え　-->
<div class="mx-auto pt-5" style="width: 600px">
<div class="card m-5" style="width: 35rem">
    <?= $this->Form->create($user,['class' => 'form-horizontal']) ?>
        <fieldset>
        <div class="form-group col-lg-12">
            <legend><?= __('Add User') ?></legend>        
            <?= $this->Form->input(('username'),['class' => 'form-control']) ?>
            <?= $this->Form->input(('password'),['class' => 'form-control']) ?>
            <?= $this->Form->input('role', [
                'options' => ['admin' => 'Admin', 'user' => 'user'],
                'class' => 'form-control'
            ]) ?>
        </div>
    </fieldset>
    <div class="col-lg-12">
            <?= $this->Form->button(__('Submit'),['class' => 'mt-1 btn btn-lg btn-primary btn-block']); ?>
        </div>
    <?= $this->Form->end() ?>
</div>