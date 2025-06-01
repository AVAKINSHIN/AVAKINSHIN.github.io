<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
        include("styles.php");
?>
<title>Библиотека: страница входа</title>
</head>
<body><div id='container'>
<?php if (isset($_COOKIE['login_f'])) { print($_COOKIE['login_f']); print("<br />"); } ?>
<form action="" method="post" class="b">
Страница входа<br />
Имя пользователя:<input name="login" placeholder="Иван Иванов" <?php if (isset($_COOKIE['login_f'])) print("class='error'");?>/><br />
Пароль:<input name="pass" <?php if (isset($_COOKIE['login_f'])) print("class='error'");?>/><br />
<input type="submit" value="Войти" />
</form>
<?php if (isset($_COOKIE['login_f'])) { setcookie('login_f', ''); } ?>
<form action="registration.php" method="post" class="c">
Ещё нет аккаунта?<br />
<input type="submit" value="Зарегистрироваться" />
</form>
<form action="admin.php" method="post">
<input type="submit" value="Войти как администратор" />
</form>
</div>
</body>
</html>
<?php
}
else
{
        if (empty($_POST['login']) || empty($_POST['pass']))
        {
                setcookie('login_f', '<div class="error">Неверный логин или пароль</div>', time() + 30 * 60);
                header("Location: index.php");
                exit();
        }
        else
        {
                include("database.php");
                $stmt=$db->prepare("SELECT * FROM bk_users WHERE username = ?");
                $stmt->execute([$_POST['login']]);
                if ($stmt->rowCount() == 0)
                {
                   setcookie('login_f', '<div class="error">Неверный логин или пароль</div>', time() + 30 * 60);
                        header("Location: index.php");
                        exit();
                }
                $row=$stmt->fetch(PDO::FETCH_ASSOC);
                if (!password_verify($_POST['pass'], $row['hache']))
                {
                        setcookie('login_f', '<div class="error">Неверный логин или пароль</div>', time() + 30 * 60);
                        header("Location: index.php");
                        exit();
                }
                if (!$session_started)
                {
                        session_start();
                }
                $stmt=$db->prepare("SELECT * FROM roles WHERE id = ?");
                $stmt->execute([$row['id']]);
                $wor=$stmt->fetch(PDO::FETCH_ASSOC);
                $_SESSION['login'] = $_POST['login'];
                $_SESSION['uid'] = $row['id'];
                $_SESSION['role'] = $wor['role'];
                $_SESSION['date'] = date('Y-m-d');
                $_SESSION['hr'] = date('H');
                $_SESSION['min'] = date('i');
                $_SESSION['token'] = bin2hex(random_bytes(32));
                header('Location: index.php');
        }
}
?>
