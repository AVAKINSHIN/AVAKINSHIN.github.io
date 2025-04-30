<?php
header('Content-Type: text/html; charset=UTF-8');

// В суперглобальном массиве $_SESSION хранятся переменные сессии.
// Будем сохранять туда логин после успешной авторизации.
$session_started = false;
if ($_COOKIE[session_name()] && session_start()) {
  $session_started = true;
  if (!empty($_SESSION['login'])) {

//Вы уже авторизовались в системе. Хотите выйти или продолжить? <br />
//<form action="./" method="post">
//<input type="submit" value="Продолжить" />
//</form>
//<form action="rabi.php" method="get">
//<input type="submit" value="Выйти" />
          //</form>
//<?php
          session_destroy();
          header('Location: login.php');
          exit();
  }
}
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
?>

<form action="" method="post">
  <input name="login" />
  <input name="pass" />
  <input type="submit" value="Войти" />
</form>

<?php
}
else {
         $db = new PDO('mysql:host=localhost;dbname=uXXXXX', 'uXXXXX', 'XXXXXXX',
          [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        try
        {
                $stmt=$db->prepare("SELECT id_user, pass FROM users WHERE login=?");
                $stmt->execute([$_POST['login']]);
                if ($stmt->rowCount() == 0)
                {
                        setcookie("login_error", "Неверный логин или пароль", 24 * 60 * 60);
                        header("Location: login.php");
                }
        }
        catch(PDOException $e)
        {
                setcookie("login_error", "Неверный логин или пароль", 24 * 60 * 60);
                header('Location: login.php');
        }
        if (!password_verify($_POST['pass'], $wq[1]))
        {
            setcookie("login_error", "Неверный логин или пароль", 24 * 60 * 60);
            header('Location: login.php');
                exit();
        }
  if (!$session_started)
  {
    session_start();
  }
  $_SESSION['login'] = $_POST['login'];
  $_SESSION['uid'] = 123;
  header('Location: ./');
}
?>
