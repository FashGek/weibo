<?php foreach($this->getData() as $weibo): ?>
<div class="weibo-item fn-clear">
	<div class="avatar fn-left">
		<img src="<?php echo $weibo->user->getAvatarUrl() ?>" alt="avatar">
	</div>
	<div class="bfc">
		<div class="info">
			<?php echo CHtml::link($weibo->user->nikename, array()) ?>
		</div>
		<div class="weibo-content">
			<?php echo $weibo->content; ?>
			<p><?php echo $weibo->dateline; ?></p>
		</div>
	</div>
</div>
<?php endforeach; ?>
