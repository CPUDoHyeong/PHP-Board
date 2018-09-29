<?php
    require_once("tools.php");
    require_once("BoardDao.php");

    // 회원정보 수정 폼에 입력된 데이터 읽기
    $id = requestValue("id");
    $pw = requestValue("pw");
    $name = requestValue("name");

    // 모든 입력란이 채워져 있으면 회원정보 업데이트
    if($id && $pw && $name) {

        // DB의 회원정보 업데이트
        $dao = new BoardDao();
        $dao->updateMember($id, $pw, $name);

        // 현재 로그인한 사용자의 이름이 담긴 세션변수 값을
        // 새로 입력된 것으로 변경
        session_start_if_none();
        $_SESSION["uname"] = $name;

        // 알림창을 띄워주고 메인페이지로 돌아간다.
        okGo("회원정보가 수정되었습니다.", MAIN_PAGE);
    } else {

        // 모든 입력란이 채워져 있지 않으면 알림창을 띄워주고 
        // 이전 페이지로 돌아간다.
        errorBack("모든 입력란을 채워주세요.");
    }
?>