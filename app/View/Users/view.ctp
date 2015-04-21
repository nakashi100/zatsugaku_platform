<?php
	echo '<h2>'.$user['User']['nickname'].'</h2>';
	echo '<p>';
		if($user['User']['gender'] == 1){ echo '男'; }else if($user['User']['gender'] == 2){ echo '女'; };
	echo '</p>';
	echo '<p>'.$user['User']['introduce'].'</p>';

	echo '<h3>'.'投稿した雑学'.'</h3>';

	echo '<p>'.$this->Paginator->counter(array('format' => '[該当件数:{:count}件]')).'</p>';

	foreach($articles as $article){
		echo '<p>■'.$this->Html->Link($article['Article']['title'], array('controller' => 'Articles', 'action' => 'detail', $article['Article']['id'])).'</p>'; // とりあえずタイトルのみ表示⇒あとで変更する
	}
?>

<div class="paging">
	<?php
		echo $this->Paginator->prev('< '.__('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next').'>', array(), null, array('class' => 'next disabled'));
	?>
</div>

<?php
	echo '<br />';
	echo '<p>';
	echo $this->Html->Link(
			'ユーザー情報を編集する',
				array(
					'controller' => 'Users',
					'action' => 'edit', $user['User']['id']
					)
		);
	echo '</p>';
?>