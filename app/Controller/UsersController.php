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
		
	}


}