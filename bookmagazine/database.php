<?php
$db = new PDO('mysql:host=localhost;dbname=u68768', 'u68768', '5901684',
        [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
function printbook($row, $rew, $db, $t)
{
        if (is_array($row))
        {
                print("<img src='");
                print($row['image_url']);
                print("' /><br />");
                                foreach (array("id", "title", "author", "genre", "publication_year") as $d)
                                {
                                        if ($d == "id")
                                        {
                                                print("Номер в библиотеке :");
                                        }
                                        if ($d == "publication_year")
                                        {
                                                print("Год публикации :");
                                        }
                                        print($row[$d]);
                                        print("<br />");
                                }
                                if ($rew == $row['author'])
                                {
                                        print("Вы являетесь автором данной книги");
                                        print("<form action='' method='post'>");
                                        print("<input name='");
                                        print($row['id']);
                                        print("' value='Редактировать книгу' type='submit' class='klen'/>");
                                        print("</form>");
                                }
                                else
                                {
                                        $stmt=$db->prepare("SELECT * FROM bk_users where username = ?");
                                        $stmt->execute([$rew]);
                                        $top=$stmt->fetch();
                                        $stmt=$db->prepare("SELECT * from bk_exchanges where bkreqid = ? and bkrequester = ? and status = ?");
                                        $stmt->execute([$row['id'], $top['id'], 'active']);
                                        if ($stmt->rowCount()==0)
                                        {
                                                print("<form action='' method='post'>");
                                                print("<input name='");
                                                print($row['id']);
                                                print("' value='Взять почитать' type='submit' class='klen'/>");
                                                print("</form>");
                                          }
                                        else
                                        {
                                                print("<form action='");
                                                print($row['gar_url']);
                                                print("' method='post'>");
                                                print("<input value='Прочитать книгу' type='submit' class='klen' />");
                                                print("</form>");
                                                print("<form action='' method='post'>");
                                                print("<input name='");
                                                print($row['id']);
                                                print("' value='Отдать обратно' type='submit' class='klen' />");
                                                print("</form>");
                                        }
                                        if ($rew == 'mainadmin' && $t == 1)
                                        {
                                                print("<form action='' method='post'>");
                                                print("<input name='");
                                                print($row['id']);
                                                print("' value='Взаимодействие с книгой' type='submit' class='klen' />");
                                                print("</form>");
                                        }
                                }
        }
        else
        {
                print($row);
        }
}
function joker($row, $rew, $u, $db)
{
        print("<td class='z'>");
        printbook($row, $rew, $db, 1);
        print("</td>");
        if ($u == 4)
        {
               $u = 0;
               print("</tr><tr>");
        }
}
function printbookbase($db, $a, $g, $ti, $rew)
{
        $r = 1; $t = 1; $u = 0;
        print("<table border='1'><tr>");
        if (!is_numeric($a))
        {
           while ($r!= 0)
                {
                        $stmt=$db->prepare("SELECT * FROM bk_books where id=? and author = ?");
                        $stmt->execute([strval($t), $a]);
                        if ($stmt->rowCount()==0)
                        {
                                $hghg=$db->prepare("SELECT * FROM bk_books where id>=? and author = ?");
                                $hghg->execute([strval($t), $a]);
                                if ($hghg->rowCount()==0)
                                {
                                        $r = 0;
                                        print("</tr></table>");
                                        break;
                                }
                                $t = $t + 1;
                        }
                        else
                        {
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                $u = $u + 1;
                                joker($row, $rew, $u, $db);
                                $t = $t + 1;
                        }
                }
        }
        else
        {
                if (!is_numeric($g) && !is_numeric($ti))
                {
                        while ($r!= 0)
                        {
                                $stmt=$db->prepare("SELECT * FROM bk_books where id=? and genre = ? and title = ?");
                                $stmt->execute([strval($t), $g, $ti]);
                                if ($stmt->rowCount()==0)
                                {
                                        $hghg=$db->prepare("SELECT * FROM bk_books where id>=? and genre = ? and title = ?");
                                        $hghg->execute([strval($t), $g, $ti]);
                                        if ($hghg->rowCount()==0)
                                        {
                                                $r = 0;
                                                print("</tr></table>");
                                                break;
                                        }
                                        $t = $t + 1;
                                }
                          else
                                {
                                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                        $u = $u + 1;
                                        joker($row, $rew, $u, $db);
                                        $t = $t + 1;
                                }
                        }
                }
                else
                {
                        if (!is_numeric($g))
                        {
                                while ($r!= 0)
                                {
                                        $stmt=$db->prepare("SELECT * FROM bk_books where id=? and genre = ?");
                                        $stmt->execute([strval($t), $g]);
                                        if ($stmt->rowCount()==0)
                                        {
                                                $hghg=$db->prepare("SELECT * FROM bk_books where id>=? and genre = ?");
                                                $hghg->execute([strval($t), $g]);
                                                if ($hghg->rowCount()==0)
                                                {
                                                        $r = 0;
                                                        print("</tr></table>");
                                                        break;
                                                }
                                                $t = $t + 1;
                                        }
                                        else
                                        {
                                                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                                $u = $u + 1;
                                                joker($row, $rew, $u, $db);
                                                $t = $t + 1;
                                        }
                                }
                        }
                        else
                        {
                                if (!is_numeric($ti))
                                {
                                        while ($r!= 0)
                                        {
                                                $stmt=$db->prepare("SELECT * FROM bk_books where id=? and title = ?");
                                          $stmt->execute([strval($t), $ti]);
                                                if ($stmt->rowCount()==0)
                                                {
                                                        $hghg=$db->prepare("SELECT * FROM bk_books where id>=? and title = ?");
                                                        $hghg->execute([strval($t), $ti]);
                                                        if ($hghg->rowCount()==0)
                                                        {
                                                                $r = 0;
                                                                print("</tr></table>");
                                                                break;
                                                        }
                                                        $t = $t + 1;
                                                }
                                                else
                                                {
                                                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                                        $u = $u + 1;
                                                        joker($row, $rew, $u, $db);
                                                        $t = $t + 1;
                                                }
                                        }
                                }
                                else
                                {
                                        while ($r!= 0)
                                        {
                                                $stmt=$db->prepare("SELECT * FROM bk_books where id=?");
                                                $stmt->execute([strval($t)]);
                                                if ($stmt->rowCount()==0)
                                                {
                                                        $hghg=$db->prepare("SELECT * FROM bk_books where id>=?");
                                                        $hghg->execute([strval($t)]);
                                                        if ($hghg->rowCount()==0)
                                                        {
                                                                $r = 0;
                                                                print("</tr></table>");
                                                                break;
                                                        }
                                                        $t = $t + 1;
                                                }
                                                else
                                                {
                                                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                                        $u = $u + 1;
                                                        joker($row, $rew, $u, $db);
                                                        $t = $t + 1;
                                                 }
                                        }
                                }
                        }
                }
        }
        return $t;
}
?>
