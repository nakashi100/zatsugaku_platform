<?php
class ArticlesController extends AppController{
	public $uses = array('Article', 'Like', 'Comment', 'Favorite', 'User'); // Controlle内で他のModel(table)を利用できるようにする
	public $helpers = array('Html', 'Form', 'Session', 'UploadPack.Upload'); // viewの拡張機能を呼び出す
	public $components = array('Session', 'Paginator'); // Controllerの拡張機能を呼び出す
	public $paginate_new = array( // Paginatorの設定
		'limit' => 10,
		'order' => array(
			'Article.created' => 'desc' // 新着順
		)
	);
	public $paginate_likes = array(
		'limit' => 10,
		'order' => array(
			'Article.likes' => 'desc' // 人気順
		)
	);

	public function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow('index', 'detail');

		// 済・・・'edit', 'delete','create','like', 'favorite','reseteLike', resetFavorite'
		// 未・・・'deleteComment', はauthor
	}
/**
 * 権限設定
 */
	public function isAuthorized($user = null){
		// ログインユーザーならばだれでも可能
		if(in_array($this->action, array('create', 'like', 'favorite'))){
			return true;
		}

		// オーナーのみ可能
		if(in_array($this->action, array('edit', 'delete'))){
			$articleId = (int) $this->request->params['pass']['0'];
			$article = $this->Article->findById($articleId);
			$articleUserId = $article['Article']['user_id'];

			if($articleUserId == $user['id']){
				return true;
			}
		}

		if(in_array($this->action, array('resetLike', 'resetFavorite'))){
			$userId = (int) $this->request->parms['pass']['0'];

			return true;
		}

		if($this->action === 'deleteComment'){
			$commentId = (int) $this->request->params['pass']['0'];
			$comment = $this->Comment->findById($commentId);
			$commentUserId = $comment['Comment']['user_id'];

			if($commentUserId == $user['id']){
				return true;
			}
		}

		return parent::isAuthorized($user);
	}

	public function index(){
		///////////  ソート内容によってページネーションの設定を変更する　///////////
		if( ($this->request->query('sort') == 1) || !($this->request->query('sort')) ){
			$this->Paginator->settings = $this->paginate_new;
			$this->set('sort_flag', 1);
		}

		if($this->request->query('sort') == 2){
			$this->Paginator->settings = $this->paginate_likes;
			$this->set('sort_flag', 2);
		}

		///////////			カテゴリ指定・検索内容の有無			///////////
		if($this->request->query('category_id')){ // getの値を取得するコマンド
			$category_id = $this->request->query('category_id');
			$articles = $this->Paginator->paginate('Article', array('Article.del_flg' => '0', 'Article.category_id' => $category_id)); // アソシエーションによりdel_flgが２つ存在するので「モデル名.del_flg」で指定

			$category= $this->Category->findById($category_id);
			$category_name = $category['Category']['category_name'];
			$this->set('category_name', $category_name);
		}

		if(!($this->request->query('category_id'))){ // getで値が取得できない場合(ALL)、category_id=0の場合もここで処理される?
			$category_id = 0;
			$articles = $this->Paginator->paginate('Article', array('Article.del_flg' => '0'));
		}

		if($this->request->query('search_word')){
			$search_word = $this->request->query('search_word');
			$articles = $this->Paginator->paginate('Article', array('Article.del_flg' => '0', 'OR' => array ('Article.title LIKE' => '%'.$search_word.'%', 'Article.detail LIKE' => '%'.$search_word.'%', 'User.nickname LIKE' => '%'.$search_word.'%')));
			$this->set('search_word', $search_word);
		}

		if($this->request->query('favorites')){
			$category_id = 0;
			$user_id = $this->request->query('favorites');

			$article_id_list = $this->Favorite->find('list', array('fields' => 'Favorite.article_id', 'conditions' => array('Favorite.user_id' => $user_id)));

			$articles = $this->Paginator->paginate('Article', array('Article.del_flg' => '0', 'Article.id' => array_values($article_id_list)));
		}


		///////////  Viewにデータを渡す　///////////
		$this->set('articles', $articles);
		$this->set('category_id', $category_id);
	}

	public function detail($id = null){ // このidはarticleのid
		if(!$id){
			throw new NotFoundException(__('申し訳ございませんが、このURLは無効です'));
		}

		$article = $this->Article->findById($id);
		if(!$article || $article['Article']['del_flg'] == ( 1 or 2)){
			throw new NotFoundException(__('申し訳ございませんが、このURLは無効です'));
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

		// 該当ユーザーの該当記事へのいいねの有無を調べてviewに渡す
		$loginUser = $this->Auth->user();
		$like = $this->Like->findAllByUserIdAndArticleId($loginUser['id'], $id);
		$this->set('like', $like);

		// 該当ユーザーの該当記事へのお気に入りの有無を調べてviewに渡す
		$favorite = $this->Favorite->findAllByUserIdAndArticleId($loginUser['id'], $id);
		$this->set('favorite', $favorite);
	}

	public function deleteComment($id){
		if($this->request->is('get')){
			throw new MethodNotAllowedException(__('このページは無効です'));
		}

		$data = array('Comment' => array('id' => $id, 'del_flg' => 1)); // 更新する内容を設定
		$fields = array('del_flg'); // 更新する項目(フィールド指定)
		if($this->Comment->save($data, false, $fields)){
			$this->Session->setFlash(__('このコメントは削除されました', h($id)));
			return $this->redirect($this->referer());
		}
	}

	public function create(){
		if($this->request->is('post')){
			if(isset($this->request->data['post'])){
				$this->Article->save($this->request->data);
				$this->Session->setFlash(__('あなたの雑学が投稿されました'));
				return $this->redirect(array('action' => 'index'));
			}
			elseif(isset($this->request->data['save'])){
				$save_data = $this->request->data;
				$save_data['Article']['del_flg'] = '2'; // 下書きの場合はdel_flgを2に変更する
				$this->Article->save($save_data); // DBに保存
				$this->Session->setFlash(__('雑学が下書きに保存されました'));
				return $this->redirect(array('controller' => 'Users', 'action' => 'view', $save_data['Article']['user_id']));
			}else{
				$this->Session->setFlash(__('雑学の編集に失敗しました'));
			}
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
			$this->set('article', $article);
		}

		//編集ボタンが押された場合に、DBへの保存処理を行う
		if($this->request->is(array('post', 'put'))){
			$this->Article->set($this->request->data);
			$this->Article->id = $id;

				if($this->Article->validates()){ // バリデーション
					if(isset($this->request->data['finish'])){
						$finish_data = $this->request->data;
						$finish_data['Article']['del_flg'] = '0';
						$this->Article->save($finish_data);
						$this->Session->setFlash(__('編集した雑学が投稿されました'));
						return $this->redirect(array('action' => 'index'));
					}elseif(isset($this->request->data['save'])){
						$save_data = $this->request->data;
						$save_data['Article']['del_flg'] = '2'; // 下書きflag
						$this->Article->save($save_data);
						$this->Session->setFlash(__('雑学が下書きに保存されました'));
						return $this->redirect(array('controller' => 'Users', 'action' => 'view', $save_data['Article']['user_id']));
					}
				}else{
					// バリデーションが通らなかった場合
					$this->Session->setFlash(__('雑学の編集に失敗しました'));
					$this->set('article', $article);
				}
		}
	}

	public function delete($id = null){
		if($this->request->is('get')){
			throw new MethodNotAllowedException(__('このページは無効です'));
		}

		$data = array('Article' => array('id' => $id, 'del_flg' => '1')); // 更新する内容を設定
		$fields = array('del_flg'); // 更新する項目(フィールド指定)
		if($this->Article->save($data, false, $fields)){
			$this->Session->setFlash(__('この雑学は削除されました', h($id)));
			$loginUserId = $this->Auth->user('id');
			return $this->redirect(array('controller' => 'Users', 'action' => 'view', $loginUserId));
		}
	}

	public function like($article_id = null, $user_id = null){ // このidはarticleのid
		if($this->request->is('get')){
			throw new MethodNotAllowedException();
		}

		$article = $this->Article->findById($article_id);

		// likeテーブルに追加する
		$data = array('Like' => array('article_id' => $article_id, 'user_id' => $user_id)); // 更新する内容を設定
		$fields = array('article_id', 'user_id'); // 登録する項目(フィールド指定)
		$this->Like->save($data, false, $fields); // 登録

		// articleテーブルを変更する
		$data2= array('Article' => array('id' => $article_id, 'likes' => $article['Article']['likes']+1));
		$fields2 = array('likes');
		$this->Article->save($data2, false, $fields2);

		$this->Session->setFlash(__('この記事をイイネしました!'));
		return $this->redirect($this->referer());
	}

	public function resetLike($article_id = null, $user_id =null){
		if($this->request->is('get')){
			throw new MethodNotAllowedException();
		}

		// likeテーブルからdataを削除する
		$like = $this->Like->findByArticleIdAndUserId($article_id, $user_id);
		$like_id = $like['Like']['id'];
		$this->Like->delete($like_id);

		// articleテーブルを変更する
		$article = $this->Article->findById($article_id);
		$data = array('Article' => array('id' => $article_id, 'likes' => $article['Article']['likes']-1)); // 更新する内容
		$fields = array('likes'); // 更新する項目
		$this->Article->save($data, false, $fields);

		$this->Session->setFlash(__('この記事へのイイネを取り消しました'));
		return $this->redirect($this->referer());
	}

	public function favorite($article_id = null, $user_id = null){ // このidはarticleのid
		if($this->request->is('get')){
			throw new MethodNotAllowedException();
		}

		// favoriteテーブルに追加する
		$data = array('Favorite' => array('article_id' => $article_id, 'user_id' => $user_id)); // 更新する内容
		$fields = array('article_id', 'user_id'); // 登録する項目(フィールド指定)
		$this->Favorite->save($data, false, $fields); // 登録

		$this->Session->setFlash(__('この記事をお気に入りに登録しました!'));
		return $this->redirect($this->referer());
	}

	public function resetFavorite($article_id = null, $user_id =null){
		if($this->request->is('get')){
			throw new MethodNotAllowedException();
		}

		// favoiteテーブルからdataを削除する
		$favorite = $this->Favorite->findByArticleIdAndUserId($article_id, $user_id);
		$favorite_id = $favorite['Favorite']['id'];
		if($this->Favorite->delete($favorite_id)){
			$this->Session->setFlash(__('この記事へのお気に入り登録を取り消しました'));
			return $this->redirect($this->referer());
		}
	}

}