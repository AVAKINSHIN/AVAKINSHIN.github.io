<?php
if (isset($_POST['exityr']))
{
        setcookie("page", "mybooks");
        header("Location: index.php");
        exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
        include("styles.php");
?>
<title>Библиотека: <?php if ($_SESSION['role']=='admin') print("кабинет для управления книгами"); else print("добавление новой книги"); ?></title>
</head>
<body>
<div class='b'>
<?php
        foreach(array('title', 'genre', 'author', 'publication', 'image_url', 'gar_url', 'token') as $e)
        {
                if (isset($_COOKIE[$e.'_ef']))
                {
                        printf('<div class="error"><strong>%s</strong></div>', strip_tags($_COOKIE[$e.'_ef']));
                }
        }
        if (isset($_COOKIE['save_ef']))
        {
                print(strip_tags($_COOKIE['save_ef']));
        }
?>
<form action="" method="post" class='c'>
<?php print("Форма ");if(isset($_COOKIE['v_t'])){print("изменения книги");} else print("добавления новой книги.");?>
<br />Название<input name="title" placeholder="Семь чудес света"
<?php if (isset($_COOKIE['title_ef'])) { print("class='error'"); }
if (isset($_COOKIE['title_er'])) { print("value='"); print(strip_tags($_COOKIE['title_er'])); print("'");}?>
                                                                                /><br />
<?php if($_SESSION['role']=='admin')
        {
                print('Автор<input name="author" placeholder="Иванов ИИ" '); if (isset($_COOKIE['author_ef'])) {print(' class="error" ');}
                if (isset($_COOKIE['author_er'])) { print("value='"); print(strip_tags($_COOKIE['author_er'])); print("'");}
                print('/><br />');
        }
?>
Жанр<input name="genre" placeholder="Рассказ"
<?php if (isset($_COOKIE['genre_ef'])) print("class='error'");
if (isset($_COOKIE['genre_er'])) { print("value='"); print(strip_tags($_COOKIE['genre_er'])); print("'");}?>
                                                /><br />
Год публикации<input name="publication_year" placeholder="1990"
  <?php if (isset($_COOKIE['publication_ef'])) print("class='error'");
if (isset($_COOKIE['publication_year_er'])) { print("value='"); print(strip_tags($_COOKIE['publication_year_er'])); print("'");} ?>
                                                                /><br />
Обложка книги<input name="image_url" placeholder="Дропните сюда url-ссылку на любое изображение"
<?php if (isset($_COOKIE['image_url_ef'])) print("class='error'");
if (isset($_COOKIE['image_url_er'])) { print("value='"); print(strip_tags($_COOKIE['image_url_er'])); print("'");}?>
                                                                                                  /><br />
Содержание книги<input name="gar_url" placeholder="Дропните сюда url-ссылку на содержание вашей книги"
<?php if (isset($_COOKIE['gar_url_ef'])) print("class='error'");
if (isset($_COOKIE['gar_url_er'])) { print("value='"); print(strip_tags($_COOKIE['gar_url_er'])); print("'");}
?>
                                                                                                        /><br />
<input type='hidden' name='token' value="<?php print($_SESSION['token']); ?>" />
<input type="submit" value="<?php if (isset($_COOKIE['v_t'])) { print('Изменить данные книги'); } else { print('Добавить книгу'); }?>" />
</form>
<?php   foreach(array('title', 'genre', 'author', 'publication', 'image_url', 'gar_url', 'save', 'token') as $e)
        {
                if (isset($_COOKIE[$e.'_ef']))
                {
                        setcookie($e.'_ef', '', 100000);
                }
        }
?>
<form action="" method="post">
<input type="submit" value="Вернуться к списку книг" name="exityr" />
</form>
<?php
        if ($_SESSION['role']=='admin')
        {
?>
<form action="admin.php" class="c">
<input type="submit" value="Кабинет админа" />
</form>
<?php
        }
?>
</div>
</body>
</html>
<?php
}
else
{
        foreach(array('title', 'genre', 'author', 'publication_year', 'image_url', 'gar_url') as $d)
        {
                if (isset($_COOKIE[$d.'_er']))
                  {
                        setcookie($d.'_er', '');
                }
        }
        $q = FALSE;
        foreach (array('title', 'genre') as $e)
        {
                if(empty($_POST[$e]) || strlen($_POST[$e]) > 128 || strpos($_POST[$e.'_url'], '{') !== false ||
                        strpos($_POST[$e.'_url'], '}') !== false || strpos($_POST[$e.'_url'], '[') !== false ||
                        strpos($_POST[$e.'_url'], ']') !== false || strpos($_POST[$e.'_url'], ';') !== false)
                {
                        if (empty($_POST[$e]))
                        {
                                setcookie($e.'_ef', 'Поле '.$e.' не должно быть пустым<br />', time() + 30 * 60);
                        }
                        else
                        {
                                if (strlen($_POST[$e]) > 128)
                                {
                                        setcookie($e.'_ef', 'Поле '.$e.' не должно быть длиннее 128 символов<br />', time() + 30 * 60);
                                }
                                else
                                {
                                setcookie($e.'_ef', 'Поле '.$e.' должно содержать только буквы, цифры, пробелы, запятые и точки<br />', time() + 30 * 60);
                                }
                        }
                        $q = TRUE;
                }
        }
        if (strlen($_POST['author']) > 128)
        {
                setcookie('author_ef', 'Поле автор не должно быть длиннее 128 символов<br />', time() + 30 * 60);
                $q = TRUE;
        }
        if (empty($_POST['publication_year'])||!is_numeric($_POST['publication_year'])||$_POST['publication_year']>2025)
        {
                if (empty($_POST['publication_year']))
                {
                        setcookie('publication_ef', 'Поле Год издания не должно быть пустым<br />', time() + 30 * 60);
                }
                else
                {
                        if ($_POST['publication_year']>2025)
                        {
                           setcookie('publication_ef', 'Книга не может быть из будущего<br />', time() + 30 * 60);
                        }
                  else
                        {
                           setcookie('publication_ef', 'Поле Год издания должно быть числом<br />', time() + 30 * 60);
                        }
                }
                $q = TRUE;
        }
        if (empty($_POST['token']) || $_POST['token'] != $_SESSION['token'])
        {
                setcookie('token_ef', 'Ваш сеанс истёк. Попробуйте перезайти', time() + 30 * 60);
                $q = TRUE;
        }
        foreach (array('image', 'gar') as $e)
        {
                if (empty($_POST[$e.'_url']) || strlen($_POST[$e.'_url']) > 250 || strpos($_POST[$e.'_url'], '{') !== false ||
                        strpos($_POST[$e.'_url'], '}') !== false || strpos($_POST[$e.'_url'], '[') !== false || strpos($_POST[$e.'_url'], ']') !== false)
                {
                        if (empty($_POST[$e.'_url']))
                        {
                                if ($e=='image')
                                {
                                        setcookie('image_url_ef', 'Выберите ссылку на изображение<br />', time() + 30 * 60);
                                }
                                else
                                {
                                        setcookie('gar_url_ef', 'Выберите ссылку на содержание книги<br />', time() + 30 * 60);
                                }
                        }
                        else
                        {
                                if (strlen($_POST[$e.'_url']) > 250)
                                {
                                        setcookie($e.'_url_ef', 'Ссылка не должна превышать 250 символов<br />', time() + 30 * 60);
                                }
                                else
                                {
                                        setcookie($e.'_url_ef', 'Ссылка не должна содержать небезопасные символы []{}<br />', time() + 30 * 60);
                                }
                        }
                        $q = TRUE;
                }
        }
        if ($q==TRUE)
        {
                header("Location: index.php");
                exit();
          }
        $aq = $_SESSION['login'];
        include("database.php");
        if ($_SESSION['role'] == 'admin')
        {
                if (strlen($_POST['author']) > 128)
                {
                        setcookie('author_ef', 'Поле автор не должно превышать 128 символов.<br />', time() + 30 * 60);
                        header("Location: index.php");
                        exit();
                }
                $stmt=$db->prepare("SELECT * FROM bk_users WHERE username = ?");
                $stmt->execute([$_POST['author']]);
                if ($stmt->rowCount()==0)
                {
     $c = 'Пользователь должен находиться в базе.<br /> Зарегистрированных пользователей можете посмотреть в <a href="admin.php">кабинете админа</a><br />';
                        setcookie('author_ef', $c, time() + 30 * 60);
                        header("Location: index.php");
                        exit();
                }
                $aq = $_POST['author'];
                if ($_POST['author']!=$_SESSION['login'])
                {
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        $stmt = $db->prepare("SELECT * FROM roles WHERE id = ?");
                        $stmt->execute([$row['id']]);
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        if ($row['role']!='admin')
                        {
                                $stmt = $db->prepare("UPDATE roles SET role=?");
                                $stmt->execute(["author"]);
                        }
                }
        }
        if (isset($_COOKIE['v_t']))
        {
                $stmt = $db->prepare("UPDATE bk_books SET title = ?, author = ?, genre = ?, publication_year = ?, image_url = ?, gar_url = ? where id = ?");
               $stmt->execute([$_POST['title'], $aq, $_POST['genre'], $_POST['publication_year'], $_POST['image_url'], $_POST['gar_url'], $_COOKIE['v_t']]);
                setcookie('save_ef', 'Ваша книга успешно изменена. Можете добавить новые книги.<br />', time() + 30 * 60);
                setcookie('v_t', '');
        }
        else
        {
                $stmt = $db->prepare("INSERT INTO bk_books SET title = ?, author = ?, genre = ?, publication_year = ?, image_url = ?, gar_url = ?");
                $stmt->execute([$_POST['title'], $aq, $_POST['genre'], $_POST['publication_year'], $_POST['image_url'], $_POST['gar_url']]);
                setcookie('save_ef', 'Ваша книга успешно сохранена в базу<br />', time() + 30 * 60);
          }
        header("Location: index.php");
        exit();
}
?>
