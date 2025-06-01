<?php
if (isset($_POST['exitu']))
{
        setcookie('page', '');
        header("Location: index.php");
        exit();
}
if (isset($_POST['exits']))
{
        setcookie('page', 'mybooks');
        header("Location: index.php");
        exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
include("styles.php");

?>
<title>Страница выбора книги</title>
</head><body>
<div class="c">
<?php
if (isset($_COOKIE['v_t']))
{
        if (isset($_COOKIE['b_p']))
        {
                print("<div class='b'>");
                print($_COOKIE['b_p']);
                print("</div>");
                setcookie("b_p", "");
        }
        include("database.php");
        $stmt=$db->prepare("SELECT * FROM bk_books where id=?");
        $stmt->execute([$_COOKIE['v_t']]);
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        if ($row['author'] == $_SESSION['login'])
        {
                setcookie('prolog', '1');
        }
        printbook($row, $_SESSION['login'], $db, 0);
}
else
{
        print("Книга не выбрана");
}
?>
<form action='' method='post' class="b">
Куда хотите вернуться???<br />
<input type='submit' value='На главную' name='exitu' />
<input type='submit' value='В личный кабинет' name='exits' />
</form>
</div>
</body>
</html>
<?php
}
else
{
        if (isset($_COOKIE['v_t']))
        {
                include("database.php");
                if (isset($_COOKIE['prolog']))
                {
                        $stmt=$db->prepare("SELECT * FROM bk_books WHERE id = ?");
                        $stmt->execute([$_COOKIE['v_t']]);
                        $row=$stmt->fetch(PDO::FETCH_ASSOC);
                        foreach(array('title', 'genre', 'author', 'publication_year', 'image_url', 'gar_url') as $d)
                        {
                                setcookie($d.'_er', $row[$d]);
                        }
                        setcookie("prolog", "");
                        setcookie("page", "add");
                        header("Location: index.php");
                        exit();
                }
                else
                {
                        $stmt=$db->prepare("SELECT * from bk_exchanges where bkreqid = ? and bkrequester = ? and status = ?");
                        $stmt->execute([$_COOKIE['v_t'], $_SESSION['uid'], 'active']);
                        if ($stmt->rowCount()!=0)
                        {
                                $stmt=$db->prepare("UPDATE bk_exchanges SET status = ? where bkreqid = ? and bkrequester = ? and status = ?");
                                $stmt->execute([date('y/m/d'), $_COOKIE['v_t'], $_SESSION['uid'], 'active']);
                                setcookie("b_p", "Вы успешно вернули книгу обратно", time() + 30 * 60);
                        }
                        else
                        {
                                $stmt=$db->prepare("INSERT INTO bk_exchanges SET bkreqid = ?, bkrequester = ?, status = ?, exchange_date = ?");
                                $stmt->execute([$_COOKIE['v_t'], $_SESSION['uid'], 'active', date('y/m/d')]);
                                setcookie("b_p", "Вы успешно взяли книгу почитать", time() + 30 * 60);
                        }
                        header("Location: index.php");
                        exit();
                }
        }
}
?>
