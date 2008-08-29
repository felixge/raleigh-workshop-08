<?php
$query = isset($query) ? $query : '';
?>
<h1><?php echo $this->pageTitle = 'Snippet Search'; ?></h1>

<?php echo $form->create('Snippet', array('action' => 'search')); ?>
<?php echo $form->input('contains', array('label' => 'Keywords:', 'value' => $query))?>
<?php echo $form->end('Search')?>

<?php
if (!isset($snippets)) {
	return;
}
?>

<?php if (empty($snippets)) : ?>
	<p>Sorry, there aren't any snippets yet.</p>
<?php return; endif; ?>

<ul>
	<?php foreach ($snippets as $snippet) : ?>
		<li>
			<?php echo $html->link($snippet['Snippet']['name'], array('controller' => 'snippets', 'action' => 'view', $snippet['Snippet']['id'])) ?>
			 &raquo; <?php echo $html->link('Edit', array('controller' => 'snippets', 'action' => 'edit', $snippet['Snippet']['id'])) ?> | 
			<?php echo $html->link('Delete', array('controller' => 'snippets', 'action' => 'delete', $snippet['Snippet']['id']), null, 'Are you sure?') ?>
		</li>
	<?php endforeach; ?>
</ul>

<?php
echo $paginator->prev();
echo $paginator->next();
?>