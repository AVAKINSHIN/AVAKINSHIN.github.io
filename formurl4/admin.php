<?php
include("functions.php");
if (empty($_SERVER['PHP_AUTH_USER']) ||
    empty($_SERVER['PHP_AUTH_PW'])) {
  header('HTTP/1.1 401 Unanthorized');
  header('WWW-Authenticate: Basic realm="My site"');
  print('<h1>401 Требуется авторизация</h1>');
  exit();
}
$stmt=$db->prepare("SELECT * FROM admins where login = ?");
$stmt->execute([$_SERVER['PHP_AUTH_USER']]);
if ($stmt->rowCount()==0)
{
        header('HTTP/1.1 401 Unanthorized');
        header('WWW-Authenticate: Basic realm="My site"');
        print('<h1>401 Требуется авторизация</h1>');
        exit();
}
$row=$stmt->fetch(PDO::FETCH_ASSOC);
if (!password_verify($_SERVER['PHP_AUTH_PW'], $row['pass']))
{
  $r = 1; $t=intval($row['id']) + 1;
  while ($r!=0)
  {
        $stmt=$db->prepare("SELECT * FROM admins where login = ? and id>=?");
        $stmt->execute([$_SERVER['PHP_AUTH_USER'], strval($t)]);
        if ($stmt->rowCount()==0)
        {
                header('HTTP/1.1 401 Unanthorized');
                header('WWW-Authenticate: Basic realm="My site"');
                print('<h1>401 Требуется авторизация</h1>');
                exit();
        }
        $stmt=$db->prepare("SELECT * FROM admins where login = ? and id=?");
        $stmt->execute([$_SERVER['PHP_AUTH_USER'], strval($t)]);
        if ($stmt->rowCount()==0)
        {
                $t = $t + 1;
        }
        else
        {
                $row=$stmt->fetch(PDO::FETCH_ASSOC);
                if (password_verify($_SERVER['PHP_AUTH_PW'], $row['pass']))
                {
                        $r = 0;
                }
                else
                {
                        $t = $t + 1;
                }
        }
  }
}
if (isset($_COOKIE['t']))
{
        $languages=empty($_COOKIE['language_value'])?array():explode("|", $_COOKIE['language_value']);
        if (isset($_POST['exitt']))
        {
                setcookie('t', '');
                header("Location: admin.php");
        }
        if (isset($_POST['saver']))
        {
                include("errortest.php");
                   if ($errors)
                   {
                        header('Location: admin.php');
                        exit();
                   }
                   updateDB($_POST['fio'], $_POST['phone'], $_POST['email'], $_POST['year'], $_POST['gender'], $_POST['biography'], $_POST['accept'],
                   $_COOKIE['t'], $_POST['language'], $db);
                   header("Location: admin.php");
                   exit();
        }
        include("checkerrors.php");
        $values = grabTegs($_COOKIE['t'], $db);
        include("form.php");
?>
<form action="" method="post">
<input type="submit" value="Выйти из режима редактирования" name="exitt" />
</form>
<?php
        exit();
}
if (isset($_POST['delete']))
{
        if (!empty($_POST['vazelin']) && is_numeric($_POST['vazelin']))
        {
                $stmt = $db->prepare("DELETE FROM person WHERE id=?");
                $stmt->execute([$_POST['vazelin']]);
                if ($stmt->rowCount()==0)
                    {
                        setcookie('v', 'Данного пользователя не существует. Внимательно посмотрите в таблице.', time() + 30 * 60);
                }
                $stmt = $db->prepare("DELETE FROM person_language WHERE id_p=?");
                $stmt->execute([$_POST['vazelin']]);
                $stmt = $db->prepare("DELETE FROM users WHERE id_user=?");
                $stmt->execute([$_POST['vazelin']]);
        }
        else
        {
                if (empty($_POST['vazelin']))
                {
                        setcookie('v', 'Удаляемый пользователь не может быть пустым.', time() + 30 * 60);
                }
                else
                {
                        setcookie('v', 'Здесь должно быть числовое значение.', time() + 30 * 60);
                }
        }
        header("Location: admin.php");
}
if (isset($_POST['update']))
{
         if (!empty($_POST['vazeliny']) && is_numeric($_POST['vazeliny']))
        {
                $stmt = $db->prepare("SELECT * FROM person WHERE id=?");
                $stmt->execute([$_POST['vazeliny']]);
                if ($stmt->rowCount()==0)
                {
                        setcookie('vy', 'Данного пользователя не существует. Внимательно посмотрите в таблице.', time() + 30 * 60);
                }
                else
                {
                        setcookie('t', $_POST['vazeliny']);
                }
         }
         else
         {
                if (empty($_POST['vazeliny']))
                {
                        setcookie('vy', 'Изменяемый пользователь не может быть пустым.', time() + 30 * 60);
                }
                else
                {
                        setcookie('vy', 'Здесь должно быть числовое значение.', time() + 30 * 60);
                }
             }
          header("Location: admin.php");
}
include('styles.php');
print('Вы успешно авторизовались и видите защищенные паролем данные. <br />');
print("Наши юзеры <br />");
$t = 1;
$stmt=$db->prepare("SELECT * FROM person where id>=?");
$stmt->execute([strval($t)]);
if ($stmt->rowCount()==0)
{
        print("У нас нет юзеров");
}
else
{
        tableheadbilder(array('id', 'fio', 'email', 'year', 'gender', 'phone', 'biography', 'accept'));
        $r = 1;
        while ($r!= 0)
        {
                $stmt=$db->prepare("SELECT * FROM person where id=?");
                $stmt->execute([strval($t)]);
                if ($stmt->rowCount()==0)
                {
                        $hghg=$db->prepare("SELECT * FROM person where id>=?");
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
                        $row=$stmt->fetch(PDO::FETCH_ASSOC);
                        tablebodybilder($row);
                        $t = $t + 1;
                }
        }
        $r = 1;
        print("Статистика языков: <br />");
        tableheadbilder(array('Название языка', 'Количество фанатов языка'));
        foreach(array('Pascal', 'C', 'C++', 'JavaScript', 'PHP', 'Python', 'Java', 'Haskel', 'Clojure', 'Scala') as $d)
        {
                tablebodybilder(array($d, selectLanguage($db, strval($r))));
                $r = $r + 1;
        }
        print("</table>");
}
?>
<form action="index.php" method="post">
<input type="submit" value="Создать новую запись" />
</form>
<?php
if (isset($_COOKIE['v']))
{
        print("<div class='error'>");
        print($_COOKIE['v']);
        print("</div>");
        setcookie('v', '');
}
?>
<form action="" method="post">
Введите сюда id пользователя, которого хотите удалить <input name="vazelin" class='<?php if (isset($_COOKIE['v'])) print('error'); else print('n');?>' />
<input type="submit" name="delete" value="Удалить запись юзера" />
</form>
<?php
if (isset($_COOKIE['vy']))
{
        print("<div class='error'>");
        print($_COOKIE['vy']);
        print("</div>");
        setcookie('vy', '');
}
?>
<form action="" method="post">
Введите сюда id юзера, данные которого хотите изменить <input name="vazeliny" class='<?php if (isset($_COOKIE['vy'])) print('error'); else print('n');?>' />
<input type="submit" name="update" value="Изменить запись юзера" />
</form>
</body>
</html>
