<?php

class Tweet
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
     * @var string
     */
    private $text;
    /**
     * @var string
     */
    private $creationDate;
    /**
     * @var string
     */
    private $userName;

    public function __construct()
    {
        $this->id = -1;
        $this->userId = 0;
        $this->text = '';
        $this->creationDate = '';
        $this->userName = '';
    }

    static public function loadTweetById($conn, $id): ?Tweet
    {
        $stmt = $conn->prepare('SELECT * FROM Twetts WHERE id=:id');
        $result = $stmt->execute(['id' => $id]);
        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $loadedTweet = new Tweet();
            $loadedTweet->id = $row['id'];
            $loadedTweet->userId = $row['userId'];
            $loadedTweet->text = $row['text'];
            $loadedTweet->creationDate = $row['creationDate'];
            return $loadedTweet;
        }
        return null;
    }

    static public function loadAllTweetsByUserId($conn, $userId)
    {
        $ret = [];
        $stmt = $conn->prepare("SELECT * FROM Twetts WHERE userId=:userId ORDER BY creationDate DESC");
        $result = $stmt->execute(['userId' => $userId]);
        if ($result === true && $stmt->rowCount() > 0) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {
                $loadedTweet = new Tweet();
                $loadedTweet->id = $row['id'];
                $loadedTweet->userId = $row['userId'];
                $loadedTweet->text = $row['text'];
                $loadedTweet->creationDate = $row['creationDate'];
                $ret[] = $loadedTweet;
            }
        }
        return $ret;
    }

    static public function loadAllTweets(PDO $conn)
    {
        $ret = [];
        $sql = "SELECT * FROM Twetts";
        $result = $conn->query($sql);
        if ($result !== false && $result->rowCount() != 0) {
            foreach ($result as $row) {
                $loadedTweet = new Tweet();
                $loadedTweet->id = $row['id'];
                $loadedTweet->userId = $row['userId'];
                $loadedTweet->text = $row['text'];
                $loadedTweet->creationDate = $row['creationDate'];
                $ret[] = $loadedTweet;
            }
        }
        return $ret;
    }

    static public function loadAllTweetsJoinUsers(PDO $conn)
    {
        $ret = [];
        $sql = "SELECT Twetts.*, Users.username FROM Twetts JOIN Users ON Twetts.userId = Users.id ORDER BY creationDate DESC;";
        $result = $conn->query($sql);
        if ($result !== false && $result->rowCount() != 0) {
            foreach ($result as $row) {
                $loadedTweet = new Tweet();
                $loadedTweet->id = $row['id'];
                $loadedTweet->userId = $row['userId'];
                $loadedTweet->text = $row['text'];
                $loadedTweet->creationDate = $row['creationDate'];
                $loadedTweet->userName = $row['username'];
                $ret[] = $loadedTweet;
            }
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
                $stmt = $conn->prepare("INSERT INTO Twetts SET userId=:userId, text=:text, creationDate=:creationDate");
                $stmt->execute(['userId' => $this->userId, 'text' => $this->text, 'creationDate' => $this->creationDate]);
            } catch (PDOException $e) {
                echo $e->getMessage();
            }

            //aktualizujemy ID naszego obiektu z -1 na aktualnie dodane ID
            //wtedy wiadomo ze obiekt nie jest nowy bo nie ma id -1
            $this->id = $conn->lastInsertId();
        } else {
            $stmt = $conn->prepare(
                'UPDATE Twetts SET userId=:userId, text=:text, creationDate=:creationDate WHERE id=:id');
            $result = $stmt->execute(
                ['userId' => $this->userId,
                    'text' => $this->text,
                    'creationDate' => $this->creationDate,
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
    public function getCreationDate(): string
    {
        return $this->creationDate;
    }

    /**
     * @param string $creationDate
     */
    public function setCreationDate(string $creationDate): void
    {
        $this->creationDate = $creationDate;
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