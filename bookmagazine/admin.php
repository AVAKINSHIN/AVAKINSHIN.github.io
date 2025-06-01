<?php
include("verify_admin.php");
if (isset($_POST['smena']))
{
        if (empty($_POST['role']))
        {
                setcookie('smener', 'Выберите роль');
        }
        else
        {
                if (empty($_POST['idsmena']))
                {
                        setcookie('smener', 'Введите id пользователя, которому хотите сменить роль');
                }
                else
                {
                        $stmt = $db->prepare("SELECT * FROM roles where id = ?");
                        $stmt->execute([$_POST['idsmena']]);
                        if ($stmt->rowCount() == 0)
                        {
                                setcookie('smener', 'Такого пользователя не существует. Проверьте id пользователя в базе');
                        }
                        else
                        {
                                $row=$stmt->fetch(PDO::FETCH_ASSOC);
                                if ($_SERVER['PHP_AUTH_USER'] != 'mainadmin' && $row['role'] == 'admin')
                                {
                setcookie('smener', 'Забрать админку у другого пользователя может только главный администратор. Ваша текущая привилегия - администратор');
                                }
                                else
                                {
                                        if ($row['id'] == 1)
                                        {
                                                setcookie('smener', 'Вы не можете забрать админку у себя');
                                        }
                                        else
                                        {
                                                $stmt = $db->prepare("UPDATE roles SET role = ? where id = ?");
                                                $stmt->execute([$_POST['role'], $row['id']]);
                                                setcookie('smener', '1');
                                        }
                                }
                        }
                }
        }
        header("Location: admin.php");
  exit();
}
?>
Приветствуем вас, <?php print($_SERVER['PHP_AUTH_USER']); ?>, в кабинете админа нашей библиотеки.<br />
<div>
<div class="b">
Список доступных действий и прав
<table border="1">
<tr><th>Действие</th><th>Роли, имеющие право доступа</th></tr>
<tr><td>Просматривать книги по определённому жанру, названию или просто так</td><td>Все пользователи</td></tr>
<tr><td>Изменить свой пароль</td><td>Все пользователи</td></tr>
<tr><td>Брать книгу почитать</td><td>Все пользователи</td></tr>
<tr><td>Отдать книгу обратно</td><td>Все пользователи</td></tr>
<tr><td>Изменять свою роль (нельзя выдать себе роль админа)</td><td>Читатели и авторы</td></tr>
<tr><td>Просматривать историю своих запросов</td><td>Все пользователи</td></tr>
<tr><td>Просматривать свои активные книги</td><td>Все пользователи</td></tr>
<tr><td>Добавлять свою книгу в библиотеку</td><td>Авторы и админы</td></tr>
<tr><td>Просматривать историю запросов своей книги</td><td>Авторы и админы</td></tr>
<tr><td>Добавлять чужие книги в библиотеку</td><td>Админы</td></tr>
<tr><td>Просматривать всю историю запросов</td><td>Админы</td></tr>
<tr><td>Изменять роли пользователям с привилегиями "Читатель" и "Автор", в том числе выдавать им админку</td><td>Админы</td></tr>
<tr><td>Просматривать роли пользователей</td><td>Админы</td></tr>
<tr><td>Удалять устаревшие аккаунты пользователей</td><td>Главный админ</td></tr>
<tr><td>Забирать админку у других пользователей</td><td>Главный админ</td></tr>
<tr><td>Очищать историю запросов, если устарела</td><td>Главный админ</td></tr>
<tr><td>Видеть схему баз данных</td><td>Главный админ</td></tr>
<tr><td>Обновлять автоинкремент</td><td>Главный админ</td></tr>
<tr><td>Обновлять пароли пользователей</td><td>Главный админ</td></tr>
</table>
</div><div style='display: flex;' class="c"><div>
Список пользователей:
<?php
$t = 1;
$stmt=$db->prepare("SELECT * FROM bk_users where id>=?");
$stmt->execute([strval($t)]);
if ($stmt->rowCount()==0)
{
        print("У нас нет юзеров");
}
else
{
        print("<table border='1'><tr>");
        foreach(array("id", "Имя пользователя", "Привилегия") as $d)
        {
                print("<td class='z'>");
                print($d);
          print("</td>");
        }
        print("</tr>");
        $r = 1;
        while ($r!= 0)
        {
                $stmt=$db->prepare("SELECT * FROM bk_users where id=?");
                $stmt->execute([strval($t)]);
                if ($stmt->rowCount()==0)
                {
                        $hghg=$db->prepare("SELECT * FROM bk_users where id>=?");
                        $hghg->execute([strval($t)]);
                        if ($hghg->rowCount()==0)
                        {
                                $r = 0;
                                print("</table>");
                                break;
                        }
                        $t = $t + 1;
                }
                else
                {
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        print("<tr>");
                        foreach (array("id", "username") as $d)
                        {
                                print("<td class='z'>");
                                print($row[$d]);
                                print("</td>");
                        }
                        print("<td class='z'>");
                        if ($row['username'] == 'mainadmin')
                        {
                                print("Главный администратор");
                        }
                        else
                        {
                                $stmt=$db->prepare("SELECT * from roles where id=?");
                                $stmt->execute([$row['id']]);
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                if ($row['role'] ==  'admin')
                                {
                                        print("Администратор");
                                }
                                else
                                {
                                        if ($row['role'] == 'author')
                                        {
                                                print("Автор");
                                        }
                                        else
                                        {
                                                print("Читатель");
                                        }
                                }
                        }
                        print("</td></tr>");
                        $t = $t + 1;
                }
        }
        print("</table>");
}
print("</div><div>");
$u = 1; $w = 1;
print("Вся история запросов<br />");
$hghg = $db->prepare("SELECT ? FROM bk_exchanges");
$hghg->execute(["*"]);
if ($hghg->rowCount()==0)
{
        print("История запросов пуста");
}
else
{
        print("<table border='1'><tr>");
        foreach (array('Пользователь', 'Книга', 'Дата начала чтения', 'Дата конца чтения') as $d)
        {
                print("<td class='z'>");
                print($d);
                print("</td>");
        }
        print("</tr>");
        while ($u != 0)
        {
                $trtr=$db->prepare("SELECT * FROM bk_exchanges where id = ?");
                $trtr->execute([strval($w)]);
                if ($trtr->rowCount()==0)
                  {
                        $trtr=$db->prepare("SELECT * FROM bk_exchanges where id >= ?");
                        $trtr->execute([strval($w)]);
                        if ($trtr->rowCount()==0)
                        {
                                print("</table>");
                                $u = 0;
                                break;
                        }
                        $w = $w + 1;
                }
                else
                {
                        $wor=$trtr->fetch(PDO::FETCH_ASSOC);
                        $trtr=$db->prepare("SELECT * FROM bk_users where id = ?");
                        $trtr->execute([$wor['bkrequester']]);
                        $yto=$trtr->fetch(PDO::FETCH_ASSOC);
                        $stmt=$db->prepare("SELECT * FROM bk_books where id = ?");
                        $stmt->execute([$wor['bkreqid']]);
                        $row=$stmt->fetch(PDO::FETCH_ASSOC);
                        print("<tr><td class='z'>");
                        print(strip_tags($yto['username']));
                        print("</td><td class='z'>");
                        print(strip_tags($row['title']));
                        print("</td><td class='z'>");
                        print(strip_tags($wor['exchange_date']));
                        print("</td><td class='z'>");
                        if ($wor['status']=='active')
                        {
                                print("Пользователь ещё читает");
                        }
                        else
                        {
                                print(strip_tags($wor['status']));
                        }
                        print("</td></tr>");
                        $w = $w + 1;
                }
        }
}
print("</div></div>");
if (isset($_POST['deleter']))
{
        print('<div class="b" name="sl">');
        if (empty($_POST['del']))
        {
          print('<div class="error">Введите id пользователя, которого хотите удалить</div>');
        }
        else
        {
                if ($_POST['del'] == '1')
                {
                        print('Нельзя удалить пользователя с привилегией Главный администратор');
                }
                else
                {
                        $stmt = $db->prepare('SELECT * FROM bk_users WHERE id = ?');
                        $stmt->execute([$_POST['del']]);
                        if ($stmt->rowCount() == 0)
                        {
                                print('Пользователя с таким id не существует');
                        }
                        else
                        {
                                $stmt = $db->prepare('DELETE FROM bk_users WHERE id = ?');
                                $stmt->execute([$_POST['del']]);
                                $stmt = $db->prepare('DELETE FROM roles WHERE id = ?');
                                $stmt->execute([$_POST['del']]);
                                print('Пользователь успешно удалён');
                        }
                }
        }
}
if ($_SERVER['PHP_AUTH_USER']=='mainadmin')
{
?>
<form action='' method='post'>
Удаление пользователя <br />
Введите id пользователя, которого хотите удалить <input name='del' />
<input name='deleter' type='submit' value='Удалить пользователя' />
</form></div>
<div class='b' name='sl'>
Схема базы<br />
<img class='q' src='https://raw.githubusercontent.com/AVAKINSHIN/AVAKINSHIN.github.io/refs/heads/main/bookmagazine/image.png'></img>
  </div>
<?php
}
print("<div name='sl'>");
if (isset($_COOKIE['smener']))
{
        if ($_COOKIE['smener'] == '1')
        {
                print('Роль пользователя успешно сменена<br />');
                setcookie('smener', '');
        }
        else
        {
                printf('<div class="error">%s</div>', strip_tags($_COOKIE['smener']));
        }
}
?>
<form action="" method="post" class="b">
Смена роли юзера<br />
Введите id пользователя, которому хотите сменить роль
<input name='idsmena' <?php if (isset($_COOKIE['smener'])) {print("class='error'");} ?>></input><br />
<input type="radio" value="author" name="role" class='o'>Писатель</input><br />
<input type="radio" value="user" name="role" class='o'>Читатель</input><br />
<input type='radio' value='admin' name='role' class='o'>Администратор</input><br />
<input type="submit" name='smena' value="Сменить роль" />
</form>
</div>
</div>
</body>
</html>
