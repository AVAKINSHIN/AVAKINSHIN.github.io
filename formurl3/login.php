<?php
header('Content-Type: text/html; charset=UTF-8');
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
        if (empty($_POST['login']) || empty($_POST['pass']))
        {
                print("Неверный логин или пароль<br />");
                print("<a href='login.php'>Попробовать снова</a> <br />");
                print("<a href='index.php'>Продолжить как гость</a>");
                exit();
        }
         $db = new PDO('mysql:host=localhost;dbname=u68768', 'u68768', '5901684',
                 [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $stmt=$db->prepare("SELECT id_user, pass FROM users WHERE name=?");
                $stmt->execute([$_POST['login']]);
                if ($stmt->rowCount() == 0)
                {
                        print("Неверный логин или пароль<br />");
                        print("<a href='login.php'>Попробовать снова</a> <br />");
                        print("<a href='index.php'>Продолжить как гость</a>");
                        exit();
                }
                $row=$stmt->fetch(PDO::FETCH_ASSOC);
                if (!password_verify($_POST['pass'], $row['pass']))
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
                $_SESSION['uid'] = $row['id_user'];
                header('Location: ./');
}
?>
