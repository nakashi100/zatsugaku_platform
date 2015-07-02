<div class="user-form">
	<h1>ユーザー情報を編集する</h1>
	<?php
		echo $this->Form->create('User', array('type' => 'file')); // 画像をuploadする際はcreateのオプションをfileにする?
		
		echo $this->Form->input(
				'nickname',
				array(
					'label' => 'ニックネーム (20文字以内)',
					// 'required' => false
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
					'User.avatar',
					array(
						'type' => 'file',
						'label' => 'プロフィール画像',
						'class' => 'user-form__img'
					)
				);

		echo '<p><a id="user-form__imgbutton">ファイルを選択</a></p>';

		echo $this->Upload->uploadImage($user, 'User.avatar', array('style' => 'thumb'), array('id' => 'user-form__newimg'));

		echo $this->Form->input(
				'introduce',
				array(
					'label' => '紹介文 (250文字以内)',
					'type' => 'textarea',
					'required' => false //html5のrequired属性の設定を外す
				)
			);

		echo $this->Form->end('更新する');
	?>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script> <!-- プレビュー機能実装のためにJavaScript File APIを使う -->