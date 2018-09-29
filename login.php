<?php
    require_once("tools.php");
    require_once("BoardDao.php");

    // 로그인 폼에서 전달된 아이디, 비밀번호 읽기
    $id = requestValue("id");
    $pw = requestValue("pw");

    // 로그인 폼에 입력된 아이디의 회원정보(id, pw, name)를 DB에서 읽기
    $dao = new BoardDao();
    $member = $dao->getMember($id);

    // 사용자가 입력한 id를 가지고 있는 행이 있고, 
    // 그 행의 pw 값과 사용자가 입력한 비밀번호가 맞으면 로그인
    if ($member && $member["pw"] == $pw) {
        session_start_if_none();

        // session을 이용하여 로그인
        // session에 pw는 저장하지 않는다
        $_SESSION["uid"] = $id;
        $_SESSION["uname"] = $member["name"];

        // 메인 페이지로 돌아감
        goNow(MAIN_PAGE);
    } else {
        errorBack("아이디 또는 비밀번호가 잘못 입력되었습니다.");
    }
        
?>