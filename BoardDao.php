<?php

/* 
    회원정보와 게시글 등록, 수정, 삭제 등 
    db와 연동된 
*/
class BoardDao {
    private $db;

    // DB접근 하고 PDO 객체를 db에 저장한다.
    public function __construct() {
        try {
            $this->db = new PDO("mysql:host=localhost; dbname=phpdb", "php", "1234");

            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            exit($e->getMessage());
        }
    }

    /*
        게시판의 전체 행수 반환
        count를 사용하여 게시글이 몇개인지 받아 온다.
        페이징 하기 위해 사용
    */
    public function getNumMsgs() {
        try {
            $query = $this->db->prepare("select count(*) from board");

            $query->execute();

            $numMsgs = $query->fetchColumn();
        } catch(PDOException $e) {
            exit($e->getMessage());
        }

        return $numMsgs;
    }

    /*
        해당 num번의 게시글만 가져온다.
        해당 글을 보거나 수정, 삭제할 때 혹은
        글을 작성한 사람이 그 글을 보려고 하는지 수정하려고 하는지
        삭제하려고 하는지 평가할 때도 사용한다.
    */ 
    public function getMsg($num) {
        try {
            $query = $this->db->prepare("select * from board where num=:num");

            $query->bindValue(":num", $num, PDO::PARAM_INT);
            $query->execute();

            $msg = $query->fetch(PDO::FETCH_ASSOC);

        } catch(PDOException $e) {
            exit($e->getMessage());
        }

        return $msg;
    }

    
    /*
        페이징 하기 위해 해당 페이지에 몇번째부터 몇개를 보여주기위한
        start와 rows를 받아온 후 데이터를 반환한다.
        전체를 보여줄 필요가 없으므로 start와 rows를 받아오는 것임.
    */
    public function getManyMsgs($start, $rows) {
        try {

            // num을 기준으로 오름차순으로 했기때문에
            // 최신 글을 먼저 보여준다.
            // ordert by num desc << 오름차순 수식
            $query = $this->db->prepare("select * from board order by num desc limit :start, :rows");    
            
            $query->bindValue(":start", $start, PDO::PARAM_INT);
            $query->bindValue(":rows", $rows, PDO::PARAM_INT);
            $query->execute();

            // 해당하는 열을 전부 받아오려고 하기 때문에 fetchall을 사용.
            // 그것을 사용할때는 foreach 사용하면됨.
            // 2차원 배열로 저장된 후 반환한다.
            $msgs = $query->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $e) {
            exit($e->getMessage());
        }

        return $msgs;
    }

    /*
        글 작성자, 제목, 내용을 받아와서 DB에 저장한다.
    */
    public function insertMsg($writer, $title, $content) {
        try {
            $query = $this->db->prepare("insert into board(writer, title, content, regtime, hits) values(:writer, :title, :content, :regtime, 0)");

            // 시간 같은 경우는 글 작성할때 기준을 추가하여 하므로 date()함수를 사용한다
            // "Y-m-d H:i:s" 는 년-월-일 시:분:초 를 의미한다.
            $regtime = date("Y-m-d H:i:s");
            $query->bindValue(":writer", $writer, PDO::PARAM_STR);
            $query->bindValue(":title", $title, PDO::PARAM_STR);
            $query->bindValue(":content", $content, PDO::PARAM_STR);
            $query->bindValue(":regtime", $regtime, PDO::PARAM_STR);
            $query->execute();

        } catch(PDOException $e) {
            exit($e->getMessage());
        }
    }

    /*
        num번 게시글 업데이트
        사용자가 글을 수정했을 때 수정내용을 DB에 반영하기 위함.
        글 등록과 비슷하지만 수정하려고 하는 게시글을 수정해야 하기 때문에
        num이 필요하다.
    */
    public function updateMsg($num, $writer, $title, $content) {
        try {
            $query = $this->db->prepare("update board set writer=:writer, title=:title, content=:content, regtime=:regtime where num=:num");

            $regtime = date("Y-m-d H:i:s");
            $query->bindValue(":writer", $writer, PDO::PARAM_STR);
            $query->bindValue(":title", $title, PDO::PARAM_STR);
            $query->bindValue(":content", $content, PDO::PARAM_STR);
            $query->bindValue(":regtime", $regtime, PDO::PARAM_STR);
            $query->bindValue(":num", $num, PDO::PARAM_INT);
            $query->execute();

        } catch(PDOException $e) {
            exit($e->getMessage());
        }
    }

    /*
        num번의 게시글 삭제
        사용자가 선택한 번호를 받아와서 그 번호의 게시글 삭제해 주면된다.
    */
    public function deleteMsg($num) {
        try {
            // 게시글이 저장되어 있는 테이블에서 데이터 삭제ㄴ
            $query = $this->db->prepare("delete from board where num=:num");

            $query->bindValue(":num", $num, PDO::PARAM_INT);
            $query->execute();

            // 게시글이 삭제되면 hit테이블에서도 해당 게시글에 대해 조회한 id 정보를 삭제해줘야한다.
            // hit테이블은 로그인 한 사용자가 글을 조회하면 한번만 조회수를 올리기 위해서 사용한다.
            // 사용자가 게시글을 확인하면 그 게시글 번호와 사용자의 id가 저장된다.
            $query = $this->db->prepare("delete from hit where num=:num");

            $query->bindValue(":num", $num, PDO::PARAM_INT);
            $query->execute();

        } catch(PDOException $e) {
            exit($e->getMessage());
        }
    }

    // $num번 게시글의 조회 수 1 증가
    public function increaseHits($num) {
        try {
            $query = $this->db->prepare("update board set hits=hits+1 where num=:num");

            $query->bindValue(":num", $num, PDO::PARAM_INT);
            $query->execute();

        } catch(PDOException $e) {
            exit($e->getMessage());
        }
    }

    // 아이디가 $id인 레코드 반환
    // 회원정보가 들어있는 테이블에서 id를 이용해 id와 pw name을 가져온다.
    public function getMember($id) {
        try {
            $query = $this->db->prepare("select * from member where id = :id");
            $query->bindValue(":id", $id, PDO::PARAM_STR);
            $query->execute();

            $result = $query->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            exit($e->getMessage());
        }

        return $result;
    }

    // 회원 정보 추가
    // 회원가입 창에서 사용자가 입력한 값들을 DB에 저장
    public function insertMember($id, $pw, $name) {
        try {
            $query = $this->db->prepare("insert into member values(:id, :pw, :name)");
            $query->bindValue(":id", $id, PDO::PARAM_STR);
            $query->bindValue(":pw", $pw, PDO::PARAM_STR);
            $query->bindValue(":name", $name, PDO::PARAM_STR);
            $query->execute();

        } catch(PDOException $e) {
            exit($e->getMessage());
        }
    }

    // 아이디가 $id인 회원 정보 업데이트
    // 회원정보를 수정할 경우 DB에 저장된 기존의 내용을 바꾼다.
    public function updateMember($id, $pw, $name) {
        try {
            $query = $this->db->prepare("update member set pw=:pw, name=:name where id=:id");
            $query->bindValue(":id", $id, PDO::PARAM_STR);
            $query->bindValue(":pw", $pw, PDO::PARAM_STR);
            $query->bindValue(":name", $name, PDO::PARAM_STR);
            $query->execute();

        } catch(PDOException $e) {
            exit($e->getMessage());
        }
    }

    /*
        조회수 중복 방지를 위해 hit테이블에 게시글 num와 사용자 id를 insert함.
        나중에 이 테이블에 num에 대한 id 값이 있는지 확인하여
        조회수를 증가할 지 안한지를 판단한다.
    */
    public function insertHitData($num, $id) {
        try {
            $query = $this->db->prepare("insert into hit values(:num, :id)");
            $query->bindValue(":num", $num, PDO::PARAM_INT);
            $query->bindValue(":id", $id, PDO::PARAM_STR);
            $query->execute();

        } catch(PDOException $e) {
            exit($e->getMessage());
        }
    }

    /*
        hit테이블에서 게시글 번호에 해당하는 id가 있는지 확인 후
        그 아이디를 return한다.
        사용자가 게시판에서 게시글을 클릭했을 때 조회수를 올려주는 메소드이다.
    */
    public function selectId($num, $id) {
        try {
            $query = $this->db->prepare("select id from hit where num=:num and id=:id");
            $query->bindValue(":num", $num, PDO::PARAM_INT);
            $query->bindValue(":id", $id, PDO::PARAM_STR);
            $query->execute();

            $result = $query->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            exit($e->getMessage());
        }

        return $result;
    }
}
?>