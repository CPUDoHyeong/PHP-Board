<?php

    require_once('tools.php');
    require_once('BoardDao.php');

    // 전달된 값 저장
    $num = requestValue("num");
    $page = requestValue("page");

    $writer = requestValue("writer");
    $title = requestValue("title");
    $content = requestValue("content");

    // 모든 항목이 값이 있으면 업데이트
    if($writer && $title && $content) {
        $dao = new BoardDao();
        $dao->updateMsg($num, $writer, $title, $content);

        /* 
        입력되었다는 알림창을 띄우고
        게시판 페이지를 보여준다.
        */
        $msg = "정상적으로 수정 되었습니다";
        okGo($msg, bdUrl("board.php", 0, $page));
    } else {

        // 하나라도 비어 있으면 알림창을 띄우고
        // 이전 페이지로 돌아간다.
        errorBack("모든 항목이 빈칸 없이 입력되어야 합니다.");
    }
?>