<?php /** @noinspection ALL */


class Comment
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $userId;

    /**
     * @var int
     */
    private $postId;

    /**
     * @var string
     */
    private $createDate;

    /**
     * @var string
     */
    private $text;

    /**
     * @var string
     */
    private $userName;

    public function __construct()
    {
        $this->id = -1;
        $this->userId = 0;
        $this->postId = 0;
        $this->createDate;
        $this->text = '';
    }

    public static function loadCommentById($conn, $id): ?Comment
    {
        $stmt = $conn->prepare('SELECT * FROM Comments WHERE id=:id');
        $result = $stmt->execute(['id' => $id]);
        if ($result === true && $stmt->rowCount() > 0) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {
                $loadedComment = new Comment();
                $loadedComment->id = $row['id'];
                $loadedComment->userId = $row['userId'];
                $loadedComment->postId = $row['postId'];
                $loadedComment->createDate = $row['createDate'];
                $loadedComment->text = $row['text'];
                return $loadedComment;
            }
        }
        return null;
    }

    public static function loadCommentsByPostId($conn, $postId)
    {
        $ret = [];
        $stmt = $conn->prepare('SELECT * FROM Comments WHERE postId=:postId');
        $result = $stmt->execute(['postId' => $postId]);
        if ($result === true && $stmt->rowCount() > 0) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {
                $loadedComment = new Comment();
                $loadedComment->id = $row['id'];
                $loadedComment->userId = $row['userId'];
                $loadedComment->postId = $row['postId'];
                $loadedComment->createDate = $row['createDate'];
                $loadedComment->text = $row['text'];
                $ret[] = $loadedComment;
            }
            return $ret;
        }
        return $ret;
    }

    public static function loadCommentsByPostIdWithUsers($conn, $postId)
    {
        $ret = [];
        $stmt = $conn->prepare('SELECT Comments.*, Users.username FROM Comments JOIN Users ON Comments.userId = Users.id WHERE postId =:postId ORDER BY createDate DESC');
        $result = $stmt->execute(['postId' => $postId]);
        if ($result === true && $stmt->rowCount() > 0) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {
                $loadedComment = new Comment();
                $loadedComment->id = $row['id'];
                $loadedComment->userId = $row['userId'];
                $loadedComment->postId = $row['postId'];
                $loadedComment->createDate = $row['createDate'];
                $loadedComment->text = $row['text'];
                $loadedComment->userName = $row['username'];
                $ret[] = $loadedComment;
            }
            return $ret;
        }
        return $ret;
    }

    public function saveToDB(PDO $conn)
    {
        //sprawdzamy czy jest to nowy obiekt
        //czyli id = -1
        if ($this->id === -1) {
            //zapisujemy nowy rekord do bazy
            try {
                $stmt = $conn->prepare("INSERT INTO Comments SET userId=:userId, postId=:postId, createDate=:createDate, text=:text");
                $stmt->execute(
                    ['userId' => $this->userId,
                        'postId' => $this->postId,
                        'createDate' => $this->createDate,
                        'text' => $this->text]);
            } catch (PDOException $e) {
                echo $e->getMessage();
            }

            //aktualizujemy ID naszego obiektu z -1 na aktualnie dodane ID
            //wtedy wiadomo ze obiekt nie jest nowy bo nie ma id -1
            $this->id = $conn->lastInsertId();
        } else {
            $stmt = $conn->prepare(
                'UPDATE Comments SET userId=:userId, postId=:post, createDate=:createDate, text=:text WHERE id=:id');
            $result = $stmt->execute(
                ['userId' => $this->userId,
                    'postId' => $this->postId,
                    'creationDate' => $this->createDate,
                    'text' => $this->text,
                    'id' => $this->id,
                ]);
            if ($result === true) {
                return true;
            }
        }

    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return int
     */
    public function getPostId(): int
    {
        return $this->postId;
    }

    /**
     * @param int $postId
     */
    public function setPostId(int $postId): void
    {
        $this->postId = $postId;
    }

    /**
     * @return string
     */
    public function getCreateDate(): string
    {
        return $this->createDate;
    }

    /**
     * @param string $createDate
     */
    public function setCreateDate(string $createDate): void
    {
        $this->createDate = $createDate;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getUserName(): string
    {
        return $this->userName;
    }

    /**
     * @param string $userName
     */
    public function setUserName(string $userName): void
    {
        $this->userName = $userName;
    }
}