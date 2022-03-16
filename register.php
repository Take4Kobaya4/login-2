<?php
// Include config file
require_once "config.php";

$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

if ($_SESSION["REQUEST_METHOD"] === "POST") {
  if (empty(trim($_POST["username"]))) {
    $username_err = "ユーザー名を入れてください。";
  } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))) {
    $username_err = "ユーザー名は英数字を入れてください。";
  } else {
    $sql = "SELECT id FROM users WHERE username = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
      mysqli_stmt_bind_param($stmt, "s", $param_username);
      // パラメーターの設置
      $param_username = trim($_POST["username"]);

      if (mysqli_stmt_execute($stmt)) {
        // store result
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) == 1) {
          $username_err = "このユーザ名は既に使われています。";
        } else {
          $username = trim($_POST["username"]);
        }
      } else {
        echo "何かが異なっています。またもう一度試してください。";
      }
      mysqli_stmt_close($stmt);
    }
  }

  // パスワードの検証
  if (empty(trim($_POST["password"]))) {
    $password_err = "パスワードを入れてください。";
  } elseif (strlen(trim($_POST["password"])) < 6) {
    $password_err = "パスワードは少なくとも6文字以上でお願いします。";
  } else {
    $password = trim($_POST["password"]);
  }

  // 確認用パスワードの検証
  if (empty(trim($_POST["confirm_password"]))) {
    $confirm_password_err = "確認用パスワードを入れてください。";
  } else {
    $confirm_password = trim($_POST["confirm_password"]);
    if (empty($password_err) && ($password != $confirm_password)) {
      $confirm_password = "パスワードが一致していません。";
    }
  }

  // データベースに挿入する前にインプットエラーを確認する
  if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {
    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";

    if ($stmt = mysqli_prepare($link, $sql)) {
      mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);

      // パラメーターの設置
      $param_username = $username;
      // パスワードハッシュの作成
      $param_password = password_hash($password, PASSWORD_DEFAULT);

      if (mysqli_stmt_execute($stmt)) {
        header("location: login.php");
      } else {
        echo "ユーザー名もしくはパスワードが異なります。";
      }
      mysqli_stmt_close($stmt);
    }
  }
  mysqli_close($link);
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>新規登録</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body {
      font: 14px sans-serif;
    }

    .wrapper {
      width: 360px;
      padding: 20px;
    }
  </style>
</head>

<body>
  <div class="wrapper">
    <h2>新規登録</h2>
    <p>アカウント作成のためにこのフォーム内の空欄を埋めてください！</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <div class="form-group">
        <label>ユーザー名</label>
        <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
        <span class="invalid-feedback" <?php echo $username_err; ?>></span>
      </div>
      <div class="form-group">
        <label>パスワード</label>
        <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
        <span class="invalid-feedback" <?php echo $password_err; ?>></span>
      </div>
      <div class="form-group">
        <label>確認用パスワード</label>
        <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
        <span class="invalid-feedback" <?php echo $password_err; ?>></span>
      </div>
      <div class="form-group">
        <input type="submit" class="btn btn-primary" value="登録">
        <input type="submit" class="btn btn-secondary" value="リセット">
      </div>
      <p>既にアカウントはお持ちですか？<a href="login.php">ログインはこちら</a>！</p>
    </form>
  </div>
</body>

</html>
