<?php
    require_once("tools.php");

    // 세션변수에서 로그인 정보 삭제(unset함수 이용)
    session_start_if_none();
    unset($_SESSION["uid"]);
    unset($_SESSION["uname"]);

    // 메인 페이지로 돌아감
    goNow(MAIN_PAGE);
?>