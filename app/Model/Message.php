<?php
namespace App\Model;

use Base\Db;

class Message {
    private $id;
    private $text;
    private $createdAt;
    private $authorId;
    private $author;
    private $image;

    public function __construct( $data ) {
        $this->text = $data['text'];
        $this->createdAt = $data['created_at'];
        $this->authorId = $data['author_id'];
        $this->image = $data['image'] ?? '';
    }

    public static function deleteMessage( $messageId ) {
        $db = Db::getInstance();
        $query = "DELETE FROM messages WHERE id = $messageId";
        return $db->exec($query, __METHOD__);
    }

    public function save() {
        $db = Db::getInstance();
        $res = $db->exec(
            'INSERT INTO messages (
                    text, 
                    created_at,
                    author_id,
                    image
                    ) VALUES (
                    :text, 
                    :created_at,
                    :author_id,
                    :image
                )',
            __FILE__,
            [
                ':text' => $this->text,
                ':created_at' => $this->createdAt,
                ':author_id' => $this->authorId,
                ':image' => $this->image,
            ]
        );

        return $res;
    }

    public static function getList( $limit = 10, $offset = 0 ) {
        $db = Db::getInstance();
        $data = $db->fetchAll(
            "SELECT * fROM messages LIMIT $limit OFFSET $offset",
            __METHOD__
        );
        if (!$data) {
            return [];
        }

        $messages = [];
        foreach ($data as $elem) {
            $message = new self($elem);
            $message->id = $elem['id'];
            $messages[] = $message;
        }

        return $messages;
    }

	public static function getUserMessages( $userId, $limit ) {
		$db = Db::getInstance();
		$data = $db->fetchAll(
			"SELECT * fROM messages WHERE author_id = $userId LIMIT $limit",
			__METHOD__
		);
		if (!$data) {
			return [];
		}

		$messages = [];
		foreach ($data as $elem) {
			$message = new self($elem);
			$message->id = $elem['id'];
			$messages[] = $message;
		}

		return $messages;
	}

    public function getId() {
        return $this->id;
    }

    public function getText() {
        return $this->text;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function getAuthorId() {
        return $this->authorId;
    }

    public function getAuthorName($author_id) {
		$db = Db::getInstance();
		$data = $db->fetchOne(
			"SELECT * fROM users WHERE id = :id",
			__METHOD__,
			[':id' => $author_id]
		);

		if (!$data) {
			return null;
		}

		return $data['name'];
    }

    public function loadFile( $file ) {
        if (file_exists($file)) {
            $this->image = $this->genFileName();
            move_uploaded_file($file,getcwd() . '/images/' . $this->image);
        }
    }

    private function genFileName() {
        return sha1(microtime(1) . mt_rand(1, 100000000)) . '.jpg';
    }

    public function getImage() {
        return $this->image;
    }

    public function getData() {
        return [
            'id' => $this->id,
            'author_id' => $this->authorId,
            'text' => $this->text,
            'created_at' => $this->createdAt,
            'image' => $this->image
        ];
    }
}