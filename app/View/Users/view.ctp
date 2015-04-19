<?php
	echo '<h2>'.$user['User']['nickname'].'</h2>';
	echo '<p>'.$user['User']['gender'].'</p>';
	echo '<p>'.$user['User']['introduce'].'</p>';

	echo '<h3>'.'投稿した雑学'.'</h3>';

	foreach($articles as $article){
		echo '<p>■'.$this->Html->Link($article['Article']['title'], array('controller' => 'Articles', 'action' => 'detail', $article['Article']['id'])).'</p>'; // とりあえずタイトルのみ表示⇒あとで変更する
	}
