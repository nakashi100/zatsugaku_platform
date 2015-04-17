<h2>投稿リスト</h2>

<?php echo $this->Html->Link('雑学を投稿する', array('controller' => 'articles', 'action' => 'create')); ?>

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
			echo '<tr>';
				echo '<td>'.$article['Category']['category_name'].'</td>'; // カテゴリ
				echo '<td>'.$article['Article']['view'].'</td>'; // View数
				echo '<td>'.count($article['Like']).'</td>'; // へぇ数
				echo '<td>'.$this->Html->Link($article['Article']['title'], array('controller' => 'articles', 'action' => 'detail', $article['Article']['id'])).'</td>'; // タイトル
				echo '<td>'.$article['Article']['detail'].'</td>'; // 詳細
				echo '<td>'.$article['User']['nickname'].'</th>'; // 投稿者
			echo '</tr>';
		}
	?>
</table>


<?php
// デバッグ用
	// echo '<pre>';
	// var_dump($test);
	// echo '</pre>';

?>
