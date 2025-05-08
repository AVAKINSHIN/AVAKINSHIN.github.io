<?php
header('Content-Type: text/html; charset=UTF-8');
include("functions.php");
$session_started = false;
if (isset($_COOKIE[session_name()]) && session_start()) {                                                                                                   
  $session_started = true;
  if (!empty($_SESSION['login']))
  {
    if (isset($_POST['exit']))
      {
        session_destroy();
        header('Location: login.php');
        exit();
        }
    $languages=empty($_COOKIE['language_value'])?array():explode("|", $_COOKIE['language_value']);
          if ($_SERVER['REQUEST_METHOD'] == 'GET')
          {
                include("checkerrors.php");
                $stmt=$db->prepare("SELECT fio, email, year, gender, phone, biography, accept FROM person WHERE id=?");
                $stmt->execute([$_SESSION['uid']]);
                if ($stmt->rowCount()!=0)
                {
                  $row=$stmt->fetch(PDO::FETCH_ASSOC);
                  foreach (array('fio', 'phone', 'email', 'year', 'gender', 'biography', 'accept') as $v)
                  {
                        $values[$v] = strip_tags($row[$v]);
                  }
                }
                printf('Вход с логином %s, uid %d', $_SESSION['login'], $_SESSION['uid']);
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
                   if ($errors)
                   {
                        header('Location: login.php');
                        exit();
                   }
            $stmt = $db->prepare("UPDATE person SET fio=?, phone=?, email=?, year=?, gender=?, biography=?, accept=? WHERE id=?");
                $stmt->execute([$_POST['fio'], $_POST['phone'], $_POST['email'], $_POST['year'], $_POST['gender'],                                          
                               $_POST['biography'], $_POST['accept'], $_SESSION['uid']]);
                $stmt = $db->prepare("DELETE FROM person_language WHERE id_p=?");                                                                           
            $stmt->execute([$_SESSION['uid']]);                                                                                                                         foreach ($_POST['language'] as $v)
            {                                                                                                                                               
              $stmt = $db->prepare("INSERT INTO person_language SET id_l=?, id_p=?");
                $stmt->execute([$v, $_SESSION['uid']]);
            }
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
        header("Location: login.php");
}
?>
