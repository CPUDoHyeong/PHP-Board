<?php
    require_once('tools.php');
    require_once('BoardDao.php');

    // 전달된 값 저장
    $num = requestValue("num");
    $page = requestValue("page");

    // $num번 게시글 데이터 읽기
    $dao = new BoardDao();
    $row = $dao->getMsg($num);

    // 글 작성자만이 글 수정 가능.
    // 세션이 시작되어 있지 않을 경우 세션을 시작하는 함수
    session_start_if_none();
    // 세션에 로그인 한 id 가 있는지 체크하는 함수
    // 없을 경우 빈값을 return해준다.
    $id = sessionVar("uid");
    if(!$id) {
        // 만약 id가 없는 경우는 로그인 하라는 알림창을 띄우고
        // 이전 페이지로 돌아간다.
        errorBack("로그인 후 수정이 가능합니다.");
    } else if($id != $row["writer"]) {
        /*
        현재 게시글의 번호를 통해 작성자, 제목, 내용 등을 가져 온 후
        글의 작성자가 로그인한 사용자 즉 세션의 id와 일치하지 않는다면
        글을 수정 못하도록 막는다.
        */
        errorBack("글 작성자만 수정 할 수 있습니다.");
    }

    
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
</head>
<body>

    <div class="jumbotron2">
        <span id="setSpan">Board Example</span>
    </div>
    
    <div class="container">
        <form method="post" action="<?= bdUrl("modify.php", $num, $page) ?>">
            <table class="table">
                <tr>
                    <th>제목</th>
                    <td><input class="form-control" type="text" name="title" maxlength="80" value="<?= $row['title'] ?>">
                    </td>
                </tr>

                <tr>
                    <th class="header">작성자</th>
                    <td><input class="form-control" type="text" name="writer" maxlength="20" value="<?= $row['writer'] ?>" readonly>
                    </td>
                </tr>

                <tr>
                    <th>내용</th>
                    <td><textarea class="form-control" name="content" wrap="virtual" rows="5"><?= $row['content'] ?></textarea>
                    </td>
                </tr>
            </table>

            <br>
            <div class="left">
                <input class="btn btn-dark" type="submit" value="적용">
                <input class="btn btn-secondary" type="button" value="목록보기" onclick="location.href='<?= bdUrl("board.php", 0, $page) ?>'">
            </div>
        </form>
    </div>

</body>
</html>