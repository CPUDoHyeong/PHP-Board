<?php

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
</head>
<body>
<div class="container"><br>
    <h3>글 작성</h3>
    <hr>
    <form action="register.php" method="POST">
        <div class="form-group">
        <label for="title">제목 : </label>
        <input type="text" class="form-control" id="title" name="title"  placeholder="Title"> </div>
        <div class="form-group">
        <label for="title">작성자 : </label>
        <input type="text" class="form-control" id="writer" name="writer"  placeholder="Writer"> </div>
        <div class="form-group">
        <label for="title">내용 : </label>
        <div class="form-group">
        <textarea class="form-control" rows="5" id="content" name="content"  placeholder="내용을 입력하세요"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">등록</button>
        <a href="main.php" class="btn btn-dark">목록</a>
    </form>
</div>
</body>
</html>