<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
$cakeVersion = __d('cake_dev', 'CakePHP %s', Configure::version())
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $cakeDescription ?>:
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->script( '//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js');
		
		echo $this->Html->meta('icon');

		echo $this->Html->css('cake.generic');
		echo $this->Html->css('common.css');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
</head>
<body>
	<div id="container">
		<div id="header">
			<div class="header__search">
				<form method="get">
					<input type="text" name="search" style="width:150px" placeholder="気になるワードを検索">
				</form>
			</div>

			<h1 class="header__title"><?php echo $this->Html->Link('雑学Platform', array('controller' => 'Articles', 'action' => 'index')); ?></h1>

			<div class="header__dropdownmenu">
				<ul>
					<?php if(isset($loginUser)): ?>
							<li><a href="#"><?php echo $loginUser['nickname']; ?></a>
								<ul>
									<li><?php echo $this->Html->Link('マイページ', array('controller' => 'Users', 'action' => 'view', $loginUser['id'])); ?></li>
									<li><?php echo $this->Html->Link('雑学を投稿する', array('controller' => 'Articles', 'action' => 'create')); ?></li>
									<li><?php echo $this->Html->Link('お気に入り雑学', array('controller' => 'Articles', 'action' => 'index', '?' => array('favorites' => $loginUser['id']))); ?></li>
									<li><?php echo $this->Html->Link('ログアウト', array('controller' => 'Users', 'action' => 'logout')); ?></li>
								</ul>
							</li>
					<?php else: ?>
							<li><a href="#"><?php echo $this->Html->Link('無料会員登録', array('controller' => 'Users', 'action' => 'signup')); ?></a></li>
							<li><a href="#"><?php echo $this->Html->Link('ログイン', array('controller' => 'Users', 'action' => 'login')); ?></a></li>
					<?php endif; ?>
				</ul>
			</div>


		</div>  <!-- id="header"閉じ -->

		<div id="content">

			<div class="leftColumn">

				<p class="leftColum__category"><?php echo $this->Html->Link('ALL',
										array(
											'controller' => 'Articles',
											'action' => 'index',
										)
									); ?></p>
				<?php foreach($categories as $category): ?>
					<p class="leftColum__category"><?php echo $this->Html->Link($category['Category']['category_name'],
										array(
											'controller' => 'Articles',
											'action' => 'index',
											'?' => array('category_id' => $category['Category']['id'])
										)
									); ?></p>
				<?php endforeach; ?>
			</div>

			<div class="mainColumn">
				<?php echo $this->Session->flash(); ?>
				<?php echo $this->fetch('content'); ?>
			</div>

			<div class="rightColumn">
			</div>

		</div>

		<div id="footer">
			<p align="center">フッターだよ</p>
		</div>

	</div>
	<?php // echo $this->element('sql_dump'); ?>
</body>
</html>
