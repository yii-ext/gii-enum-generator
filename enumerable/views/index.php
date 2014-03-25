<?php
/**
 * index class file.
 *
 * @author Gladchenko Oleg
 */
$class = get_class($model);
?>

<h1>Model Generator</h1>

<p>This generator generates a model class for the specified database table.</p>

<?php $form = $this->beginWidget('CCodeForm', array('model' => $model)); ?>


<div class="row enumerable-class">
    <?php echo $form->label($model, 'enumerableName', array('required' => true)); ?>
    <?php echo $form->textField($model, 'enumerableName', array('size' => 65)); ?>
    <div class="tooltip">
        This is the name of the enumerable class to be generated (e.g. <code>PostStatusEnumerable</code>, <code>CommentTypeEnumerable</code>).
        It is case-sensitive.
    </div>
    <?php echo $form->error($model, 'enumerableName'); ?>
</div>
<div class="row">
    <?php echo $form->label($model, 'enumerableItems', array('required' => true)); ?>
    <?php echo $form->textArea($model, 'enumerableItems'); ?>
    <div class="tooltip">
        This is the list of the enumerable items to be generated.
        You can use two types of valid record: each key per line or value:text par line.
    </div>
    <?php echo $form->error($model, 'enumerableItems'); ?>
</div>
<div class="row sticky">
    <?php echo $form->labelEx($model, 'baseClass'); ?>
    <?php echo $form->textField($model, 'baseClass', array('size' => 65)); ?>
    <div class="tooltip">
        This is the class that the new enumerable class will extend from.
        Please make sure the class exists and can be autoloaded.
    </div>
    <?php echo $form->error($model, 'baseClass'); ?>
</div>
<div class="row sticky">
    <?php echo $form->labelEx($model, 'enumerablePath'); ?>
    <?php echo $form->textField($model, 'enumerablePath', array('size' => 65)); ?>
    <div class="tooltip">
        This refers to the directory that the new enumerable class file should be generated under.
        It should be specified in the form of a path alias, for example, <code>application.models.enumerables</code>.
    </div>
    <?php echo $form->error($model, 'enumerablePath'); ?>
</div>
<div class="row">
    <?php echo $form->label($model, 'author'); ?>
    <?php echo $form->textField($model, 'author'); ?>
    <div class="tooltip">
        This is the author for doc-blocks.
    </div>
    <?php echo $form->error($model, 'author'); ?>
</div>

<?php $this->endWidget(); ?>