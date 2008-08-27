<h1><?php echo $this->pageTitle = 'Add Snippet'; ?></h1>

<?php echo $form->create('Snippet', array('action' => 'add'))?>
<?php echo $form->input('name', array('label' => 'Name:'))?>
<?php echo $form->input('description', array('label' => 'Description:'))?>
<?php echo $form->input('commands', array('label' => 'Commands (separate by comma):'))?>
<?php echo $form->end('Add')?>