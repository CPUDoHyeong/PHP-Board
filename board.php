<?php
    require_once('tools.php');
    require_once('BoardDao.php');

    // 사용자 아이디와 이름을 담은 세션 변수 읽기
    session_start_if_none();
    $id = sessionVar("uid");
    $name = sessionVar("uname");

    // 전달된 페이지 번호 저장
    $page = requestValue("page");

    // 화면 구성에 관련된 상수 정의 
    define("NUM_LINES", 5);         // 한페이지에 몇개의 게시글을 보여줄지 정함.
    // 화면에 표시될 페이지 링크의 수
    // 3이라면 3까지 보여주고 4, 5, 6을 보여주는 식이다.
    define("NUM_PAGE_LINKS", 3);    

    $dao = new BoardDao();
    // 전체 게시글의 수가 몇개인지 받아온다.
    $numMsgs = $dao->getNumMsgs();

    // 만약 게시글이 하나도 없으면 아래의 작업을 처리할 필요가 없음.
    if($numMsgs > 0) {
        /*
        전체 페이지 수를 구한다.
        예를 들어 게시글 16개를 5개씩 보여준다고 하면 3페이지랑 1개가 되고
        4번째 페이지에도 마지막 하나가 들어가야 하므로 소수점인 경우 올림을 시켜준다.
        그 수식이 ceil이다.
        */
        $numPages = ceil($numMsgs / NUM_LINES);

        /* 
        현재 페이지 번호가 1보다 작은경우에는 1로 변경하고 
        전체 페이지보다 큰경우는 전체 페이지로 변경한다.
        11번 행의 페이지 번호를 저장할 때 아무 값도 없는 경우는 1로 해준다.
        */
        if($page < 1)
            $page = 1;
        if($page > $numPages)
            $page = $numPages;

        // 리스트에 보일 게시글 데이터 읽기
        /* 
        페이지마다 현재 5개의 글이 들어가도록 설정했으므로
        전체 게시글을 가져올 필요 없이 5개만 가지고 오면 된다.
        $page - 1 * NUM_LINES를 하는 이유는 DB에서 레코드를 0부터 처리하기 때문이다.
        만약 1페이지의 경우 $start 값이 0이 되는데
        0번 레코드 부터 5개를 가져와서 보여준다는 말이다. 
        -1이 없는 경우는 1페이지임에도 데이터베이스의 5번째 게시글 부터 보여준다.
        */
        $start = ($page - 1) * NUM_LINES; // 첫 줄의 레코드 번호
        $msgs = $dao->getManyMsgs($start, NUM_LINES);

        /*
        현재 페이지를 기준으로 아래 페이지번호들을 계산한다.
        예를들어 현재가 3페이지라면 1, 2, 3을 보여주도록하고
        4페이지라면 4, 5, 6을 보여주도록 계산하는 수식이다.
        즉 firstLink는 1, 4, 7 이런식으로 나올 수 밖에 없고
        listLink는 3, 6, 9 이런식으로 나올 수 밖에 없다.
        */
        $firstLink = floor(($page - 1) / NUM_PAGE_LINKS) * NUM_PAGE_LINKS + 1;
        $lastLink = $firstLink + NUM_PAGE_LINKS -1;
        if($lastLink > $numPages) {
            $lastLink = $numPages;
        }
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
    <script>
function openNav() {
    document.getElementById("mySidenav").style.width = "250px";
}

function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
}
</script>
</head>
<body>
<div class="jumbotron2">
    <span id="setSpan">Board Example</span>
</div>


<div class="container">

    <?php if($id) : // 로그인 상태일 때의 출력 ?>
        <form action="<?=MEMBER_PATH ?>/logout.php" method="post">
            <span class="loginSpan"><?= $name ?>님 로그인</span>
            <input type="button" id="button" class="btn btn-outline-dark" value="Modify" onclick="location.href='<?=MEMBER_PATH ?>/member_update_form.php'">
            <input type="submit" id="button" class="btn btn-outline-secondary" value="Logout">
        </form>

    <?php else : // 로그인되지 않은 상태일 때의  출력 ?>
        <form action="login.php" method="POST">
            <div class="form-row">
                <div class="col-auto">
                    <input type="text" class="form-control" name="id" placeholder="ID">
                </div>
                <div class="col-auto">
                    <input type="password" class="form-control" name="pw" placeholder="Password">
                </div>
                <button id="button" type="submit" class="btn btn-outline-dark">Login</button>
                <a id="button" class="btn btn-outline-secondary" href="member_join_form.php">Register</a>
            </div>
        </form>
    <?php endif ?>
    
    <!-- 만약 게시글이 있다면 보여준다.-->
    <?php if($numMsgs > 0) : ?>
        <table class="table table-hover">
            <thead class="thead-dark">
                <tr>
                    <th class="list-num">Num</th>
                    <th class="list-title">Title</th>
                    <th class="list-writer">Writer</th>
                    <th class="list-regtime">Regtime</th>
                    <th>Hits</th>
                </tr>
            </thead>
            <!-- 데이터를 보여준다. 반복문으로. -->
            <?php foreach ($msgs as $row) : ?>
            <?php
                $row["title"] = str_replace(" ", "&nbsp;", $row["title"]);
                $row["title"] = str_replace("<", "&lt", $row["title"]);
                $row["title"] = str_replace(">", "&gt", $row["title"]);
            ?>
            <tbody>
                <tr class="table hover">
                    <td><?= $row["num"] ?></td>
                    <td class="left"><a href="<?= bdUrl("view.php", $row["num"], $page) ?>"><?= $row["title"] ?></td>
                    <td><?= $row["writer"] ?></td>
                    <td><?= $row["regtime"] ?></td>
                    <td><?= $row["hits"] ?></td>
                </tr>
            </tbody>
            <?php endforeach ?>
        </table>

        <br>

        <!-- 페이지 보여주는 div -->
        <div class="center">
            <!-- 
                첫 페이지가 1보다 클경우만 < 표시를 해준다.
                1, 2, 3 페이지의 경우는 1이 가장 앞인데 그 경우 < 표시가 없어도 되므로.
            -->
            <?php if ($firstLink > 1) : ?>
                <a href="<?= bdUrl("board.php", 0, $page - 1) ?>"><</a>&nbsp;
            <?php endif ?>

            <!-- 
                firstLink 부터 lastLink까지의 페이지 링크를 출력한다.
                현재 보여주고 있는 페이지가 출력하려는 페이지와 같다면 굵은 글자로 보여준다.
            -->
            <?php for ($i = $firstLink; $i <= $lastLink; $i++) : ?>
                <?php if ($i == $page) : ?>
                    <a href="<?= bdUrl("board.php", 0, $i) ?>"><b><?= $i ?></b></a>&nbsp;
                <?php else : ?>
                    <a href="<?= bdUrl("board.php", 0, $i) ?>"><?= $i ?></a>&nbsp;
                <?php endif ?>
            <?php endfor ?>

            <!-- 
                마지막 페이지가 전체 페이지보다 작을경우만 > 표시를 해준다.
                전체가 7 페이지의 경우는 7이 가장 뒤인데 그 경우 > 표시가 없어도 되므로.
            -->
            <?php if ($lastLink < $numPages) : ?>
                <a href="<?= bdUrl("board.php", 0, $page + 1) ?>">></a>
            <?php endif ?>
        </div>

    <?php endif ?>

    <br><br>

    <!-- 글 작성 버튼 -->
    <div class="left">
        <input class="btn btn-dark" type="button" value="Write" onclick="location.href='<?= bdUrl("write_form.php", 0, $page) ?>'">
    </div>
</div>
</body>
</html>