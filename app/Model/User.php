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

	public $validate = array(
		'email' => array(
			'rule' => 'notEmpty'
		),
		'password' => array(
			'rule' => 'notEmpty'
		),
		'nickname' => array(
			'rule' => 'notEmpty'
		),
		'gender' => array(
			'rule' => 'notEmpty'
		),
		'birthday' => array(
			'rule' => 'date',
			'allowEmpty' => true
		),
		'gender' => array(
			'rule' => 'notEmpty'
		)
	);


}


