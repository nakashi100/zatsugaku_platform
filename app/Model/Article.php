<?php
class Article extends AppModel{
	public $name = 'Article'; // エイリアス的なもの

	// バリデーション
	public $validate = array(
        'title' => array(
            'allowEmpty' => false,
            'rule' => array('maxLength', 60),
            'message' => '60文字以内で入力してください',
        ),
        'detail' => array(
            'allowEmpty' => false,
            'rule' => array('maxLength', 3000),
            'message' => '3000文字以内で入力してください',
        )
    );

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
		)
	);

	// 該当ユーザーの投稿した雑学を配列として取得するメソッド
	public function getArticles($user_id){
		$conditions = array(
			'user_id' => $user_id
		);

		$articles = $this->find('all', array('conditions' => $conditions));
		return $articles;
	}

	// iOSアプリに表示する情報を渡すメソッド
	public function getArticlesInfoForApp($category_id) {
		if ($category_id == 0) {
			$conditions = array(
			'Article.del_flg' => 0,
			
			// 'order' => array('Article.created' => 'desc')


			);
		}

		if ($category_id != 0) {
			$conditions = array(
				'Article.category_id' => $category_id,
				'Article.del_flg' => 0
			);
		}

		$articlesInfo = $this->find('all' , array('conditions' => $conditions, 'order' => array('Article.created' => 'desc')));
		return $articlesInfo;
	}


}