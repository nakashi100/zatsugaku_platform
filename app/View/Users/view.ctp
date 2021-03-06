<?php
	echo '<div class="user-top">';
		echo '<div class="user-top__left">';
			echo $this->Upload->uploadImage($user, 'User.avatar', array('style' => 'thumb'));

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
			echo '<h2 class="user-top__name">'.h($user['User']['nickname']).'</h2>';
			echo '<p class="user-top__introduce">'.h($user['User']['introduce']).'</p>';
		echo '</div>';
	echo '</div>';

	echo '<div class="user-bottom">';
		echo '<ul class="user-bottom__post">';
			if(isset($favorites_flag) && $favorites_flag == 1){
				echo '<li>'.$this->Html->Link('作成した雑学'.' (投稿済 '.h($count_posted_articles).'件) ', array('controller' => 'Users', 'action' => 'view', $user['User']['id'])).'</li>';
				echo '<li class="active">'.$this->Html->Link('お気に入り雑学'.' ('.h($count_favorite_articles).'件) ', array('controller' => 'Users', 'action' => 'view', $user['User']['id'], 1)).'</li>';
			}else{
				echo '<li class="active">'.$this->Html->Link('作成した雑学'.' (投稿済 '.h($count_posted_articles).'件) ', array('controller' => 'Users', 'action' => 'view', $user['User']['id'])).'</li>';
				echo '<li>'.$this->Html->Link('お気に入り雑学'.' ('.h($count_favorite_articles).'件) ', array('controller' => 'Users', 'action' => 'view', $user['User']['id'], 1)).'</li>';
			}
		echo '</ul>';

	/*****************************
	* 下記、article-indexから流用(クラス名含む)
	******************************/
		echo '<div class="article-contents">';
			foreach($articles as $article){
				/********  投稿済み雑学の場合    ******/
				if($article['Article']['del_flg'] == 0){
					echo '<div class="article-contents__cel">';
						echo '<div class="article-contents__cel__left">';
							echo '<p class="article-contents__cel__category">'.h($article['Category']['category_name']).'</p>'; // カテゴリ
							echo '<p class="article-contents__cel__view">'.h($article['Article']['pageviews']).'<span>view</span></p>'; // View数
							echo '<p class="article-contents__cel__likes">'.count($article['Like']).'<span>へぇ</span></p>'; // へぇ数
						echo '</div>';

						echo '<div class="article-contents__cel__right">';
							echo $this->Html->Link(h($article['Article']['title']), array('controller' => 'Articles', 'action' => 'detail', $article['Article']['id'])); // タイトル
							echo '<p class="article-contents__cel__detail">'.h($article['Article']['detail']).'</p>'; // 詳細
							if($article['User']['del_flg'] == 0){
								echo $this->Html->Link(h($article['User']['nickname']), array('controller' => 'Users', 'action' => 'view', $article['Article']['user_id']));}else{ echo '<p class="article-contents__cel__detail__user">'.h($article['User']['nickname']).'</p>'; } // 投稿者
						echo '</div>';
					echo '</div>';

					if( $favorites_flag == 0 && isset($loginUser) && ($article['Article']['user_id'] == $loginUser['id'] || $loginUser['role'] == '2') ){
			 				echo '<span class="user-bottom__article__edit">';
			 				echo $this->Html->Link(
			 						'編集する',
			 							array(
			 								'controller' => 'Articles',
			 								'action' => 'edit', $article['Article']['id']
			 								)
			 					);
			 				echo '</span>';
			 		}
			 	}
			 	/********  下書き中雑学の場合    ******/
			 	if($article['Article']['del_flg'] == 2){
			 		if( isset($loginUser) && ($article['Article']['user_id'] == $loginUser['id'] || $loginUser['role'] == '2') ){
						echo '<div class="article-contents__cel">';
							echo '<div class="article-contents__cel__left">';
								echo '<p class="article-contents__cel__category">'.h($article['Category']['category_name']).'</p>'; // カテゴリ
							echo '</div>';

							echo '<div class="article-contents__cel__right">';
								echo '<a class="article-contents__cel__saved-article-title">'.h($article['Article']['title']).'</a>'; // タイトル
								echo '<p class="article-contents__cel__detail article-contents__cel__saved-article">';
								echo $this->Html->Link(
									'【下書き中】編集を続ける',
										array(
											'controller' => 'Articles',
											'action' => 'edit', $article['Article']['id']
											),
										array('class' => 'test')
									);
								echo '</p>'; // 詳細
							echo '</div>';
						echo '</div>';
					}
			 	}
			}
		echo '</div>';
	/*****************************
	*  上記、article-indexから引用(クラス名含む)
	******************************/

	echo '</div>'; // class="user-bottom"の閉じタグ
?>

<div class="paging">
	<?php
		$pages = $this->Paginator->counter(array('format' => '{:pages}'));
		if($pages >= 2){
			echo $this->Paginator->prev('< '.__('previous'), array(), null, array('class' => 'prev disabled'));
			echo $this->Paginator->numbers(array('separator' => ''));
			echo $this->Paginator->next(__('next').'>', array(), null, array('class' => 'next disabled'));
		}
	?>
</div>

<?php // echo $this->Paginator->counter(array('format' => 'TOTAL:{:count} | SHOWING:{:current} | PAGE:{:page}/{:pages}')); ?>
