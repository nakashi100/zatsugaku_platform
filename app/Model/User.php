<?php
// パスワードハッシュ化の準備
App::uses('AppModel', 'Model');
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');


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

	// パスワードのハッシュ化
	public function beforeSave($options = array()){
		if(isset($this->data[$this->alias]['password'])){
			$passwordHasher = new SimplePasswordHasher();
			$this->data[$this->alias]['password'] = $passwordHasher->hash($this->data[$this->alias]['password']);
		}
		return true;
	}

}


