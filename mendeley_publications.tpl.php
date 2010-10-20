<div id="mendeley_documents">
	<ul>
		<?php foreach($docs as $d): ?>
			<li><?php print theme('mendeley_document', $d); ?></li>
		<?php endforeach; ?>
	</ul>
	<?php print theme('pager'); ?>
</div>