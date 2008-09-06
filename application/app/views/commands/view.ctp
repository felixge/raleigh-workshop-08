<h1><?php echo $this->pageTitle = 'Command: ' . $command['Command']['name']; ?></h1>
<?php echo $html->link('Back to Index', array('controller' => 'snippets', 'action' => 'index'), array('class' => 'button')); ?>
<div class="clear"></div>
<dl>
	<dt>Name:</dt>
	<dd><?php echo $command['Command']['name']?></dd>
	<dt>Snippets:</dt>
	<dd>
	<?php if (empty($command['Snippet'])): ?>
		<p>Sorry, there are no snippets where this command is used.</p>
	<?php else: ?>
		<ul class="inline">
		<?php foreach ($command['Snippet'] as $i => $snippet): ?>
			<li class="<?php echo ($i % 2) ? 'alt' : ''; ?>">
				<?php echo $html->link($snippet['name'], array('controller' => 'snippets', 'action' => 'view', $snippet['id'])); ?>
			</li>
		<?php endforeach; ?>
		</ul>
	<?php endif; ?>
	</dd>
</dl>