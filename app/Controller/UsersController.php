<?php

class UsersController extends AppController{
	public $uses = array('User', 'Article'); // Controlle内で他のModel(table)を利用できるようにする
	public $helpers = array('Html', 'Form', 'Session'); // viewの拡張機能を呼び出す
	public $components = array('Session', 'Paginator'); // Controllerの拡張機能を呼び出す
	public $paginate = array(
		'limit' => 3,
		'order' => array(
			'created' => 'desc'
		)
	);

	public function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow('signup'); // ユーザー自身による登録とログアウトを許可する
	}

	public function view($id = null){
		if(!$id){
			throw new NotFoundException(__('このページは存在しません'));
		}

		$user = $this->User->findById($id);
		if(!$user){
			throw new NotFoundException(__('データが存在しません'));
		}

		$this->set('user', $user);

		// 該当ユーザーの投稿した雑学一覧をpaginateした上でviewに渡す
		$this->Paginator->settings = $this->paginate;
		$articles = $this->Paginator->paginate('Article', array('Article.user_id' => $id, 'Article.del_flg' => '0'));
		$this->set('articles', $articles);
	}

	public function edit($id = null){
		$user = $this->User->findById($id);

		if(!$id || !$user){
			throw new NotFoundException(__('このページは存在しません'));
		}

		// editページにアクセスした際にフォームにデータをセットしておく
		if(!$this->request->data){
			$this->request->data = $user;
		}

		//編集ボタンが押された場合に、DBへの保存処理を行う
		if($this->request->is(array('post', 'put'))){
			$this->User->id = $id;
			if($this->User->save($this->request->data)){
				$this->Session->setFlash(__('ユーザー情報が編集されました'));
				return $this->redirect(array('action' => 'view', $id));
			}
			$this->Session->setFlash(__('ユーザー情報の編集に失敗しました'));
		}
	}

	public function signup(){
		if($this->request->is('post')){
			$this->User->create();
			if($this->User->save($this->request->data)){
				return $this->redirect(array('controller' => 'Articles', 'action' => 'index')); // 実際にはapprovalに飛ばしてauth処理を行う
			}else{
				$this->Session->setFlash(__('ユーザー登録に失敗しました。'));
			}
		}
	}

	public function login(){
		if($this->request->is('post')){
			if($this->Auth->login()){
				return $this->redirect(array('controller' => 'articles', 'action' => 'index'));
			}else{
				$this->Session->setFlash(__('メールアドレスもしくはパスワードに間違いがあります'));
			}
		}

		// 直接URLを打ち込んだ場合の対応
		// else if ($this->Auth->user()) {
  //           $loginUser = $this->Auth->user();
  //           if ($loginUser['del_flg'] == 0 && $loginUser['role'] != 0) {
  //               return $this->redirect(array('controller' => 'events', 'action' => 'index'));
  //           } else if($loginUser['del_flg'] == 1) {
  //               return $this->redirect(array('action' => 'logout'));
  //           }
  //       }
	}

	public function logout(){
		$this->Auth->logout();
		$this->Session->destroy();
		return $this->redirect(array('action' => 'login'));
	}

}