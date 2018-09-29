<?php
    require_once('tools.php');
    require_once('BoardDao.php');

    /*
    해당 게시글이 몇번인지와 몇번째 페이지인지 가져온다.
    num은 해당 게시글에 대한 작업을 하기 위해
    page는 사용자가 몇번째 page에서 선택했는지 확인하고
    해당 page로 돌아가기 위해서 필요함.
    */
    $num  = requestValue("num");
    $page = requestValue("page");

    // BoardDao 객체 생성
    $dao = new BoardDao();
    // 사용자가 선택한 게시글의 num을 체크해서 
    // 그 게시글에 대한 정보를 가져온다.
    $row = $dao->getMsg($num);
    
    // 로그인 한 사람만 글을 볼 수 있도록한다.
    // 세션이 시작되어 있지 않을 경우 세션을 시작하는 함수
    session_start_if_none();
    // 세션에 로그인 한 id 가 있는지 체크하는 함수
    // 없을 경우 빈값을 return해준다.
    $id = sessionVar("uid");
    // 빈값일 경우 로그인 되지 않았다는 말이므로
    // 알림창을 띄우고 이전 페이지를 보여준다.
    if(!$id) {
        errorBack("로그인 후 조회가 가능합니다.");
    } // else if($id != $row["writer"]) {
        // errorBack("글 작성자만 읽을 수 있습니다.");
    // }

    /*
    조회수 증가
    hit 테이블(몇 번 게시글에 어떤 사용자가 조회했는지 저장한 테이블)
    이 테이블에서 게시글 번호에 해당하는 id가 있다면
    이미 조회했다는 말이므로 아무 작업도 하지않고
    데이터가 없다면 조회수를 올려주고 데이터를 저장한다.
    */
    $hitId = $dao->selectId($num, $id);
    if($hitId) {
        // true라면 이미 조회 했다는 말

    } else {
        // false라면 데이터 없음
        // 게시글 번호와 현재 로그인 한 사용자의 id를 hit 테이블에 저장한다.
        $dao->insertHitData($num, $id);
        // 조회수를 1 증가시킨다.
        $dao->increaseHits($num);

        /*
            사용자가 글을 등록하고 그 글을 봤을 때 기존에는
            새로고침을해야 조회 수가 올라갔는데
            reload(); 즉 화면을 다시 새로고침 해줌으로써
            글을 보는 순간 바로 조회수가 올라가있게함.
        */
        echo "<script>location.reload();</script>";
    }    

    // 제목의 공백, 본문의 공백과 줄넘김이 웹에서 보이도록 처리
    // str_replace("찾을 문자", "변경할 문자", "해당하는 문자열");
    $row["title"] = str_replace(" ", "&nbsp;", $row["title"]);
    $row["title"] = str_replace("<", "&lt", $row["title"]);
    $row["title"] = str_replace(">", "&gt", $row["title"]);
    $row["content"] = str_replace(" ", "&nbsp;", $row["content"]);
    $row["content"] = str_replace("\n", "<br>", $row["content"]);
    $row["content"] = str_replace("<", "&lt", $row["content"]);
    $row["content"] = str_replace(">", "&gt", $row["content"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="board2.css">
    <script>
        // confirm 처리.
        function confirm_click() {
            var check = confirm("정말 삭제 하겠습니까?");
            if(check) {
                location.href="<?= bdUrl("delete.php", $num, $page) ?>";
            } else {
                
            }
        }
    </script>
</head>
<body>
    <div class="jumbotron2">
        <span id="setSpan">Board Example</span>
    </div>

    <div class="container">
        <table class="table">
            <tr>
                <th>제목</th>
                <td><?= $row["title"]; ?></td>
            </tr>

            <tr>
                <th>작성자</th>
                <td><?= $row["writer"]; ?></td>
            </tr>

            <tr>
                <th>작성일시</th>
                <td><?= $row["regtime"]; ?></td>
            </tr>
            
            <tr>
                <th>Hits</th>
                <td><?= $row["hits"]; ?></td>
            </tr>
            
            <tr>
                <th>내용</th>
                <td><?= $row["content"]; ?></td>
            </tr>
        </table>

        <br>
        <div class="left">
            <input type="button" class="btn btn-dark" value="목록보기" onclick="location.href='<?= bdUrl("board.php", 0, $page) ?>'">
            <input type="button" class="btn btn-secondary" value="수정" onclick="location.href='<?= bdUrl("modify_form.php", $num, $page) ?>'">
            <input type="button" class="btn btn-active" value="삭제" onclick="confirm_click();">
        </div>
    </div>
</body>
</html>