<?php
class Like extends AppModel{
	
	// 該当イベントの「へぇ」の配列を返すメソッド(記事詳細ページで使う)
	public function getLikesList($article_id){
		$conditions = array(
			'article_id' => $article_id,
		);

		$likesList = $this->find('all', array('conditions' => $conditions));
		return $likesList;
	}
}