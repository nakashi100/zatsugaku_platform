<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	public $uses = array('User', 'Category');

	// Authコンポーネントの利用を宣言
	public $components = array(
            'Session',
            'Auth' => array( //ログイン機能を利用する
                'authenticate' => array(
                    'Form' => array(
                    	'userModel' => 'User',
                    	'fields' => array('username' => 'email', 'password' => 'password')
                	)
                ),
                //ログイン後の移動先
                // 'loginRedirect' => array('controller' => 'articles', 'action' => 'index'),
                //ログアウト後の移動先
                // 'logoutRedirect' => array('controller' => 'users', 'action' => 'login'),
                //ログインページのパス
                'loginAction' => array('controller' => 'users', 'action' => 'login'), // ログインを扱うコントローラとアクションを表す
                //未ログイン時のメッセージ
                'authError' => 'あなたのメールアドレスとパスワードを入力して下さい。'
            )
    );

	public function beforeFilter(){
		$this->Auth->allow('index'); // 全てのコントローラのindexアクションでログインを必要としないように設定

		if($this->Auth->user()){
			$loginUser = $this->Auth->user();
			$this->set('loginUser', $loginUser);
		}

		$categories = $this->Category->find('all');
		$this->set('categories', $categories);

		if($this->request->query('search')){
				$search_word = $this->request->query('search');
				return $this->redirect(array('controller' => 'Articles' , 'action' => 'index' , '?' => array('search_word' => $search_word)));
		}
	
	}

}
