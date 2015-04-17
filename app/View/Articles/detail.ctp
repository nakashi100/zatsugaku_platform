<?php // echo $this->Html->css('article'); // cssの呼び出しを記述する  ?>
<?php if($article['Article']['del_flg'] == 1){ throw new NotFoundException(__('この記事は削除されました')); } ?>

<?php
	echo '<h2>'.$article['Article']['title'].'</h2>';
	echo '<p>■カテゴリ：'.$article['Category']['category_name'].'</p>';
	echo '<p>■view数：'.$article['Article']['view'].'</p>';
	echo '<p>■へぇ数：'.count($article['Like']).'</p>';
	echo '<p>■投稿者：'.$article['User']['nickname'].'</p>';
	echo '<p>■更新日：'.$article['Article']['created'].'</p>';
	echo '<p>■詳細：'.$article['Article']['detail'].'</p>';

	echo '<h4>コメント</h4>';
	foreach ($comments as $comment) {
		if($comment['Comment']['del_flg'] != 1){
			echo '　　■'.h($comment['Comment']['comment']).'---'.$comment['Comment']['created'].'('.h($comment['User']['nickname']).')　　';
			echo $this->Form->postLink(
				'削除',
				array('action' => 'deleteComment', $comment['Comment']['id']), // deleteCommentというメソッドを実行するように指定
				array('confirm' => '本当に削除してよろしいですか？')
				);
			echo '<br />';
		}
	}

	$article_id = $article['Article']['id'];
	echo $this->Form->create('Comment'); // 挿入するModel名を記載
	echo $this->Form->input('comment', array('type' => 'detail', 'placeholder' => '入力してください'));
	echo $this->Form->input('article_id', array('type' => 'hidden', 'value' => $article_id));
	// echo $this->Form->input('user_id', array('type' => 'hidden', 'value' => 1)); // 最後にログインユーザーが投稿者になるようにする	

?>




<?php




// デバッグ用
	// echo '<pre>';
	// var_dump($comments);
	// echo '</pre>';
?>