<?php
class Comment extends AppModel{

	// バリデーション
	public $validate = array(
        'comment' => array(
        	'allowEmpty' => false, // 空欄ではダメ
            'rule' => array( 'maxLength', 1000),
            'message' => '1000文字以内で入力してください'
        )
    );

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