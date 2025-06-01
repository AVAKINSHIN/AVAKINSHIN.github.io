<?php
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
if (isset($_POST['exit']) || empty($_SESSION['login']))
{
        session_destroy();
        header("Location: index.php");
        exit();
}
if (isset($_POST['added']))
{
        setcookie("page", "mybooks");
        header("Location: index.php");
        exit();
}
if (isset($_POST['smenarol']))
{
        setcookie("page", "smenarol");
        header("Location: index.php");
        exit();
}
include("styles.php");
?>
<title>Библиотека: главная страница пользователя <?php print($_SESSION['login']); ?></title>
</head>
<body>
<div class="c" style="width: 98%">
Мы рады видеть вас снова, <?php print($_SESSION['login']); ?>
<br />
Поиск книг
<form action="" method="post" class="b">
Название <input name="title" /><br /> Жанр <input name="genre" />
<input type="submit" name="search_book" value="Найти книгу" />
</form>
<?php
include("database.php");
if (isset($_POST['search_book']) && (!empty($_POST['genre']) || !empty($_POST['title'])))
{
        if (empty($_POST['title']))
        {
                $stmt = $db->prepare("SELECT * FROM bk_books where genre = ?");
                $stmt->execute([$_POST['genre']]);
                if ($stmt->rowCount()==0)
                {
                        print("Нету книг по вашему запросу");
                }
                else
                {
                        $w = printbookbase($db, 0, $_POST['genre'], 0, $_SESSION['login']);
                        setcookie('lemon', $w);
                }
        }
        else
        {
                if (empty($_POST['genre']))
                {
                        $stmt = $db->prepare("SELECT * FROM bk_books where title = ?");
                        $stmt->execute([$_POST['title']]);
                        if ($stmt->rowCount() == 0)
                        {
                                print("Нету книг по вашему запросу");
                        }
                        else
                        {
                                $w = printbookbase($db, 0, 0, $_POST['title'], $_SESSION['login']);
                                setcookie('lemon', $w);
                        }
                }
                else
                {
                        $stmt = $db->prepare("SELECT * FROM bk_books where title = ? and genre = ?");
                        $stmt->execute([$_POST['title'], $_POST['genre']]);
                        if ($stmt->rowCount()==0)
                        {
                                print('Нету книг по вашему запросу');
                        }
                        else
                        {
                                $w = printbookbase($db, 0, $_POST['genre'], $_POST['title'], $_SESSION['login']);
                                setcookie('lemon', $w);
                        }
                  }
        }
}
else
{
         $stmt = $db->prepare("SELECT ? FROM bk_books");
         $stmt->execute(["*"]);
         if ($stmt->rowCount()==0)
         {
                print("Пока библиотека пуста");
         }
         else
         {
                $w = printbookbase($db, 0, 0, 0, $_SESSION['login']);
                setcookie('lemon', $w);
         }
}
?>
<form action="" method="post">
<input type="submit" name="added" value="Мои книги" />
</form>
<form action="" method="post" class="b">
<input type="submit" value="<?php if ($_SESSION['role']=='admin') print("Управление ролями"); else print("Изменить роль");?>" name="smenarol" />
</form>
<form action="" method="post" style="position: absolute; left: 15px; top: 0">
<input type="submit" value="Назад" name="exit" style="width: 100px;"/>
</form>
</div>
</body>
</html>
