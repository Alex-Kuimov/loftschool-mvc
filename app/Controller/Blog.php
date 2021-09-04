<?php
namespace App\Controller;

use App\Model\Message;
use App\Model\User;
use Base\AbstractController;

class Blog extends AbstractController  {

	private $data = [];
	private $check = true;

	public function indexAction() {

		$this->data['msg'] = '';
		$this->data['items'] = '';

		if( ! $this->getUserId() ){
			$this->redirect('login');
		}

		$messages = Message::getList();
		if ($messages) {
			$this->data['items'] = $messages;
			$this->data['isAdmin'] = User::isAdmin($this->getUserId());
		}

		if ( isset( $_POST['text'] ) ) {

			$text = htmlspecialchars( $_POST['text'] );

			if ( ! $text ) {
				$this->data['msg'] = 'Сообщение не может быть пустым';
				$this->check = false;
			}

			$message = new Message([
				'text' => $text,
				'author_id' => $this->getUserId(),
				'created_at' => date('Y-m-d H:i:s')
			]);

			if ( isset( $_FILES['image']['tmp_name'] ) ) {
				$message->loadFile($_FILES['image']['tmp_name']);
			}

			if( $this->check ) {
				$message->save();
				$this->redirect('blog');
			}
		}

		return $this->render('Blog/index.phtml', $this->data);
	}

	public function deleteAction() {
		if( isset( $_GET['id'] ) && User::isAdmin( $this->getUserId() ) ){
			$messageID = htmlspecialchars( $_GET['id'] );
			Message::deleteMessage($messageID);
			$this->redirect('blog');
		}
	}
}