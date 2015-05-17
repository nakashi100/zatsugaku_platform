<?php // echo $this->Html->css('article'); // cssの呼び出しを記述する  ?>
<?php if($article['Article']['del_flg'] == 1){ throw new NotFoundException(__('この記事は削除されました')); } ?>

<?php
	echo '<h2>'.$article['Article']['title'].'</h2>';

	if(isset($loginUser)){
	////////////////  いいねの処理  ////////////////
		if($like){ 
			echo '<p>';
				echo $this->Form->postLink(
						'イイネを取り消す',
						array('action' => 'resetLike', $article['Article']['id'], $loginUser['id'])
					);
			echo '</p>';
		}

		if(!$like){
			echo '<p>';
				echo $this->Form->postLink(
						'イイネする！',
						array('action' => 'like', $article['Article']['id'], $loginUser['id'])
					);
			echo '</p>';
		}

		//////////////// お気に入りの処理 ////////////////
		if($favorite){
			echo '<p>';
				echo $this->Form->postLink(
						'お気に入りを取り消す',
						array('action' => 'resetFavorite', $article['Article']['id'], $loginUser['id'])
					);
			echo '</p>';
		}

		if(!$favorite){
			echo '<p>';
				echo $this->Form->postLink(
						'お気に入りに登録する',
						array('action' => 'favorite', $article['Article']['id'], $loginUser['id'])
					);
			echo '</p>';
		}
	}

	echo '<p>■カテゴリ：'.$article['Category']['category_name'].'</p>';
	echo '<p>■view数：'.$article['Article']['view'].'</p>';
	echo '<p>■へぇ数：'.count($article['Like']).'</p>';
	echo '<p>■投稿者：'.$this->Html->Link($article['User']['nickname'], array('controller' =>'Users', 'action' => 'view', $article['Article']['user_id'])).'</p>';
	echo '<p>■更新日：'.$article['Article']['created'].'</p>';
	echo '<p>■詳細：'.$article['Article']['detail'].'</p>';

	echo '<h4>コメント</h4>';
	foreach ($comments as $comment) {
		if($comment['Comment']['del_flg'] != 1){
			echo '　　■'.h($comment['Comment']['comment']);
			echo '---'.$comment['Comment']['created'];
			echo '('.$this->Html->Link(h($comment['User']['nickname']), array('controller' => 'Users', 'action' => 'view', $comment['Comment']['user_id'])).')　　';
			if(isset($loginUser) && ($comment['Comment']['user_id'] == $loginUser['id'] || $loginUser['role'] == '2')){
				echo $this->Form->postLink(
					'削除',
					array('action' => 'deleteComment', $comment['Comment']['id']), // deleteCommentというメソッドを実行するように指定
					array('confirm' => '本当に削除してよろしいですか？')
					);
			}
			echo '<br />';
		}
	}

	$article_id = $article['Article']['id'];

	if(isset($loginUser)){
		// コメント投稿処理
		echo $this->Form->create('Comment'); // 挿入するModel名を記載
		echo $this->Form->input('comment', array('type' => 'detail', 'placeholder' => '入力してください'));
		echo $this->Form->input('article_id', array('type' => 'hidden', 'value' => $article_id));
		echo $this->Form->input('user_id', array('type' => 'hidden', 'value' => $loginUser['id']));
		echo $this->Form->end('投稿');
	}

	if( isset($loginUser) && ($article['Article']['user_id'] == $loginUser['id'] || $loginUser && $loginUser['role'] == '2') ){
		echo '<p>';
		echo $this->Html->Link(
				'雑学を編集する',
					array(
						'controller' => 'Articles',
						'action' => 'edit', $article_id
						)
			);
		echo '</p>';

		echo '<p>';
		echo $this->Form->postLink(
				'雑学を削除する',
					array('action' => 'delete', $article_id),
					array('confirm' => '本当に削除してよろしいですか？')
			);
		echo '</p>';
	}

?>




<?php
// デバッグ用
	// echo '<pre>';
	// var_dump($article);
	// echo '</pre>';
?>