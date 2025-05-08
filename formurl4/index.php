<?php
header('Content-Type: text/html; charset=UTF-8');
include("functions.php");
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
  $errors = array();
  $messages = array();
  $languages=empty($_COOKIE['language_value'])?array():explode("|", $_COOKIE['language_value']);
  if (!empty($_COOKIE['save']))
  {
    setcookie('save', '', 100000);
    $messages[] = '1';
    if (!empty($_COOKIE['pass']))
    {
        $messages[] = sprintf('Вы можете <a href="login.php">войти</a> с логином <strong>%s</strong> и паролем <strong>%s</strong> для изменения данных.',
                strip_tags($_COOKIE['login']), strip_tags($_COOKIE['pass']));
        $messages[] = sprintf('Ваш uid для входа: <strong>%s</strong>.', strip_tags($_COOKIE['uid']));
    }
    setcookie('login', '', 100000);
    setcookie('pass', '', 100000);
  }
  include("checkerrors.php");
  foreach (array('fio', 'phone', 'email', 'year', 'gender', 'biography', 'accept') as $v)
  {
        $values[$v] = empty($_COOKIE[$v.'_value']) ? '' : strip_tags($_COOKIE[$v.'_value']);
  }
  include('form.php');
?>
<form action="login.php" method="post">
<input type="submit" value="Эта форма предназначена для новых гостей. Если хотите зайти в свою учётную запись и изменить данные, нажмите сюда." />
</form>
<?php
}
else
{
  include("errortest.php");
if ($errors)
  {
        header('Location: index.php');
        exit();
  }
  foreach (array('fio', 'phone', 'email', 'year', 'gender', 'language', 'biography') as $v)
  {
          setcookie($v.'_error', '', 100000);
  }
  $login = $_POST['fio'];
  $pass = uniqid(mt_rand(1010, 9898), true);
  $uid = password_hash($pass, PASSWORD_DEFAULT);
  setcookie('login', $login, time() + 24 * 60 * 60);
  setcookie('pass', $pass, time() + 24 * 60 * 60);
  try
  {
    $stmt = $db->prepare("INSERT INTO person SET fio = ?, email = ?, year = ?, gender = ?, phone =  ?, biography = ?, accept = ?");
    $stmt->execute([$_POST['fio'], $_POST['email'], $_POST['year'], $_POST['gender'], $_POST['phone'], $_POST['biography'], $_POST['accept']]);
    $last_id = $db->lastInsertId();
    foreach ($_POST['language'] as $ability)
    {
          $stmt = $db->prepare("INSERT INTO person_language SET id_l = ?, id_p = ?");
          $stmt->execute([$ability, $last_id]);
    }
    $stmt = $db->prepare("INSERT INTO users SET name = ?, pass = ?, id_user = ?");
    $stmt->execute([$login, $uid, $last_id]);
    setcookie('uid', $last_id, time() + 24 * 60 * 60);
  }
  catch(PDOException $e)
  {
        print('Error : ' . $e->getMessage());
        exit();
  }
  setcookie('save', '1');
  header('Location: index.php');
}
?>
