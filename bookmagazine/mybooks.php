<?php
if (isset($_POST['exity']) || empty($_SESSION['login']))
{
        setcookie("page", "");
        header("Location: index.php");
        exit();
}
if (isset($_POST['adder']))
{
        setcookie("page", "add");
        header("Location: index.php");
        exit();
}
if (isset($_COOKIE['lemon']))
{
        for ($d = 1; $d <= $_COOKIE['lemon']; $d = $d + 1)
        {
                if (isset($_POST[$d]))
                {
                        setcookie('v_t', $d);
                        setcookie('page', 'bookpage');
                        header("Location: index.php");
                        exit();
                }
        }
}
include("styles.php");
?>
</head>
<body>
<div class="c">
Здравствуйте, <?php print($_SESSION['login']); ?> <br />
<?php if ($_SESSION['role'] == 'author' || $_SESSION['role'] == 'admin')
{
        if ($_SESSION['role'] == 'author')
        {
                print("Здесь отобразятся книги, выставленные вами, а также те, которые вы взяли почитать.<br />");
        }
        else
        {
                print("Вы можете управлять библиотекой, добавляя и удаляя книги любого автора в системе.<br />");
                print("В целях защиты ваших прав админа, вам придётся ввести ваши имя и пароль ещё раз.<br />");
        }
?>
<form action='' method='post' class='b'>
<input type='submit' name='adder' value='Добавить книгу' />
</form>
<?php
}
else
{
        print("Здесь отобразятся книги, которые вы взяли почитать.<br />");
}
?>
<?php
include("database.php");
$stmt = $db->prepare("SELECT * FROM bk_exchanges where status=? and bkrequester = ?");
$stmt->execute(['active', $_SESSION['uid']]);
if ($stmt->rowCount()==0)
{
        print("У вас нет активных книг. Вернитесь на главную и возьмите книгу. <br />");
}
else
{
        print("Вы взяли почитать<br /><table border='1'><tr>");
        $r = 1; $t = 1; $u = 0;
        while ($r != 0)
        {
                $stmt=$db->prepare("SELECT * FROM bk_exchanges where status = ? and bkrequester = ? and id = ?");
                $stmt->execute(['active', $_SESSION['uid'], strval($t)]);
                if ($stmt->rowCount()==0)
                {
                        $stmt=$db->prepare("SELECT * FROM bk_exchanges where status = ? and bkrequester = ? and id >= ?");
                        $stmt->execute(['active', $_SESSION['uid'], strval($t)]);
                        if ($stmt->rowCount()==0)
                        {
                                setcookie('lemon', $t);
                                print("</tr></table>");
                                $r = 0;
                                break;
                        }
                        $t = $t + 1;
                }
                else
                {
                        $row=$stmt->fetch(PDO::FETCH_ASSOC);
                        $trtr=$db->prepare("SELECT * FROM bk_books where id = ?");
                        $trtr->execute([$row['bkreqid']]);
                        $wor=$trtr->fetch(PDO::FETCH_ASSOC);
                        print("<td class='z'>");
                        printbook($wor, $_SESSION['login'], $db, 0);
                        print("</td>");
                  $t = $t + 1; $u = $u + 1;
                        if ($u == 2)
                        {
                                print("</tr><tr>");
                        }
                }
        }
}
        print("Ваша история запросов книг<br />");
        $stmt = $db->prepare("SELECT * FROM bk_exchanges where bkrequester = ?");
        $stmt->execute([$_SESSION['uid']]);
        if ($stmt->rowCount()==0)
        {
                print("Пока тут пусто. <br />");
        }
        else
        {
                print("<table border='1'><tr>");
                $r = 1; $t = 1; $u = 0;
                while ($r != 0)
                {
                        $stmt=$db->prepare("SELECT * FROM bk_exchanges where bkrequester = ? and id = ?");
                        $stmt->execute([$_SESSION['uid'], strval($t)]);
                        if ($stmt->rowCount()==0)
                        {
                                $stmt=$db->prepare("SELECT * FROM bk_exchanges where bkrequester = ? and id >= ?");
                                $stmt->execute([$_SESSION['uid'], strval($t)]);
                                if ($stmt->rowCount()==0)
                                {
                                        print("</tr></table>");
                                        $r = 0;
                                        break;
                                }
                                $t = $t + 1;
                        }
                        else
                        {
                                $row=$stmt->fetch(PDO::FETCH_ASSOC);
                                $trtr=$db->prepare("SELECT * FROM bk_books where id = ?");
                                $trtr->execute([$row['bkreqid']]);
                                $wor=$trtr->fetch(PDO::FETCH_ASSOC);
                                print("<td class='z'>");
                                printbook($wor, $_SESSION['login'], $db, 1);
                                print("Вы начали читать эту книгу: ");
                                print($row['exchange_date']);
                                print("<br />");
                          if ($row['status'] == 'active')
                                {
                                        print("Вы сейчас читаете данную книгу");
                                }
                                else
                                {
                                        print("Вы закончили читать данную книгу: ");
                                        print($row['status']);
                                }
                                print("</td>");
                                $t = $t + 1; $u = $u + 1;
                                if ($u == 2)
                                {
                                        print("</tr><tr>");
                                }
                        }
                }
        }
if ($_SESSION['role'] == 'author' || $_SESSION['role'] == 'admin')
{
        print("Ваши творения<br />");
        $stmt = $db->prepare("SELECT * FROM bk_books where author = ?");
        $stmt->execute([$_SESSION['login']]);
        if ($stmt->rowCount()==0)
        {
                print("У вас пока нету книг");
        }
        else
        {
                $w = printbookbase($db, $_SESSION['login'], 0, 0, $_SESSION['login']);
                print("Ваши фанаты<br />"); $t = 1; $r = 1;
                print("<table border='1'>");
                print("<tr>");
                foreach (array("Имя фаната", "Книга", "Дата начала прочтения", "Дата окончания прочтения") as $d)
                {
                      print("<td class='z'>");
                      print($d);
                      print("</td>");
                }
                print("</tr>");
                while ($r!= 0)
                {
                        $stmt=$db->prepare("SELECT * FROM bk_books where id = ? and author = ?");
                        $stmt->execute([strval($t), $_SESSION['login']]);
                        if ($stmt->rowCount()==0)
                        {
                          $hghg=$db->prepare("SELECT * FROM bk_books where id>=? and author = ?");
                                $hghg->execute([strval($t), $_SESSION['login']]);
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
                                $u = 1; $w = 1;
                                $hghg = $db->prepare("SELECT * FROM bk_exchanges where bkreqid = ?");
                                $hghg->execute([$row['id']]);
                                if ($hghg->rowCount()!=0)
                                {
                                        while ($u != 0)
                                        {
                                                $trtr=$db->prepare("SELECT * FROM bk_exchanges where bkreqid = ? and id = ?");
                                                $trtr->execute([$row['id'], strval($w)]);
                                                if ($trtr->rowCount()==0)
                                                {
                                                        $trtr=$db->prepare("SELECT * FROM bk_exchanges where bkreqid = ? and id >= ?");
                                                        $trtr->execute([$row['id'], strval($w)]);
                                                        if ($trtr->rowCount()==0)
                                                        {
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
                                                        print("<tr><td class='z'>");
                                                        print($yto['username']);
                                                        print("</td><td class='z'>");
                                                        print($row['title']);
                                                        print("</td><td class='z'>");
                                                        print($wor['exchange_date']);
                                                        print("</td><td class='z'>");
                                                  if ($wor['status']=='active')
                                                        {
                                                                print("Фанат ещё читает");
                                                        }
                                                        else
                                                        {
                                                                print($wor['status']);
                                                        }
                                                        print("</td></tr>");
                                                        $w = $w + 1;
                                                }
                                        }
                                }
                                $t = $t + 1;
                        }
                }
        }
}
?>
<form action="" method="post" style="position: absolute; left: 15px; top: 0">
<input type="submit" name="exity" value="Назад" style="width: 100px" />
</form>
</div>
</body>
</html>
