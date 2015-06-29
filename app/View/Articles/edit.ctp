<div class="user-form">
	<h1>投稿雑学を編集する</h1>

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
					'type' => 'hidden', // Adminが編集する場合もあるためloginUser['id']は渡さない
				)
			);

		echo $this->Form->input(
				'del_flg',
				array(
					'type' => 'hidden',
					'value' => '0'
				)
			);

		echo '<ul>';
			if($article['Article']['del_flg'] == 2){
				echo '<li>'.$this->Form->submit('下書きを保存する', array('name' => 'save')).'</li>';
			}

			echo '<li>'.$this->Form->submit('編集を完了する', array('name' => 'finish')).'</li>';
		echo '</ul>';

		echo $this->Form->end();
		
		echo $this->Form->postLink(
				'雑学を削除する',
					array('class' => 'testaaa'),						array('action' => 'delete', $article['Article']['id']),
					array('confirm' => '本当に削除してよろしいですか？')
			);
	?>
</div>