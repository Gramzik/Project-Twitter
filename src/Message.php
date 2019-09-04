<?php


class Message
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var int
     */
    private $senderId;
    /**
     * @var int
     */
    private $receiverId;
    /**
     * @var string
     */
    private $creationDate;
    /**
     * @var string
     */
    private $text;

    /**
     * @var string
     */
    private $theme;

    public function __construct()
    {
        $this->id = -1;
        $this->senderId = 0;
        $this->receiverId = 0;
        $this->creationDate = '';
        $this->text = '';
    }

    public static function loadMessageById($conn, $id)
    {
        $stmt = $conn->prepare('SELECT * FROM Messages WHERE id=:id');
        $result = $stmt->execute(['id' => $id]);
        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $loadedMessage = new Message();
            $loadedMessage->id = $row['id'];
            $loadedMessage->senderId = $row['senderId'];
            $loadedMessage->receiverId = $row['receiverId'];
            $loadedMessage->text = $row['text'];
            $loadedMessage->theme = $row['theme'];
            $loadedMessage->creationDate = $row['creationDate'];
            return $loadedMessage;
        }
        return null;
    }

    public static function LoadAllMessageByReceiverId($conn, $receiverId)
    {
        $ret = [];
        $stmt = $conn->prepare("SELECT * FROM Messages WHERE receiverId=:receiverId ORDER BY creationDate DESC");
        $result = $stmt->execute(['receiverId' => $receiverId]);
        if ($result === true && $stmt->rowCount() > 0) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {
                $loadedMessage = new Message();
                $loadedMessage->id = $row['id'];
                $loadedMessage->senderId = $row['senderId'];
                $loadedMessage->receiverId = $row['receiverId'];
                $loadedMessage->text = $row['text'];
                $loadedMessage->theme = $row['theme'];
                $loadedMessage->creationDate = $row['creationDate'];
                $ret[] = $loadedMessage;
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
                $stmt = $conn->prepare("INSERT INTO Messages SET senderId=:senderId, receiverId=:receiverId, creationDate=:creationDate, text=:text, theme=:theme");
                $stmt->execute(['senderId' => $this->senderId, 'receiverId' => $this->receiverId, 'creationDate' => $this->creationDate, 'text' => $this->text, 'theme'=>$this->theme]);
            } catch (PDOException $e) {
                echo $e->getMessage();
            }

            //aktualizujemy ID naszego obiektu z -1 na aktualnie dodane ID
            //wtedy wiadomo ze obiekt nie jest nowy bo nie ma id -1
            $this->id = $conn->lastInsertId();
        } else {
            $stmt = $conn->prepare(
                'UPDATE Messages SET senderId=:senderId, receiverId=:receiverId, creationDate=:creationDate, text=:text, theme=:theme WHERE id=:id');
            $result = $stmt->execute(
                ['senderId' => $this->senderId,
                    'receiverId' => $this->receiverId,
                    'text' => $this->text,
                    'creationDate' => $this->creationDate,
                    'id' => $this->id,
                    'theme' => $this->theme,
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
    public function getSenderId(): int
    {
        return $this->senderId;
    }

    /**
     * @param int $senderId
     */
    public function setSenderId(int $senderId): void
    {
        $this->senderId = $senderId;
    }

    /**
     * @return int
     */
    public function getReceiverId(): int
    {
        return $this->receiverId;
    }

    /**
     * @param int $receiverId
     */
    public function setReceiverId(int $receiverId): void
    {
        $this->receiverId = $receiverId;
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
    public function getTheme(): string
    {
        return $this->theme;
    }

    /**
     * @param string $theme
     */
    public function setTheme(string $theme): void
    {
        $this->theme = $theme;
    }
}