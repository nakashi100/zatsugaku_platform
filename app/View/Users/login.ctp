<div>
	<?php echo $this->Session->flash('auth'); // authのエラーメッセージの表示 ?> 
	<?php echo $this->Form->create('User'); ?>
		<fieldset>
			<legend><?php echo __('ログイン') ?></legend>
			<?php echo $this->Form->input('email', array('label' => 'メールアドレス')); ?>
			<?php echo $this->Form->input('password', array('label' => 'パスワード')); ?>
		</fieldset>
	<?php echo $this->Form->end(__('ログイン')); ?>
</div>