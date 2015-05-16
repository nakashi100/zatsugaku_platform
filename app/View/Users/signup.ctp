<div>
<?php echo $this->Form->create('User', array('type' => 'file')); ?>
	<fieldset>
		<legend>ユーザー登録</legend>
		<?php
			echo $this->Form->input(
				'email',
				array(
					'label' => 'メールアドレス'
				)
			);

			echo $this->Form->input(
				'password',
				array(
					'label' => 'パスワード'
				)
			);
			
			echo $this->Form->input(
				'nickname',
				array(
					'label' => 'ニックネーム'
				)
			);

			echo $this->Form->input(
				'gender',
				array(
					'label' => '性別',
					'options' => array(
						'1' => '男',
						'2' => '女'
						)
				)
			);

			echo $this->Form->input(
				'birthday',
				array(
					'label' => '誕生日',
					'minYear' => date('Y') - 80,
					'maxYear' => date('Y')
				)
			);

			echo $this->Form->input(
				'introduce',
				array(
					'label' => '自己紹介',
					'type' => 'textarea'
				)
			);

			echo $this->Form->input(
				'role',
				array(
					'value' => '1',
					'type' => 'hidden'
				)
			);
		?>
	</fieldset>
<?php echo $this->Form->end('送信'); ?>
</div>