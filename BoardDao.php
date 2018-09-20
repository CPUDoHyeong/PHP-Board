<?php
class BoardDao {
        
    private $db;

    public function __construct() {
        try {
            $this->db = new PDO("mysql:host=localhost; dbname=test", "root", "1234");
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            exit($e->getMessage());
        }
    }

    // writer, title, content 값 request에 추출
    // 그값이 모두 존재하면 DB에 삽입
    // 삽입 후 목록 페이지로 이동
    // 값이 하나라도 없으면 errorBack();

    // DB insert
    public function insertData($title, $writer, $content) {
        try {
            $sql = "insert into board2(title, writer, content) values(:title, :writer, :content)";

            $query = $this->db->prepare($sql);
            
            $query->bindValue(":title", $writer, PDO::PARAM_STR);
            $query->bindValue(":writer", $title, PDO::PARAM_STR);
            $query->bindValue(":content", $content, PDO::PARAM_STR);
            $query->execute();
        } catch(PDOException $e) {
            exit($e->getMessage());
        }
    }
    

    // DB에 있는 모든 데이터 반환
    // main.php에서 전부 보여주기 위해 사용
    public function getData() {
        try {
            $query = $this->db->prepare("select * from board2 order by num desc");
            $query->execute();

        } catch(PDOException $e) {
            exit($e->getMessage());
        }

        return $query;
    }
}

?>