<?php if (count($errors) > 0) : ?>
	<div class="alert alert-danger" role="alert">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <?php foreach ($errors as $error) : ?>
			<?php echo $error ?>
    <?php endforeach ?>
	</div>
<?php endif ?>
