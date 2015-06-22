<div class="article-top">
	<?php
		if($this->request->query('favorites')){echo '<h2>'.'お気に入り雑学'.'</h2>';}
		else if($this->request->query('category_id')){echo '<h2>「'.$category_name.'」に関する雑学</h2>';}
		else if($this->request->query('search_word')){echo '<h2>「'.$search_word.'」で検索した結果'.'</h2>';}
		else if($category_id == 0){echo '<h2>全ての雑学リスト</h2>';}
	?>
	<p><?php echo $this->Paginator->counter(array('format' => '　({:count}件)')); ?></p>
</div>

<div class="article-sort">
	<ul>
		<?php if($sort_flag == 1){ echo '<li class="active">'; }else{ echo '<li>'; } ?>
			<?php echo $this->Html->Link('新着順', array('controller' => 'Articles', 'action' => 'index', '?' => array('category_id' => $category_id, 'sort' => 1))); ?>
		</li>
		<?php if($sort_flag == 1){ echo '<li>'; }else{ echo '<li class="active">'; } ?>
			<?php echo $this->Html->Link('人気順', array('controller' => 'Articles', 'action' => 'index', '?' => array('category_id' => $category_id, 'sort' => 2))); ?>
		</li>
	</ul>
</div>

<div class="article-contents">
		<?php
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
			}
		?>
</div>

 <?php // echo $this->Paginator->counter(array('format' => 'TOTAL:{:count} | SHOWING:{:current} | PAGE:{:page}/{:pages}')); ?>

<div class="paging">
	<?php
		echo $this->Paginator->prev('< '.__('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next').'>', array(), null, array('class' => 'next disabled'));
	?>
</div>



<?php
	echo $this->Html->script('script');


// echo ini_get('upload_max_filesize');
//   echo ini_get('post_max_size');
//   echo ini_get('memory_limit');
// デバッグ用
	// echo '<pre>';
	// var_dump($articles);
	// echo '</pre>';
?>
