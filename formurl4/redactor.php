<?php
$stmt = $db->prepare("UPDATE person SET fio=?, phone=?, email=?, year=?, gender=?, biography=?, accept=? WHERE id=?");
                $stmt->execute([$_POST['fio'], $_POST['phone'], $_POST['email'], $_POST['year'], $_POST['gender'],
                               $_POST['biography'], $_POST['accept'], $_SESSION['uid']]);
                $stmt = $db->prepare("DELETE FROM person_language WHERE id_p=?");
            $stmt->execute([$_SESSION['uid']]);                                                                                                                         foreach ($_POST['language'] as $v)
            {
              $stmt = $db->prepare("INSERT INTO person_language SET id_l=?, id_p=?");
              $stmt->execute([$v, $_SESSION['uid']]);
            }
?>
