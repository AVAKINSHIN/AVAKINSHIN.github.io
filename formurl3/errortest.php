<?php
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
?>
