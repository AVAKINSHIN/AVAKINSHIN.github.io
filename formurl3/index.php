<?php
header('Content-Type: text/html; charset=UTF-8');
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
    }
    setcookie('login', '', 100000);
    setcookie('pass', '', 100000);
  }
  $errors = array();
  foreach (array('fio', 'phone', 'email', 'year', 'gender', 'language', 'biography', 'accept') as $v)
  {
        $errors[$v] = !empty($_COOKIE[$v.'_error']);
        if ($errors[$v])
        {
                $messages[] = sprintf('<div class="error"><strong>%s</strong></div>', strip_tags($_COOKIE[$v.'_error']));
                setcookie($v.'_error', '', 100000);
                setcookie($v.'_value', '', 100000);
        }
  }
  $values = array();
  foreach (array('fio', 'phone', 'email', 'year', 'gender', 'biography', 'accept') as $v)
  {
        $values[$v] = empty($_COOKIE[$v.'_value']) ? '' : strip_tags($_COOKIE[$v.'_value']);
  }
  if (empty($errors) && !empty($_COOKIE[session_name()]) && session_start() && !empty($_SESSION['login']))
  {
    $db = new PDO('mysql:host=localhost;dbname=uXXXXX', 'uXXXXX', 'XXXXXXX',
          [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
          $stmt=$db->prepare("SELECT fio, email, year, gender, phone, biography, accept FROM person WHERE id=?");
          $stmt->execute([$_SESSION['uid']);
          if ($stmt->rowCount()!=0)
          {
                  $row=$stmt->fetch(PDO::FETCH_ASSOC);
                  foreach (array('fio', 'phone', 'email', 'year', 'gender', 'biography', 'accept') as $v)
                  {
                        $values[$v] = strip_tags($row[$v]);
                  }
          }
    printf('Вход с логином %s, uid %d', $_SESSION['login'], $_SESSION['uid']);
  }
  include('form.php');
}
else
{
  $errors = FALSE;
  if (empty($_POST['fio'])||preg_match('~[0-9]+~', $_POST['fio'])||strlen($_POST['fio'])>128||!preg_match('/^[A-Za-zА-Яа-яЁё\s]{1,150}$/u', $_POST['fio']))
  {
    if (empty($_POST['fio']))
    {
        setcookie('fio_error', 'Поле ФИО не должно быть пустым.', time() + 24 * 60 * 60);
    }
    elseif (preg_match('~[0-9]+~', $_POST['fio']))
    {
        setcookie('fio_error', 'Поле ФИО не должно содержать цифр', time() + 24 * 60 * 60);
    }
    elseif (strlen($_POST['fio']) > 128)
    {
        setcookie('fio_error', 'ФИО слишком большое. Пожалуйста, введите короче (макс длина 128 символов).', time() + 24 * 60 * 60);
    }
    else
    {
            setcookie('fio_error', 'Поле ФИО не должно содержать спецсимволы', time() + 24 * 60 * 60);
    }
    $errors = TRUE;
  }
  if (!preg_match('/^\+?[1-9][0-9]{7,14}$/', $_POST['phone']) || empty($_POST['phone']))
  {
       if (empty($_POST['phone']))
       {
               setcookie('phone_error', 'Номер телефона не должен быть пустым.', time() + 24 * 60 * 60);
       }
       else
       {
               setcookie('phone_error', 'Номер должен соответствовать международному формату.', time() + 24 * 60 * 60);
       }
       $errors = TRUE;
  }
  if (!preg_match('~@~', $_POST['email']) || empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
  {
       if (empty($_POST['email']))
       {
               setcookie('email_error', 'Email не должен быть пустым.', time() + 24 * 60 * 60);
       }
       elseif (!preg_match('~@~', $_POST['email']))
       {
               setcookie('email_error', 'Email должен содержать \'@\'.', time() + 24 * 60 * 60);
       }
       else
         {
               setcookie('email_error', 'Email должен соответствовать формату X@Y.Z', time() + 24 * 60 * 60);
       }
       $errors = TRUE;
  }
  if (empty($_POST['year'])||!is_numeric($_POST['year'])||!preg_match('/^\d+$/', $_POST['year'])||intval($_POST['year'])<1900||intval($_POST['year'])>2025)
  {
            if (empty($_POST['year']))
            {
                    setcookie('year_error', 'Поле год рождения не должно быть пустым.', time() + 24 * 60 * 60);
            }
            elseif (!is_numeric($_POST['year'])||!preg_match('/^\d+$/', $_POST['year']))
            {
                    setcookie('year_error', 'Поле год рождения должно быть в числовом формате в диапазоне от 1900 до 2025.', time() + 24 * 60 * 60);
            }
            elseif (intval($_POST['year']) < 1900)
            {
                    setcookie('year_error', 'Не верю, что вы смогли прожить так долго. Попробуйте ещё раз (минимальный год - 1900)', time() + 24 * 60 * 60);
            }
            else
            {
                    setcookie('year_error', 'Не верю, что существует машина времени. Попробуйте ещё раз (текущий год - 2025).', time() + 24 * 60 * 60);
            }
            $errors = TRUE;
  }
  if (empty($_POST['gender']) || ($_POST['gender']!='М' && $_POST['gender']!='Ж'))
  {
        if (empty($_POST['gender']))
        {
                setcookie('gender_error', "Обязательно выберите поле пол.", time() + 24 * 60 * 60);
        }
        else
        {
                setcookie('gender_error', "Думали, я не замечу, как вы пытаетесь меня взломать???", time() + 24 * 60 * 60);
        }
        $errors = TRUE;
  }
  if (!is_array($_POST['language']))
  {
        $_POST['language'] = array();
  }
  foreach ($_POST['language'] as $k => $v)
  {
        if (intval($v) < 1 || intval($v) > 11)
            unset($_POST['language'][$k]);
  }
  if (empty($_POST['language']))
  {
        setcookie('language_error', 'Выберите хотя бы один язык программирования.', time() + 24 * 60 * 60);
        $errors = TRUE;
  }
  if (strlen($_POST['biography']) > 200 || empty($_POST['biography']) || !preg_match('/^[A-Za-zА-Яа-яЁё\s,.]{1,150}$/u', $_POST['biography']))
  {
        if (empty($_POST['biography']))
        {
                setcookie('biography_error', 'Биография не может быть пустой.', time() + 24 * 60 * 60);
        }
        elseif (strlen($_POST['biography']) > 200)
        {
                setcookie('biography_error', 'Максимальная длина биографии - 200 символов.', time() + 24 * 60 * 60);
        }
        else
        {
                setcookie('biography_error', 'Биография не должна содержать спецсимволы', time() + 24 * 60 * 60);
        }
        $errors = TRUE;
  }
  if (empty($_POST['accept']) || ($_POST['accept']!='1' && $_POST['accept']!='0'))
  {
        if (empty($_POST['accept']))
        {
                setcookie('accept_error', "Укажите, ознакомлены ли вы с контрактом.", time() + 24 * 60 * 60);
        }
        else
        {
                setcookie('accept_error', "Думали, я не замечу, как вы пытаетесь меня взломать???", time() + 24 * 60 * 60);
        }
        $errors = TRUE;
  }
  foreach (array('fio', 'phone', 'email', 'year', 'gender', 'biography', 'accept') as $v)
  {
       setcookie($v.'_value', $_POST[$v], time() + 30 * 24 * 60 * 60);
  }
  setcookie('language_value', implode('|', ($_POST['language'])), time() + 30 * 24 * 60 * 60);
  if ($errors)
  {
    header('Location: index.php');
    exit();
  }
  foreach (array('fio', 'phone', 'email', 'year', 'gender', 'language', 'biography') as $v)
  {
          setcookie($v.'_error', '', 100000);
     }
  $user = 'uXXXXX';
  $pass = 'XXXXXXX';
  $db = new PDO('mysql:host=localhost;dbname=uXXXXX', $user, $pass,
          [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
  if (!empty($COOKIE[session_name()]) && session_start() && !empty($_SESSION['login']))
  {

  }
  else
  {
          $login = $_POST['fio'];
          $pass = uniqid(mt_rand(1010, 9898), true);
          $uid = password_hash($pass, PASSWORD_DEFAULT);
          setcookie('login', $login);
          setcookie('pass', $pass);
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
  }
  catch(PDOException $e)
  {
        print('Error : ' . $e->getMessage());
        exit();
  }
  }
  setcookie('save', '1');
  header('Location: index.php');
}
?>
