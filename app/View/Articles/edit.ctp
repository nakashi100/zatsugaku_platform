<h2>投稿雑学を編集する</h2>

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
				'label' => 'タイトル',
				'placeholder' => 'タイトルを入力してください'
			)
		);

	echo $this->Form->input(
			'detail',
			array(
				'label' => '詳細',
				'type' => 'textarea',
				'placeholder' => '詳細を入力してください'
			)
		);

	echo $this->Form->input(
			'user_id',
			array(
				'type' => 'hidden',
			)
		);

	echo $this->Form->end('編集を完了する');

	
	echo '<p>';
		echo $this->Form->postLink(
				'雑学を削除する',
					array('action' => 'delete', $article_id),
					array('confirm' => '本当に削除してよろしいですか？')
			);
	echo '</p>';

?>