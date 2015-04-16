<?php
class Comment extends AppModel{

	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
		)
	);

	// 該当記事のコメントを配列として取得するメソッド
	public function getComments($article_id){
		$conditions = array(
			'article_id' => $article_id
		);

		$comments = $this->find('all', array('conditions' => $conditions));
		return $comments;
	}

}