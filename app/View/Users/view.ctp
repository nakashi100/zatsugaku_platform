<?php

	echo '<div class="user-top">';
		echo '<div class="user-top__left">';
			echo $this->Upload->uploadImage($user, 'User.img', array('style' => 'thumb'));

			if( isset($loginUser) && ($user['User']['id'] == $loginUser['id'] || $loginUser['role'] == '2') ){
				echo $this->Html->Link(
						'編集',
							array(
								'controller' => 'Users',
								'action' => 'edit', $user['User']['id']
								)
					);
			}
		echo '</div>';


		echo '<div class="user-top__right">';
			echo '<h2 class="user-top__name">'.$user['User']['nickname'].'</h2>';
			echo '<p class="user-top__introduce">'.$user['User']['introduce'].'</p>';
		echo '</div>';
	echo '</div>';

	echo '<div class="user-bottom">';
		echo '<h3 class="user-bottom__post">'.'投稿した雑学'.'</h3>';
		echo '<p class="user-bottom__postCount">'.$this->Paginator->counter(array('format' => '({:count}件)')).'</p>';

		foreach($articles as $article){
			echo '<div class="user-bottom__article">';
			echo $this->Html->Link($article['Article']['title'], array('controller' => 'Articles', 'action' => 'detail', $article['Article']['id'])); // とりあえずタイトルのみ表示⇒あとで変更する
			echo '</div>';
		}
	echo '</div>';
?>

<div class="paging">
	<?php
		echo $this->Paginator->prev('< '.__('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next').'>', array(), null, array('class' => 'next disabled'));
	?>
</div>
<br />