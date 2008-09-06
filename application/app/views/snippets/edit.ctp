<h1><?php echo $this->pageTitle = 'Edit Snippet'; ?></h1>
<?php echo $form->create('Snippet', array('action' => 'edit'))?>
<?php echo $form->input('id')?>
<?php echo $form->input('name', array('label' => 'Code:'))?>
<?php echo $form->input('description', array('label' => 'Description:'))?>
<?php echo $form->input('commands', array('label' => 'Commands (separate by comma):'))?>
<?php echo $form->end('Save')?>