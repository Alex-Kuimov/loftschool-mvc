<?php
namespace App\Controller;

use App\Model\Eloquent\Message;
use Base\AbstractController;
use Base\Request;

class Api extends AbstractController {
    public function getUserMessagesAction() {

		$userId = Request::get('id');

        if ( ! $userId ) {
            return $this->response( ['error' => 'no_user_id'] );
        }

        $messages = Message::getUserMessages( $userId, 20 );

		if (!$messages) {
            return $this->response( ['error' => 'no_messages'] );
        }

        $data = array_map(function ( Message $message ) {
            return $message->getData();
        }, $messages);

        return $this->response( ['messages' => $data] );
    }

    public function response( $data ) {
        header('Content-type: application/json');
        return json_encode($data);
    }
}