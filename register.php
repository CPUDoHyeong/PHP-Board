<?php
    require_once('tools.php');
    require_once('BoardDao.php');

    $title = requestValue("title");
    $writer = requestValue("writer");
    $content = requestValue("content");

    $bmdo = new BoardDao();
    if($title && $writer && $content) {
        $bmdo->insertData($title, $writer, $content);
        okGo("글 등록 완료", MAIN_PAGE);    
    } else {
        errorBack("모든 입력란을 채워주세요.");
    }

?>