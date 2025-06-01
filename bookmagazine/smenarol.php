<?php
if (isset($_POST['exit6']))
{
        setcookie("page", "");
        header("Location: index.php");
        exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
        if ($_SESSION['role'] == 'admin')
        {
                include("verify_admin.php");
        }
        else
        {
                include("styles.php");
                print("<title>Библиотека: смена роли</title>");
                print("</head><body><div class='c'>");
        }
        if (isset($_COOKIE['roler']))
        {
                if ($_COOKIE['roler'] == '1')
                {
                        print("<div class='b'>Роль успешно сменена</div>");
                }
                else
                {
                        print("<div class='error'>");
                        print($_COOKIE['roler']);
                        print("</div>");
                }
                setcookie('roler', '');
        }
?>
<form action="" method="post" class='b'>
<?php if ($_SESSION['role'] == 'admin') print ("Введите id пользователя, которому хотите сменить роль<input name='idsmena'></input><br />"); ?>
<input type="radio" value="author" name="role" class='o'>Писатель</input><br />
<input type="radio" value="user" name="role" class='o'>Читатель</input><br />
<?php if ($_SESSION['role'] == 'admin') print ("<input type='radio' value='admin' name='role' class='o'>Администратор</input><br />");?>
<input type="submit" value="Сменить роль" />
</form>
<form action="" method="post">
<input type="submit" name="exit6" value="Назад к списку книг" />
</form>
</div>
</body>
</html>
<?php
}
else
{
        if (empty($_POST['role']))
        {
                setcookie('roler', 'Выберите роль', time() + 30 * 60);
                header("Location: index.php");
                exit();
        }
        $id = $_SESSION['uid'];
        include('database.php');
        if ($_SESSION['role'] == 'admin')
        {
                if (empty($_POST['idsmena']))
                {
                        setcookie('roler', 'Введите id пользователя, которому хотите сменить роль', time() + 30 * 60);
                        header("Location: index.php");
                        exit();
                }
                $stmt = $db->prepare("SELECT * FROM roles where id = ?");
                $stmt->execute([$_POST['idsmena']]);
                if ($stmt->rowCount() == 0)
                {
             setcookie('roler', 'Такого пользователя не существует. Проверьте id пользователя в <a href="admin.php">кабинете админа</a>', time() + 30 * 60);
                        header("Location: index.php");
                        exit();
                }
                $row=$stmt->fetch(PDO::FETCH_ASSOC);
                if ($_SESSION['login'] != 'mainadmin' && $row['role'] == 'admin')
                {
setcookie('roler', 'Забрать админку у другого пользователя может только главный администратор. Ваша текущая привилегия - администратор', time() + 30 * 60);
                        header("Location: index.php");
                        exit();
                }
                if ($_POST['idsmena'] == $row['id'])
                {
                         setcookie('roler', 'Вы не можете забрать админку у себя', time() + 30 * 60);
                        header("Location: index.php");
                        exit();
                }
                $id = $_POST['idsmena'];
        }
        $stmt = $db->prepare("UPDATE roles SET role = ? where id = ?");
        $stmt->execute([$_POST['role'], $id]);
        setcookie('roler', '1', time() + 30 * 60);
}
?>
