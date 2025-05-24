<?php
header('Content-Type: text/html; charset=UTF-8');
include("functions.php");
$session_started = false;
if (isset($_COOKIE[session_name()]) && session_start()) {                                                                                                     $session_started = true;
  if (!empty($_SESSION['login']))
  {
    $min = intval(date('i')) - $_SESSION['min'];
    if ($_SESSION['date'] != date('Y-m-d') || $_SESSION['hr'] != date('H') || $min > 30 || isset($_POST['exit']))
    {
        session_destroy();
        header('Location: login.php');
        exit();
    }
    $languages=empty($_COOKIE['language_value'])?array():explode("|", $_COOKIE['language_value']);
          if ($_SERVER['REQUEST_METHOD'] == 'GET')
          {
                include("checkerrors.php");
                $values = grabTegs($_SESSION['uid'], $db);
                printf('Вход с логином %s, uid %d, дата %s, время %s', $_SESSION['login'], $_SESSION['uid'], date('Y-m-d'), date('H:i:s'));
                include("form.php");
?>
<form action="login.php" method="post">
<input type="submit" value="Выйти" name="exit" />
</form>
<?php
                exit();
          }
          else
          {
                  include("errortest.php");
                  if ($POST['token'] != $_SESSION['token'])
                  {
                          setcookie('to', 'Ваш токен устарел. Попробуйте ещё раз.', time() + 30 * 60);
                          $errors = TRUE;
                  }
                   if ($errors)
                   {
                        header('Location: login.php');
                        exit();
                   }
                   updateDB($_POST['fio'], $_POST['phone'], $_POST['email'], $_POST['year'], $_POST['gender'], $_POST['biography'], $_POST['accept'],
                   $_SESSION['uid'], $_POST['language'], $db);
                   header("Location: login.php");
                   exit();
             }
  }
}
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
?>
<form action="" method="post">
  Логин: <input name="login" />
  Пароль: <input name="pass" />
  UID: <input name="uid" />
  <input type="submit" value="Войти" />
</form>
<form action="admin.php" method="post">
<input type="submit" value="Войти как администратор" />
</form>
<?php
}
else
{
        if (empty($_POST['login']) || empty($_POST['pass']))
        {
                print("Неверный логин или пароль<br />");
                print("<a href='login.php'>Попробовать снова</a> <br />");
                print("<a href='index.php'>Продолжить как гость</a>");
                exit();
        }
        $stmt=$db->prepare("SELECT name, pass FROM users WHERE id_user=?");
        $stmt->execute([$_POST['uid']]);
        if ($stmt->rowCount() == 0)
        {
                print("Неверный логин или пароль<br />");
                print("<a href='login.php'>Попробовать снова</a> <br />");
                print("<a href='index.php'>Продолжить как гость</a>");
                exit();
        }
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        if (!password_verify($_POST['pass'], $row['pass']) || $_POST['login']!=$row['name'])
        {
                print("Неверный логин или пароль<br />");
                print("<a href='login.php'>Попробовать снова</a> <br />");
                print("<a href='index.php'>Продолжить как гость</a>");
                exit();
        }
        if (!$session_started)
        {
                session_start();
        }
        $_SESSION['login'] = $_POST['login'];
        $_SESSION['uid'] = $_POST['uid'];
        $_SESSION['date'] = date('Y-m-d');
        $_SESSION['hr'] = date('H');
        $_SESSION['min'] = date('i');
        $_SESSION['token'] = bin2hex(random_bytes(32));
        header("Location: login.php");
}
?>
