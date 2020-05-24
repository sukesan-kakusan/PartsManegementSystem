<?= $this->Html->css('style'); ?>
<nav class="navbar navbar-expand navbar-light fixed-top bg-primary">
    <a class="navbar-brand" href="/pms/users/">
        <font color="white">PartsManagementSystem</font>
    </a>
    <div class="collapse navbar-collapse justify-content-end">
        <ul class="navbar-nav">
            
        </ul>
    </div>
</nav>



<div class="container">
<div class="mx-auto">
<?= $this->Form->create('Search',array('class'=>'form-inline','method'=>'post','action'=>'sql')); ?>
    <fieldset>
        <?= $this->Form->text(('script'),['class'=>'form-control mr-sm-2','style'=>'width:500px']) ?>
        <?= $this->Form->button(('execute'),['class'=>'btn btn-info my-2 my-sm-0','type'=>'submit']) ?>
    </fieldset>
<?= $this->Form->end() ?>
    <div class="card">
        <div class="card-body">
           <?php pr($result); ?>
        </div>
    </div>
</div>
</div>