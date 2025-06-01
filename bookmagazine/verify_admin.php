<?php
include("styles.php");
if (empty($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_PW']))
{
        header('HTTP/1.1 401 Unanthorized');
        header('WWW-Authenticate: Basic realm="My site"');
        print("<title>Библиотека: ошибка входа</title></head><body>");
        print('<div class="b"><h1>401 Требуется авторизация</h1>');
        print("<div class='c'>Введите логин или пароль админа</div>");
?>
<form action="" method="post">
<input type="submit" name="repeat" value="Попробовать ещё раз" />
</form>
<form action="index.php" method="post">
<input type="submit" value="Выйти из аккаунта админа" />
</form>
</div>
</body>
</html>
<?php
        exit();
}
include("database.php");
$stmt=$db->prepare("SELECT * FROM bk_users where username = ?");
$stmt->execute([$_SERVER['PHP_AUTH_USER']]);
if ($stmt->rowCount()==0)
{
        header('HTTP/1.1 401 Unanthorized');
        header('WWW-Authenticate: Basic realm="My site"');
        print("<title>Библиотека: ошибка входа</title></head><body>");
        print('<div class="b"><h1>401 Требуется авторизация</h1>');
        print("<div clas='c'>Неверный логин или пароль</div>");
?>
<form action="" method="post">
<input type="submit" name="repeat" value="Попробовать ещё раз" />
</form>
<form action="index.php" method="post">
<input type="submit" value="Выйти из аккаунта админа" />
</form>
</div>
</body>
</html>
<?php
        exit();
}
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if (!password_verify($_SERVER['PHP_AUTH_PW'], $row['hache']))
{
        header('HTTP/1.1 401 Unanthorized');
        header('WWW-Authenticate: Basic realm="My site"');
        print("<title>Библиотека: ошибка входа</title></head><body>");
        print('<div class="b"><h1>401 Требуется авторизация</h1>');
        print("<div clas='c'>Неверный логин или пароль</div>");
?>
<form action="" method="post">
<input type="submit" name="repeatyt" value="Попробовать ещё раз" />
</form>
<form action="index.php" method="post">
<input type="submit" value="Выйти из аккаунта админа" />
</form>
</div>
</body>
</html>
<?php
        exit();
}
$stmt=$db->prepare("SELECT * FROM roles where id = ?");
$stmt->execute([$row['id']]);
$row=$stmt->fetch(PDO::FETCH_ASSOC);
if ($row['role']!='admin')
{
        header('HTTP/1.1 401 Unanthorized');
        header('WWW-Authenticate: Basic realm="My site"');
        print('<h1>401 Требуется авторизация</h1>');
        print("<div clas='c'>Необходим статус администратора</div>");
        print('<form action="" method="post">');
        print('<input type="submit" name="repeatyt" value="Попробовать ещё раз" />');
        print('</form>');
        print('<form action="index.php" method="post">');
        print('<input type="submit" value="Выйти из аккаунта админа" />');
        print('</form>');
        print('</div>');
        print('</body>');
        print('</html>');
        exit();
}
if (isset($_POST['exitadmin']))
{
        $_SERVER['PHP_AUTH_USER'] = '';
        $_SERVER['PHP_AUTH_PW'] = '';
        setcookie("r", "");
        if (isset($_COOKIE['page']))
        {
                setcookie('page', '');
        }
        header("Location: index.php");
        exit();
}
?>
<title>Кабинет действий админа</title>
</head>
<body>
<form action="" method="post">
<input type="submit" name="exitadmin" value="Выход" />
</form>
