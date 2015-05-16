<?php if($this->request->query('favorites')){ echo '<h2>'.'お気に入り雑学'.'</h2>'; } ?>
<?php if($this->request->query('category_id')){ echo '<h2>'.$category_name.'</h2>'; } ?>
<?php if($this->request->query('search_word')){ echo '<p>「'.$search_word.'」で検索した結果'.'</p>'; } ?>
<?php if($category_id == 0){ echo '<h2>全雑学一覧</h2>'; } ?>

<p><?php echo $this->Html->Link('雑学を投稿する', array('controller' => 'Articles', 'action' => 'create')); ?></p>
<p>
	<?php echo $this->Html->Link('新着順', array('controller' => 'Articles', 'action' => 'index', '?' => array('category_id' => $category_id, 'sort' => 1))); ?>
	<?php echo $this->Html->Link('人気順', array('controller' => 'Articles', 'action' => 'index', '?' => array('category_id' => $category_id, 'sort' => 2))); ?>
	<?php echo $this->Paginator->counter(array('format' => '[該当件数:{:count}件]')); ?>
</p>


<table>
	<tr>
		<th>カテゴリ</th>
		<th>View数</th>
		<th>へぇ数</th>
		<th>タイトル</th>
		<th>詳細</th>
		<th>投稿者</th>
	</tr>
	<?php
		foreach($articles as $article){
			if($article['Article']['del_flg'] != 1){
				echo '<tr>';
					echo '<td>'.$article['Category']['category_name'].'</td>'; // カテゴリ
					echo '<td>'.$article['Article']['view'].'</td>'; // View数
					echo '<td>'.count($article['Like']).'</td>'; // へぇ数
					echo '<td>'.$this->Html->Link($article['Article']['title'], array('controller' => 'Articles', 'action' => 'detail', $article['Article']['id'])).'</td>'; // タイトル
					echo '<td>'.$article['Article']['detail'].'</td>'; // 詳細
					echo '<td>'.$this->Html->Link($article['User']['nickname'], array('controller' => 'Users', 'action' => 'view', $article['Article']['user_id'])).'</th>'; // 投稿者
				echo '</tr>';
			}
		}
	?>
</table>

<!-- <?php echo $this->Paginator->counter(array('format' => 'TOTAL:{:count} | SHOWING:{:current} | PAGE:{:page}/{:pages}')); ?>-->

<div class="paging">
	<?php
		echo $this->Paginator->prev('< '.__('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next').'>', array(), null, array('class' => 'next disabled'));
	?>
</div>



<?php
// echo ini_get('upload_max_filesize');
//   echo ini_get('post_max_size');
//   echo ini_get('memory_limit');
// デバッグ用
	// echo '<pre>';
	// var_dump($articles);
	// echo '</pre>';
?>
