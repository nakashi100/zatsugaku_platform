<div class="user-form">
	<h1>雑学を投稿する</h1>
	<?php
		echo $this->Form->create('Article', array('type' => 'file')); // 画像をuploadする際はcreateのオプションをfileにする?
		
		echo $this->Form->input(
				'category_id',
				array(
					'label' => 'カテゴリ',
					'options' => array(
						'1' => 'エンタメ',
						'2' => '生活・健康',
						'3' => '歴史・文化',
						'4' => '生物・自然',
						'5' => '科学・技術',
						'6' => '趣味・スポーツ',
						'7' => 'ビジネス・経済',
						'8' => 'その他'
					)
				)
			);

		echo $this->Form->input(
				'title', 
				array(
					'label' => 'タイトル (60文字以内)',
					'placeholder' => 'タイトルを入力してください'
				)
			);

		echo $this->Form->input(
				'detail',
				array(
					'label' => '詳細 (3000文字以内)',
					'type' => 'textarea',
					'placeholder' => '詳細を入力してください'
				)
			);

		echo $this->Form->input(
				'user_id',
				array(
					'type' => 'hidden',
					'value' => $loginUser['id']
				)
			);

		echo '<ul>';
			echo '<li>'.$this->Form->submit('この内容で投稿する', array('name' => 'post')).'</li>';
			echo '<li>'.$this->Form->submit('下書きに保存する', array('name' => 'save')).'</li>';
		echo '</ul>';

		echo $this->Form->end();
	?>
</div>