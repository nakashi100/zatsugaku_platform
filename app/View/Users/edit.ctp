<div class="user-form">
	<h1>ユーザー情報を編集する</h1>
	<?php
		echo $this->Form->create('User', array('type' => 'file')); // 画像をuploadする際はcreateのオプションをfileにする?
		
		echo $this->Form->input(
				'nickname',
				array(
					'label' => 'ニックネーム',
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
					'img',
					array(
						'type' => 'file',
						'label' => 'プロフィール画像'
					)
				);

		echo $this->Form->input(
				'introduce',
				array(
					'label' => '紹介文',
					'type' => 'textarea'
				)
			);

		echo $this->Form->end('編集する');
	?>
</div>