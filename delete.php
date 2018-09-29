<?php
    require_once('tools.php');
    require_once('BoardDao.php');

    /*
    해당 게시글이 몇번인지와 몇번째 페이지인지 가져온다.
    num은 해당 게시글에 대한 작업을 하기 위해
    page는 사용자가 몇번째 page에서 선택했는지 확인하고
    해당 page로 돌아가기 위해서 필요함.
    */
    $num = requestValue("num");
    $page = requestValue("page");

    // BoardDao 객체 생성
    $dao = new BoardDao();

    // 사용자가 선택한 게시글의 num을 체크해서 
    // 그 게시글에 대한 정보를 가져온다.
    $row = $dao->getMsg($num);
    
    // 글 작성자만이 글 삭제 가능.
    // 세션이 시작되어 있지 않을 경우 세션을 시작하는 함수
    session_start_if_none();
    // 세션에 로그인 한 id 가 있는지 체크하는 함수
    // 없을 경우 빈값을 return해준다.
    $id = sessionVar("uid");
    // 만약 세션에 있는 id 즉 로그인 id와
    // 글 작성자가 일치하지 않으면 글 삭제 불가
    if($id != $row["writer"]) {
        errorBack("글 작성자만 삭제할 수 있습니다.");
    }

    // 검증되면 글 삭제.
    $dao->deleteMsg($num);

    // 메인 페이지로 복귀
    $msg = "정상적으로 삭제되었습니다";

    /*
        tools에 있는 bdUrl을 사용하여 board.php로 복귀한다.
    */
    okGo($msg, bdUrl("board.php", 0, $page));
?>