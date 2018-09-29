<?php
    require_once("tools.php");
    require_once("BoardDao.php");

    // 회원가입 폼에 입력된 데이터 읽기
    $id = requestValue("id");
    $pw = requestValue("pw");
    $name = requestValue("name");
    $pwPattern = '/^.*(?=^.{8,15}$)(?=.*\d)(?=.*[a-zA-Z])(?=.*[!@#$%^&+=]).*$/';
    

    // 모든 입력란이 채워져 있고, 사용 중인 아이디가 아니면
    // 회원정보 추가
    $dao = new BoardDao();
    if($id && $pw && $name) {
        if($dao->getMember($id))

            // 사용중인 아이디라면 이전 페이지로
            errorBack("이미 사용 중인 아이디입니다");            
        else if (!preg_match($pwPattern, "$pw")) {
            
            // 비밀번호가 양식에 맞지 않다면
            errorBack("비밀번호는 8~15자 사이, 영문, 숫자, 특수문자가 포함되어야 합니다");
            
        } else {
            $dao->insertMember($id, $pw, $name);
            okGo("가입이 완료되었습니다.", MAIN_PAGE);
        }
    } else {

        // 하나라도 비어져 있으면 이전 페이지로
        errorBack("모든 입력란을 채워주세요.");
    }    

?>