<?php

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] != true) {
  header("location: login.php");
  exit;
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Page</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body {
      font: 14px sans-serif;
      text-align: center;
    }
  </style>
</head>

<body>
  <header>
    <div>
      <nav>
        <li><a href="reset-password.php" class="btn btn-warning">パスワードリセット</a></li>
        <li><a href="logout.php" class="btn btn-danger ml-3">ログアウト</a></li>
      </nav>
    </div>
  </header>
</body>

</html>
