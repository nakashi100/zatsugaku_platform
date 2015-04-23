<?php
class Article extends AppModel{
	public $name = 'Article'; // エイリアス的なもの

	// Article(多数)にCategory(1つ)・User(1つ)をbelongstoでアソシエーションする
	public $belongsTo = array(
		'Category' => array( // Categoryは配列の引数になる
			'className' => 'Category',
			'foreignKey' => 'category_id'
		),
		'User' => array( // Userは配列の引数になる
			'className' => 'User',
			'foreignKey' => 'user_id'
		)
	);

	// Article(1つ)にLike(多数)・Comment(多数)をhasmanyでアソシエーションする
	public $hasMany = array(
		'Like' => array(
			'className' => 'Like',
			'foreignKey' => 'article_id'
		),
		// 'Comment' => array(
		// 	'className' => 'Comment',
		// 	'foreignKey' => 'article_id'
		// )
	);

	// 該当ユーザーの投稿した雑学を配列として取得するメソッド
	public function getArticles($user_id){
		$conditions = array(
			'user_id' => $user_id
		);

		$articles = $this->find('all', array('conditions' => $conditions));
		return $articles;
	}

}