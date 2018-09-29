<?php
    require_once('tools.php');
    require_once('BoardDao.php');

    // 현재 로그인한 사용자의 회원정보 읽기
    // 먼저 사용자의 정보를 보여주고 수정하라고 해야하기 때문에
    // 사용자의 정보를 가져와야한다.
    session_start_if_none();
    $dao = new BoardDao();
    $member = $dao->getMember($_SESSION['uid']);
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
    <!-- 
        사용자 회원정보 업데이트 form
        사용자가 작성한 아이디, 비밀번호, 닉네임을 미리 보여준다.
        id 같은 경우는 수정되지 못하도록 readonly를 주었다.
        disabled도 있지만 disabled 같은 경우는 request 할 때 읽어오지 못한다.
    -->
    <div class="container">
        <form action="member_update.php" method="POST" style="max-width:500px; margin:auto;">
            <h3>The register data changes</h3>
            <hr>
            <div class="form-group">
                <input type="text" class="form-control" name="id" placeholder="ID" value=<?= $member["id"] ?> readonly>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="pw"  value=<?= $member["pw"] ?> placeholder="Password">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="name" value=<?= $member["name"] ?> placeholder="Name">
            </div>
            <button type="submit" class="btn btn-dark">Modify</button>
            <!-- 취소를 누르면 이전 페이지로 돌아간다. -->
            <a class="btn btn-secondary" href="javascript:history.back()">Cancel</a>
        </form>
    </div>
</body>
</html>