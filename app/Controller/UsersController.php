<?php
class UsersController extends AppController{
	public $uses = array('User', 'Article'); // Controlle内で他のModel(table)を利用できるようにする
	public $helpers = array('Html', 'Form', 'Session'); // viewの拡張機能を呼び出す
	public $components = array('Session'); // Controllerの拡張機能を呼び出す

	public function view($id = null){
		if(!$id){
			throw new NotFoundException(__('このページは存在しません'));
		}

		$user = $this->User->findById($id);
		if(!$user){
			throw new NotFoundException(__('データが存在しません'));
		}

		$this->set('user', $user);

		// 該当ユーザーの投稿した雑学一覧をviewに渡す
		$articles = $this->Article->getArticles($id);
		$this->set('articles', $articles);

	}




}