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
		echo '<h3 class="user-bottom__post">';
			echo '投稿した雑学'.'<span class="user-bottom__postCount">'.$this->Paginator->counter(array('format' => '({:count}件)')).'</span>';

			
			echo '<span>'.$this->Html->Link('てすとてすとてすと(お気に入り雑学●●件)', array('controller' => 'Users', 'action' => 'view', $user['User']['id'], 1)).'</span>';
// '?' => array('favorites' => $loginUser['id'])

		echo '</h3>';

	/*****************************
	*  ↓  article-indexから引用
	******************************/
		echo '<div class="article-contents">';
			foreach($articles as $article){
				if($article['Article']['del_flg'] != 1){
					echo '<div class="article-contents__cel">';
						echo '<div class="article-contents__cel__left">';
							echo '<p class="article-contents__cel__category">'.$article['Category']['category_name'].'</p>'; // カテゴリ
							echo '<p class="article-contents__cel__view">'.$article['Article']['view'].'<span>view</span></p>'; // View数
							echo '<p class="article-contents__cel__likes">'.count($article['Like']).'<span>へぇ</span></p>'; // へぇ数
						echo '</div>';

						echo '<div class="article-contents__cel__right">';
							echo $this->Html->Link($article['Article']['title'], array('controller' => 'Articles', 'action' => 'detail', $article['Article']['id'])); // タイトル
							echo '<p class="article-contents__cel__detail">'.$article['Article']['detail'].'</p>'; // 詳細
							echo $this->Html->Link($article['User']['nickname'], array('controller' => 'Users', 'action' => 'view', $article['Article']['user_id'])); // 投稿者
						echo '</div>';
					echo '</div>';
				}

				if( isset($loginUser) && ($article['Article']['user_id'] == $loginUser['id'] || $loginUser && $loginUser['role'] == '2') ){
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
		echo '</div>';
	/*****************************
	*  ↑  article-indexから引用
	******************************/

	echo '</div>'; // class="user-bottom"の閉じタグ
?>


<div class="paging">
	<?php
		echo $this->Paginator->prev('< '.__('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next').'>', array(), null, array('class' => 'next disabled'));
	?>
</div>

<?php // echo $this->Paginator->counter(array('format' => 'TOTAL:{:count} | SHOWING:{:current} | PAGE:{:page}/{:pages}')); ?>
