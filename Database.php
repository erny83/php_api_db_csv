<?php
class Database {
    private $host = "localhost";
    private $db_name = "nome_database";
    private $username = "root";
    private $password = "";
    private $conn;

    public function __construct() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
    }

    public function save(string $table, array $data): bool {
        $columns = implode(", ", array_keys($data));
        $values = ":" . implode(", :", array_keys($data));

        $query = "INSERT INTO $table ($columns) VALUES ($values)";

        $stmt = $this->conn->prepare($query);

        foreach ($data as $key => &$val) {
            $stmt->bindParam(':' . $key, $val);
        }

        return $stmt->execute();
    }

    public function getConnection(): ?PDO {
        return $this->conn;
    }
}
?>
