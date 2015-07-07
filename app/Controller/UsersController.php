<?php

class UsersController extends AppController{
	public $uses = array('User', 'Article', 'Favorite'); // Controlle内で他のModel(table)を利用できるようにする
	public $helpers = array('Html', 'Form', 'Session', 'UploadPack.Upload'); // viewの拡張機能を呼び出す
	public $components = array('Session', 'Paginator'); // Controllerの拡張機能を呼び出す
	public $paginate = array(
		'limit' => 5,
		'order' => array(
			'created' => 'desc'
		)
	);

	public function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow('view', 'login', 'logout', 'signup'); // 全てのユーザーがアクセス可能
	}

/**
 * 権限設定
 */
	public function isAuthorized($user = null){
		// オーナーのみ可能
		if(in_array($this->action, array('edit'))){
			$userId = (int) $this->request->params['pass'][0];
			if($userId == $user['id']){
				return true;
			}
		}
		return parent::isAuthorized($user);
	}

	public function view($id = null, $favorites = null){
		if(!$id){
			throw new NotFoundException(__('申し訳ございませんが、このURLは無効です'));
		}

		$user = $this->User->findById($id);
		if(!$user || $user['User']['del_flg'] == 1){
			throw new NotFoundException(__('申し訳ございませんが、このURLは無効です'));
		}

		$this->set('user', $user);
		$loginUser = $this->Auth->user();

		// 該当ユーザーの投稿した雑学一覧をpaginateした上でviewに渡す
		if(!$favorites){
			// 他ユーザーのマイページで下書き中の雑学を見れないようにするために条件分けする
			if($id == $loginUser['id']){
				$this->Paginator->settings = $this->paginate;
				$articles = $this->Paginator->paginate('Article', array('Article.user_id' => $id, 'Article.del_flg' => array(0, 2)));
				$this->set('articles', $articles);
				$this->set('favorites_flag', 0);
			}elseif($id != $loginUser['id']){
				$this->Paginator->settings = $this->paginate;
				$articles = $this->Paginator->paginate('Article', array('Article.user_id' => $id, 'Article.del_flg' => '0'));
				$this->set('articles', $articles);
				$this->set('favorites_flag', 0);
			}

		}

		// お気に入りした雑学一覧をpaginateした上でviewに渡す
		if(isset($favorites) && $favorites == 1){
			$this->Paginator->settings = $this->paginate;
			$article_id_list = $this->Favorite->find('list', array('fields' => 'Favorite.article_id', 'conditions' => array('Favorite.user_id' => $id)));
			$articles = $this->Paginator->paginate('Article', array('Article.del_flg' => '0', 'Article.id' => array_values($article_id_list)));
			$this->set('articles', $articles);
			$this->set('favorites_flag', 1);
		}

		// 投稿した雑学の数をviewに渡す
		$count_posted_articles = $this->Article->find('count', array('conditions' => array('Article.user_id' => $id, 'Article.del_flg' => '0')));
		$this->set('count_posted_articles', $count_posted_articles);

		// 下書きしている雑学の数をviewに渡す
		$count_saved_articles = $this->Article->find('count', array('conditions' => array('Article.user_id' => $id, 'Article.del_flg' => '2')));
		$this->set('count_saved_articles', $count_saved_articles);

		// お気に入りした雑学の数をviewに渡す
		$count_favorite_articles = $this->Favorite->find('count', array('conditions' => array('Favorite.user_id' => $id, 'Article.del_flg' => '0')));
		$this->set('count_favorite_articles', $count_favorite_articles);
	}

	public function edit($id = null){
		$user = $this->User->findById($id);
		$this->set('user', $user);

		if(!$id || !$user){
			throw new NotFoundException(__('このページは存在しません'));
		}

		// editページにアクセスした際にフォームにデータをセットしておく
		if(!$this->request->data){
			$this->request->data = $user;
		}

		//編集ボタンが押された場合に、DBへの保存処理を行う
		if($this->request->is(array('post', 'put'))){
			$viewId = $id;
			$this->User->id = $id;
			if($this->User->save($this->request->data)){
				$this->Session->setFlash(__('ユーザー情報が編集されました'));
				return $this->redirect(array('action' => 'view', $viewId));
			}
			$this->Session->setFlash(__('ユーザー情報の編集に失敗しました'));
		}
	}

	public function signup(){
		if($this->request->is('post')){
			$this->User->create();
			if($this->User->save($this->request->data)){
				$this->Auth->login();
				return $this->redirect(array('controller' => 'Articles', 'action' => 'index')); // 実際にはapprovalに飛ばしてauth処理を行う
			}else{
				$this->Session->setFlash(__('ユーザー登録に失敗しました。'));
			}
		}
	}

	public function login($id = null){
		if($this->request->is('post')){
			if($this->Auth->login()){ //$this->request->dataの値を使用してログインする規約になっている
				if(isset($id)){
					return $this->redirect(array('controller' => 'articles', 'action' => 'detail', $id));
				}else{
					return $this->redirect(array('controller' => 'articles', 'action' => 'index'));
				}
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