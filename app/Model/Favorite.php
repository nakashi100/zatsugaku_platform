<?php
class Favorite extends AppModel{
	public $name = 'Favorite'; // エイリアス的なもの

	// Favorite(多数)にArticle(1つ)をbelongstoでアソシエーションする
	public $belongsTo = array(
		'Article' => array( // Categoryは配列の引数になる
			'className' => 'Article',
			'foreignKey' => 'article_id'
		)
	);
}