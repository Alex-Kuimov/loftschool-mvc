<?php
namespace App\Model\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Message extends Model {
    protected $table = 'messages';
    public $timestamps = false;

    protected $fillable = [
        'text',
        'created_at',
        'author_id',
        'image',
    ];

    public static function deleteMessage( $messageId ) {
        return self::destroy($messageId);
    }

    public static function getList( $userId, $limit = 10, $offset = 0 ) {
        return self::query()->where('author_id', '=', $userId)
            ->limit($limit)
            ->offset($offset)
            ->orderBy('id', 'DESC')
            ->get();
    }

    public static function getUserMessages( $userId,  $limit ) {
        return self::query()->where('author_id', '=', $userId)->limit($limit)->get();
    }

	public function getAuthorName($userId) {
		return self::query()->select('name')->where('author_id', '=', $userId)->limit(1)->get();
	}

    public function getId() {
        return $this->id;
    }

    public function getText() {
        return $this->text;
    }

    public function getCreatedAt() {
        return $this->created_at;
    }

    public function getAuthorId() {
        return $this->authorId;
    }

    public function getAuthor() {
        return $this->author;
    }

    public function setAuthor(User $author) {
        $this->author = $author;
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