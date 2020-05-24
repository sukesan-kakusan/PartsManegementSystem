<?= $this->Html->css('login'); ?>
<div class="mx-auto pt-5" style="width: 600px">
  <div class="card mt-5" style="width:35rem">
    <div class="form-group">
    <?= $this->Flash->render('auth') ?>
    <?= $this->Form->create('User',array('class'=>'form-horizontal')); ?>
      <fieldset>
        <div class="form-group">
          <div class="col-lg-12">
          <legend><?= __('Please enter your username and password') ?></legend>
          <?= $this->Form->input(('username'),['class' => 'form-control','required'=>true]) ?>
          <?= $this->Form->input(('password'),['class' => 'form-control','required'=>true]) ?>
          </div>
        </div>
      </fieldset>
      <div class="col-lg-12">
        <?= $this->Form->button(__('Login'),['class' => 'mt-3 btn btn-lg btn-block btn-primary']) ?>
      </div>
      <?= $this->Form->end() ?>
    </div>
  </div>
</div>