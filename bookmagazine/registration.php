<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
        include("styles.php");
?>
<title>Библиотека: страница регистрации</title>
</head>
<body><div id='container'>
<?php if (isset($_COOKIE['reg_f'])) { print($_COOKIE['reg_f']); print("<br />"); } ?>
<form action="" method="post" class="b">
Страница регистрации<br />
Имя пользователя:<input name="user" placeholder="Иван Иванов" <?php if (isset($_COOKIE['reg_f'])) print("class='error'");?>/><br />
Пароль:<input name="pass" <?php if (isset($_COOKIE['reg_f'])) print("class='error'");?>/><br />
Повторите пароль <input name="ppass" <?php if (isset($_COOKIE['reg_f'])) print("class='error'");?>/><br />
<input type="submit" value="Зарегистрироваться" />
</form>
<?php if (isset($_COOKIE['reg_f'])) { setcookie('reg_f', ''); } ?>
<form action="index.php" method="post" class="c">
Уже есть аккаунт?<br />
<input type="submit" value="Войти" />
</form>
</div>
</body>
</html>
<?php
}
else
{
        if (empty($_POST['user']) || empty($_POST['pass']) || empty($_POST['ppass']))
        {
                setcookie('reg_f', '<div class="error">Все поля должны быть заполнены</div>', time() + 30 * 60);
                header("Location: registration.php");
                exit();
        }
        else
        {
                include("database.php");
                $stmt=$db->prepare("SELECT * FROM bk_users WHERE username = ?");
                $stmt->execute([$_POST['user']]);
                if ($stmt->rowCount() != 0)
                {
                        setcookie('reg_f', '<div class="error">Пользователь с таким именем уже существует. Попробуйте другое имя.</div>', time() + 30 * 60);                        header("Location: registration.php");
                        exit();
                }
                if ($_POST['pass'] != $_POST['ppass'])
                {
                   setcookie('reg_f', '<div class="error">Пароли не совпадают</div>', time() + 30 * 60);
                        header("Location: registration.php");
                        exit();
                }
                $stmt=$db->prepare("INSERT INTO bk_users SET username = ?, hache= ?");
                $stmt->execute([$_POST['user'], $_POST['pass']]);
                setcookie('reg_f', 'Данные успешно сохранены. Теперь можете войти в совй личный кабинет.<br />', time() + 30 * 60);
                header("Location: registration.php");
                exit();
}
?>
