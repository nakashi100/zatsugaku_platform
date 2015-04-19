<?php
class ArticlesController extends AppController{
	public $uses = array('Article', 'Like', 'Comment'); // Controlle内で他のModel(table)を利用できるようにする
	public $helpers = array('Html', 'Form', 'Session'); // viewの拡張機能を呼び出す
	public $components = array('Session'); // Controllerの拡張機能を呼び出す

	public function index(){
		// articlsデータをviewに渡す
		// $articles = $this->Article->find('all', array('order' => array('created' => 'DESC')));
		$articles = $this->Article->find('all');
		$this->set('articles', $articles);

		$likes = $this->Like->find('all');
		$this->set('likes', $likes);
	}

	public function detail($id = null){ // このidはarticleのid
		if(!$id){
			throw new NotFoundException(__('このページは存在しません'));
		}

		$article = $this->Article->findById($id);
		if(!$article){
			throw new NotFoundException(__('データがありません'));
		}
		$this->set('article', $article);

		// 該当記事に関するコメントをviewに渡す
		$comments = $this->Comment->getComments($id);
		$this->set('comments', $comments);

		// コメント処理を行う
		if($this->request->is('post')){
			$this->Comment->create();
			if($this->Comment->save($this->request->data)){
				$this->Session->setFlash(__('コメントが反映されました'));
				return $this->redirect($this->referer()); // いまいるページにリダイレクトする
			}
			$this->Session->setFlash(__('コメントできませんでした'));
		}
	}

	public function deleteComment($id){
		if($this->request->is('get')){
			throw new MethodNotAllowedException(__('このページは無効です'));
		}

		$data = array('Comment' => array('id' => $id, 'del_flg' => 1)); // 更新する内容を設定
		$fields = array('del_flg'); // 更新する項目(フィールド指定)
		if($this->Comment->save($data, false, $fields)){
			$this->Session->setFlash(__('このコメント(id=%s)は削除されました', h($id)));
			return $this->redirect($this->referer());
		}
	}

	public function create(){
		if($this->request->is('post')){
			if($this->Article->save($this->request->data)){
				$this->Session->setFlash(__('あなたの雑学が投稿されました'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('雑学の投稿に失敗しました'));
		}
	}

	public function edit($id = null){
		if(!$id){
			throw new NotFoundException(__('このページは存在しません'));
		}

		$article = $this->Article->findById($id);
		if(!$article){
			throw new NotFoundException(__('データがありません'));
		}

		// editページにアクセスした際にフォームにデータをセットしておく
		if(!$this->request->data){
			$this->request->data = $article;
		}

		//編集ボタンが押された場合に、DBへの保存処理を行う
		if($this->request->is(array('post', 'put'))){
			$this->Article->id = $id;
			if($this->Article->save($this->request->data)){
				$this->Session->setFlash(__('雑学が編集されました'));
				return $this->redirect(array('action' => 'detail', $id));
			}
			$this->Session->setFlash(__('雑学の編集に失敗しました'));
		}
	}

	public function delete($id = null){
		if($this->request->is('get')){
			throw new MethodNotAllowedException(__('このページは無効です'));
		}

		$data = array('Article' => array('id' => $id, 'del_flg' => '1')); // 更新する内容を設定
		$fields = array('del_flg'); // 更新する項目(フィールド指定)
		if($this->Article->save($data, false, $fields)){
			$this->Session->setFlash(__('この雑学(id=%s)は削除されました', h($id)));
			return $this->redirect(array('action' => 'index'));
		}
	}
}



