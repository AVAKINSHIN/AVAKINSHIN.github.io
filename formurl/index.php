<?php
header('Content-Type: text/html; charset=UTF-8');
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  if (!empty($_GET['save'])) {
         print('Ваша учётная запись зарегистрирована.');
  }
  include('form.php');
  exit();
}
$k=0;
$errors = FALSE;
if (empty($_POST['fio'])) {
  print("<table border='1'><tr><th>Номер ошибки</th><th>Суть проблемы</th></tr>");
  $k=$k+1;
  print("<tr><td>");
  print($k);
  print("</td><td>Заполните имя.</td></tr>");
  $errors = TRUE;
}
if (empty($_POST['year']) || !is_numeric($_POST['year']) || !preg_match('/^\d+$/', $_POST['year'])) {
  if ($k == 0) {
    print("<table border='1'><tr><th>Номер ошибки</th><th>Суть проблемы</th></tr>");
  }
  $k=$k+1;
  print("<tr><td>");
  print($k);
  print("</td><td>");
  if (empty($_POST['year'])) {
    print("Заполните год.");
  }
  else {
    print("В поле год рождения должно быть указано целое число.");
  }
  print("</td></tr>");
  $errors = TRUE;
}
if (empty($_POST['phone']) || !preg_match('/^\+?[1-9][0-9]{7,14}$/', $_POST['phone'])) {
  if ($k == 0) {
    print("<table border='1'><tr><th>Номер ошибки</th><th>Суть проблемы</th></tr>");
  }
  $k=$k+1;
  print("<tr><td>");
  print($k);
  print("</td><td>");
  if (empty($_POST['phone'])) {
    print("Заполните номер телефона.<br/>");
  }
  else {
    print("Поле номер телефона должно содержать номер формата +X, где X - произвольная последовательность от 7 до 14 цифр.");
  }
  print("</td></tr>");
  $errors = TRUE;
}
if (empty($_POST['biography'])) {
  if ($k == 0) {
    print("<table border='1'><tr><th>Номер ошибки</th><th>Суть проблемы</th></tr>");
  }
  $k=$k+1;
  print("<tr><td>");
  print($k);
  print("</td><td>Поле биография обязательно для заполнения. Напишите хотя бы что-нибудь о себе.</td></tr>");
  $errors = TRUE;
}
if (empty($_POST['gender'])) {
  if ($k == 0) {
    print("<table border='1'><tr><th>Номер ошибки</th><th>Суть проблемы</th></tr>");
  }
  $k=$k+1;
  print("<tr><td>");
  print($k);
  print("</td><td>Выберите пол.</td></tr>");
  $errors = TRUE;
}
if (empty($_POST['abilities'])) {
  if ($k == 0) {
    print("<table border='1'><tr><th>Номер ошибки</th><th>Суть проблемы</th></tr>");
  }
  $k=$k+1;
  print("<tr><td>");
  print($k);
  print("</td><td>Выберите хотя бы один предложенный язык из списка.</td></tr>");
  $errors = TRUE;
}
if (empty($_POST['accept'])) {
  if ($k == 0) {
    print("<table border='1'><tr><th>Номер ошибки</th><th>Суть проблемы</th></tr>");
  }
  $k=$k+1;
  print("<tr><td>");
  print($k);
  print("</td><td>Укажите, ознакомлены ли вы с контрактом.</td></tr>");
  $errors = TRUE;
}
if ($errors) {
  print("<tr><td colspan='2'><a href='http://u68768.kubsu-dev.ru/formurl'>Попробуйте ещё раз заполнить поле</a></td></tr></table>");
  exit();
}
$user = 'uXXXXX';
$pass = 'XXXXXXX';
$db = new PDO('mysql:host=localhost;dbname=u68768', $user, $pass,
  [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
try {
  $stmt = $db->prepare("INSERT INTO person SET fio = ?, email = ?, year = ?, gender = ?, phone =  ?, biography = ?, accept = ?");
  $stmt->execute([$_POST['fio'], $_POST['email'], $_POST['year'], $_POST['gender'], $_POST['phone'], $_POST['biography'], $_POST['accept']]);
  $last_id = $db->lastInsertId();
  foreach ($_POST['abilities'] as $ability) {
          $stmt = $db->prepare("INSERT INTO person_language SET id_l = ?, id_p = ?");
          $stmt->execute([$ability, $last_id]);
  }
}
catch(PDOException $e){
  print('Error : ' . $e->getMessage());
  exit();
  }
header('Location: ?save=2');
