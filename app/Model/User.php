<?php
// パスワードハッシュ化の準備
App::uses('AppModel', 'Model');
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');


class User extends AppModel{
	public $name = 'User'; // エイリアス的なもの

	// バリデーション
	public $validate = array(
        'nickname' => array(
        	'allowEmpty' => false,
            'rule' => array('maxLength', 20),
            'message' => '20文字以内で入力してください'
        ),
        'introduce' => array(
        	'allowEmpty' => true, // 空欄でもよい
            'rule' => array( 'maxLength', 250),
            'message' => '250文字以内で入力してください'
        ),
        'email' => array(
			'rule' => 'isUnique',
			"message" => "このメールアドレスは既に登録されています"
		),
		'password' => array(
			'rule' => 'notEmpty'
		),
		'gender' => array(
			'rule' => 'notEmpty'
		),
		'avatar' => array(
			'allowEmpty' => true,
			'rule' => array('fileSize', '<=', '2MB'),
        	'message' => '画像は2MB以内でお願いします'
    	)
    );

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

/**
 * 画像アップロード機能の設定
 */
    // public $actsAs = array(
    //     'UploadPack.Upload' => array(
    //         'img' => array( // ここでは、"_file_name"を除いたカラム名を書く
    //             'quality' => 95, // 画質指定 デフォルトでは75
    //             'styles' => array(
    //                 'thumb' => '120x120' // リサイズしたいサイズ
    //             ),
    //             'default_url' => 'noimage.gif' // デフォルト画像をwebroot/imgから読み込む
    //         )
    //     )
    // );

    public $actsAs = array(
        'UploadPack.Upload' => array(
            'avatar' => array( // ここでは、"_file_name"を除いたカラム名を書く
            	'default_url' => 'noimage.gif', // デフォルト画像をwebroot/imgから読み込む
                'quality' => 95, // 画質指定 デフォルトでは75
	            'styles' => array(
	                'thumb' => '120x120', // リサイズしたいサイズ
	                'mini' => '40x40'
	            )
            )
        )
    );

/**
 * パスワードのハッシュ化
 */
	public function beforeSave($options = array()){
		if(isset($this->data[$this->alias]['password'])){
			$passwordHasher = new SimplePasswordHasher();
			$this->data[$this->alias]['password'] = $passwordHasher->hash($this->data[$this->alias]['password']);
		}
		return true;
	}
}


