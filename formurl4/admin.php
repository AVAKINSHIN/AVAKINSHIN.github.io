<?php
include("functions.php");

$stmt=$db->prepare("SELECT * FROM admins where login = ?");
$stmt->execute([$_SERVER['PHP_AUTH_USER']]);
if (empty($_SERVER['PHP_AUTH_USER']) ||
    empty($_SERVER['PHP_AUTH_PW']) ||
    $stmt->rowCount()==0) {
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
        $stmt->execute([$_SERVER['PHP_AUTH_USER'], $strval($t)]);
        if ($stmt->rowCount()==0)
        {
                header('HTTP/1.1 401 Unanthorized');
                header('WWW-Authenticate: Basic realm="My site"');
                print('<h1>401 Требуется авторизация</h1>');
                exit();
        }
        $stmt=$db->prepare("SELECT * FROM admins where login = ? and id=?");
        $stmt->execute([$_SERVER['PHP_AUTH_USER'], $strval($t)]);
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
if (isset($_POST['delete']))
{
        if (!empty($_POST['vazelin']) && is_numeric($_POST['vazelin']))
        {
                $stmt = $db->prepare("DELETE FROM person WHERE id=?");
                $stmt->execute([$_POST['vazelin']]);
                $stmt = $db->prepare("DELETE FROM person_language WHERE id_p=?");
                $stmt->execute([$_POST['vazelin']]);
                $stmt = $db->prepare("DELETE FROM users WHERE id_user=?");
                $stmt->execute([$_POST['vazelin']]);
        }
        header("Location: admin.php");
}
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
<form action="" method="post">
Введите сюда id пользователя, которого хотите удалить <input name="vazelin" />
<input type="submit" name="delete" value="Удалить запись юзера" />
</form>
