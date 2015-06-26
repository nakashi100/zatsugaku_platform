<div class="user-form">
	<?php echo $this->Session->flash('auth'); // authのエラーメッセージの表示 ?> 

	<h1><?php echo __('ログイン') ?></h1>
	<?php echo $this->Form->create('User'); ?>
		<fieldset>
			<?php echo $this->Form->input('email', array('label' => 'メールアドレス')); ?>
			<?php echo $this->Form->input('password', array('label' => 'パスワード')); ?>
		</fieldset>
	<?php echo $this->Form->end(__('ログイン')); ?>
</div>