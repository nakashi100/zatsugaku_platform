<div class="article-detail-title">
	
	<div class="article-detail-title-left">
		<ul>
			<li><?php echo $article['Category']['category_name']; ?></li>
			<li><?php echo $article['Article']['view']; ?><span>view</span></li>
			<li><?php echo count($article['Like']); ?><span>へぇ</span></li>
		</ul>
	</div>

	<div class="article-detail-title-right">
		<h2 class="article-detail-title-right__title"><?php echo $article['Article']['title']; ?></h2>
		
		<div class="article-detail-title-right__author">
			<p><?php if($article['User']['del_flg'] == 0){
				echo $this->Html->Link($article['User']['nickname'], array('controller' =>'Users', 'action' => 'view', $article['Article']['user_id']));
				}else{
					echo '<span class="article-detail-title-right__author__user">'.$article['User']['nickname'].'</span>';
				} ?></p>
			<p><?php echo date('Y/n/j', strtotime($article['Article']['created'])); ?>更新</p>
		</div>

		<div class="article-detail-title-action">
			<?php
				if(isset($loginUser)){
				////////////////  いいねの処理  ////////////////
					if($like){ 
							echo $this->Form->postLink(
									'「へぇ！」を取り消す',
									array('action' => 'resetLike', $article['Article']['id'], $loginUser['id'])
								);
					}

					if(!$like){
							echo $this->Form->postLink(
									'へぇ！なるほど♪',
									array('action' => 'like', $article['Article']['id'], $loginUser['id'])
								);
					}

				//////////////// お気に入りの処理 ////////////////
					if($favorite){
							echo $this->Form->postLink(
									'お気に入りを取り消す',
									array('action' => 'resetFavorite', $article['Article']['id'], $loginUser['id'])
								);
					}

					if(!$favorite){
							echo $this->Form->postLink(
									'お気に入りに追加',
									array('action' => 'favorite', $article['Article']['id'], $loginUser['id'])
								);
					}
				}else{
					// $loginUserが存在しなかった場合の処理
					echo $this->Html->link(
								'へぇ！なるほど♪',
								array('controller' => 'Users', 'action' => 'login', $article['Article']['id'])
							);
					echo $this->Html->Link(
								'お気に入りに追加',
								array('controller' => 'Users', 'action' => 'login', $article['Article']['id'])
							);
				}
			?>
		</div> <!-- article-detail-title-action -->
	</div> <!-- article-detail-title-right -->

<div class="article-detail-detail">
	<p><?php echo $article['Article']['detail']; ?></p>
</div>

<div class="article-detail-comment">
	<h3 class="article-detail-comment__top">コメント</h3>

		<?php
			foreach ($comments as $comment) {
				if($comment['Comment']['del_flg'] != 1){
					echo '<div class="article-detail-comment__cel">';
						echo '<div class="article-detail-comment__image">'.$this->Upload->uploadImage($comment,'User.avatar', array('style' => 'mini')).'</div>';
						echo '<div class="article-detail-comment__cel__right">';
							if($comment['User']['del_flg'] == 0){
								echo $this->Html->Link(h($comment['User']['nickname']), array('controller' => 'Users', 'action' => 'view', $comment['Comment']['user_id']));
							}else{
									echo '<span class="article-detail-comment__cel__right__user">'.h($comment['User']['nickname']).'</span>';
							}
							echo '<span class="article-detail-comment__cel__right__date">'.date('Y/n/j G:i', strtotime($comment['Comment']['created'])).'</span>';

							if(isset($loginUser) && ($comment['Comment']['user_id'] == $loginUser['id'] || $loginUser['role'] == '2')){
								echo $this->Form->postLink(
									'削除',
									array('action' => 'deleteComment', $comment['Comment']['id']), // deleteCommentというメソッドを実行するように指定
									array('confirm' => '本当に削除してよろしいですか？')
									);
							}
							echo '<p>'.h($comment['Comment']['comment']).'</p>';
						echo '</div>';
					echo '</div>';
				}
			}

		$article_id = $article['Article']['id'];

		if(isset($loginUser)){
			// コメント投稿処理
			echo '<div class="article-detail-comment__add">';
				echo $this->Form->create('Comment'); // 挿入するModel名を記載
				echo $this->Form->input('comment', array('type' => 'detail', 'placeholder' => 'コメントを書く'));
				echo $this->Form->input('article_id', array('type' => 'hidden', 'value' => $article_id));
				echo $this->Form->input('user_id', array('type' => 'hidden', 'value' => $loginUser['id']));
				echo $this->Form->end('投稿', array('class' => 'test'));
			echo '</div>';
		}else{
			// ログインしていない場合
			echo $this->Html->link('ログインしてコメントを書く', array('controller' => 'Users', 'action' => 'login', $article['Article']['id']), array('class' => 'article-detail-comment__notlogin'));
		}
	?>
</div> <!-- article-detail-comment -->