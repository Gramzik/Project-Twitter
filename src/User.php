<?php


//obiekt classy user odpowiada jendemu wierszowi w tabeli users
//wlasciwosci identyczne jak kolumny tabeli
class User
{
    /**
     * @var int
     */
    private $id; //tylko getter poniewaz nikt z zewnatrz nie moze nadpisac ID
    /**
     * @var string
     */
    private $username;
    /**
     * @var string
     */
    private $email;
    /**
     * @var string
     */
    private $hashPass;

    public function __construct()
    {
        $this->id = -1;
        $this->username = '';
        $this->email = '';
        $this->hashPass = '';
    }


    public function saveToDB(PDO $conn)
    {
        //sprawdzamy czy jest to nowy obiekt
        //czyli id = -1
        if ($this->id === -1) {
            //zapisujemy nowy rekord do bazy
            try {
                $stmt = $conn->prepare("INSERT INTO Users SET username=:username, email=:email, hashPass=:hashPass");
                $stmt->execute(['username' => $this->username, 'email' => $this->email, 'hashPass' => $this->hashPass]);
            } catch (PDOException $e) {
                echo $e->getMessage();
            }

            //aktualizujemy ID naszego obiektu z -1 na aktualnie dodane ID
            //wtedy wiadomo ze obiekt nie jest nowy bo nie ma id -1
            $this->id = $conn->lastInsertId();
        } else {
            $stmt = $conn->prepare(
                'UPDATE Users SET username=:username, email=:email, hashPass=:hashPass WHERE id=:id');
            $result = $stmt->execute(
                ['username' => $this->username,
                    'email' => $this->email,
                    'hashPass' => $this->hashPass,
                    'id' => $this->id,
                ]);
            if ($result === true) {
                return true;
            }
        }

    }

    public function updatePassword(PDO $conn)
    {
        $stmt = $conn->prepare('UPDATE Users SET hashPass =:hashPass WHERE id=:id');
        $result = $stmt->execute(
            ['id' => $this->id,
                'hashPass' => $this->hashPass,
                ]);
        if ($result === true){
            return true;
        }
    }

    static public function loadUserById(PDO $conn, $id): ?User
    {
        $stmt = $conn->prepare('SELECT * FROM Users WHERE id=:id');
        $result = $stmt->execute(['id' => $id]);
        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $loadedUser = new User();
            $loadedUser->id = $row['id'];
            $loadedUser->username = $row['username'];
            $loadedUser->hashPass = $row['hashPass'];
            $loadedUser->email = $row['email'];
            return $loadedUser;
        }
        return null;
    }


    static public function loadAllUsers(PDO $conn)
    {
        $ret = [];
        $sql = "SELECT * FROM Users";
        $result = $conn->query($sql);
        if ($result !== false && $result->rowCount() != 0) {
            foreach ($result as $row) {
                $loadedUser = new User();
                $loadedUser->id = $row['id'];
                $loadedUser->username = $row['username'];
                $loadedUser->hashPass = $row['hashPass'];
                $loadedUser->email = $row['email'];
                $ret[] = $loadedUser;
            }
        }
        return $ret;
    }


    public function delete(PDO $conn)
    {
        if ($this->id != -1) {
            $stmt = $conn->prepare('DELETE FROM Users WHERE id=:id');
            $result = $stmt->execute(['id' => $this->id]);
            if ($result === true) {
                $this->id = -1;
                return true;
            }
            return false;
        }
        return true;
    }


    static public function loadUserByEmail(PDO $conn, $email): ?User
    {
        $stmt = $conn->prepare('SELECT * FROM Users WHERE email=:email');
        $result = $stmt->execute(['email' => $email]);
        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $loadedUser = new User();
            $loadedUser->id = $row['id'];
            $loadedUser->username = $row['username'];
            $loadedUser->hashPass = $row['hashPass'];
            $loadedUser->email = $row['email'];
            return $loadedUser;
        }
        return null;
    }


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getHashPass(): string
    {
        return $this->hashPass;
    }

    /**
     * @param string $hashPass
     */
    public function setHashPass(string $hashPass): void
    {
        //hashujemy haslo
        //nigdy nie przechowujemy w plain text
        $hashPass = password_hash($hashPass, PASSWORD_BCRYPT);
        $this->hashPass = $hashPass;
    }
}