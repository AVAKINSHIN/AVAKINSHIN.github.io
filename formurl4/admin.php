<?php
include("functions.php");
/**
 * Задача 6. Реализовать вход администратора с использованием
 * HTTP-авторизации для просмотра и удаления результатов.
 **/

// Пример HTTP-аутентификации.
// PHP хранит логин и пароль в суперглобальном массиве $_SERVER.
// Подробнее см. стр. 26 и 99 в учебном пособии Веб-программирование и веб-сервисы.

if (empty($_SERVER['PHP_AUTH_USER']) ||
    empty($_SERVER['PHP_AUTH_PW']) ||
    $_SERVER['PHP_AUTH_USER'] != 'admin' ||
    $_SERVER['PHP_AUTH_PW'] != '123') {
  header('HTTP/1.1 401 Unanthorized');
  header('WWW-Authenticate: Basic realm="My site"');
  print('<h1>401 Требуется авторизация</h1>');
  exit();
}

print('Вы успешно авторизовались и видите защищенные паролем данные. <br />');
print("Наши юзеры <br />");
$t = 1;
$stmt=$db->prepare("SELECT * FROM person where id=?");
$stmt->execute([strval($t)]);
if ($stmt->rowCount()==0)
{
        print("У нас нет юзеров");
}
else
{
        tableheadbilder(array('id', 'fio', 'email', 'year', 'gender', 'phone', 'biography', 'accept'));
        $r = 1;
        while ($r!=0)
        {
                $row=$stmt->fetch(PDO::FETCH_ASSOC);
                print("<tr>");
                tablebodybilder($row);
                $t = $t + 1;
                $stmt=$db->prepare("SELECT * FROM person where id=?");
                $stmt->execute([strval($t)]);
                if ($stmt->rowCount()==0)
                {
                        $r = 0;
                        print("</table>");
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
