<h2>ユーザー情報を編集する</h2>

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
			'introduce',
			array(
				'label' => '紹介文',
			)
		);

	echo $this->Form->end('編集する');

?>