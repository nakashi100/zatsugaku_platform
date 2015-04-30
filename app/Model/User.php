<?php
class User extends AppModel{

	public $hasMany = array(
		'Article' => array(
			'className' => 'Article',
			'foreignKey' => 'user_id'
		),
		'Like' => array(
			'className' => 'Like',
			'foreignKey' => 'user_id'
		)
	);


}


