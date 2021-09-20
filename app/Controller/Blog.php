<?php
namespace App\Controller;

use App\Model\Eloquent\Message;
use App\Model\Eloquent\User;
use Base\AbstractController;
use Base\Request;
use Base\TwigView;
use Base\View;

class Blog extends AbstractController  {

	public function indexAction() {

		$check = true;

		$data['msg'] = '';
		$data['items'] = '';

		if( ! $this->getUserId() ){
			$this->redirect('login');
		}

		$messages = Message::getList( $this->getUserId() );
		if ($messages) {
			$data['items'] = $messages;
			$data['user'] = User::getNameByID( $this->getUserId() );
			$data['isAdmin'] = User::isAdmin( $this->getUserId() );
		}

		$text = Request::post('text');

		if ( ! $text && $text !== null ) {
			$data['msg'] = 'Сообщение не может быть пустым';
			$check = false;
		}

		if ( $text && $check ) {
			$message = new Message([
				'text' => $text,
				'author_id' => $this->getUserId(),
				'created_at' => date('Y-m-d H:i:s')
			]);

			if ( isset( $_FILES['image']['tmp_name'] ) ) {
				$message->loadFile($_FILES['image']['tmp_name']);
			}

			$message->save();
			$this->redirect('blog');
		}

		return View::render( 'Blog/index.phtml', $data );
	}

	public function twigAction() {
		$data['msg'] = 'Hello Twig';
		return TwigView::render( 'Blog/test.twig', $data );
	}

	public function deleteAction() {
		$id = Request::get('id');

		if( $id && User::isAdmin( $this->getUserId() ) ){
			$messageID = htmlspecialchars( $_GET['id'] );
			Message::deleteMessage($messageID);
			$this->redirect('blog');
		}
	}
}