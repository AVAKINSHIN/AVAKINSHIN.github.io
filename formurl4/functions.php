<?php
        $db = new PDO('mysql:host=localhost;dbname=uXXXXX', 'uXXXXX', 'XXXXXXX',
                [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        function selectLanguage($db, $r)
        {
                $stmt = $db->prepare("SELECT id_p FROM person_language where id_l=?");
                $stmt->execute([$r]);
                return $stmt->rowCount();
        }
        function tableheadbilder($u)
        {
                print("<table border='1'>");
                print("<tr>");
                if (is_array($u))
                {
                        foreach ($u as $d)
                        {
                                print("<th>");
                                print($d);
                                print("</th>");
                        }
                }
                else
                {
                        print("<th>");
                        print($d);
                        print("</th>");
                }
                print("</tr>");
        }
        function tablebodybilder($u)
        {
                print("<tr>");
                if (is_array($u))
                {
                        foreach ($u as $d)
                        {
                                print("<td>");
                                print($d);
                                print("</td>");
                        }
                }
                else
                {
                        print("<td>");
                        print($u);
                         }
                print("</tr>");
        }
        function coutE($u)
        {
                $o = array();
                if (is_array($u))
                {
                        foreach ($u as $v)
                        {
                                $o[$v] = !empty($_COOKIE[$v.'_error']);
                                if ($o[$v])
                                {
                                        $messages[] = sprintf('<div class="error"><strong>%s</strong></div>', strip_tags($_COOKIE[$v.'_error']));
                                        setcookie($v.'_error', '', 100000);
                                }
                        }
                }
                else
                {
                        $o[$u] = !empty($_COOKIE[$u.'_error']);
                        if ($o[$u])
                        {
                                $messages[] = sprintf('<div class="error"><strong>%s</strong></div>', strip_tags($_COOKIE[$u.'_error']));
                                setcookie($u.'_error', '', 100000);
                        }
                }
                return d;
        }
        function updateDB($f, $p, $e, $y, $g, $b, $a, $u, $l, $db)
        {
                $stmt = $db->prepare("UPDATE person SET fio=?, phone=?, email=?, year=?, gender=?, biography=?, accept=? WHERE id=?");
                $stmt->execute([$f, $p, $e, $y, $g, $b, $a, $u]);
                $stmt = $db->prepare("DELETE FROM person_language WHERE id_p=?");
                $stmt->execute([$u]);
                if (is_array($l))
                {
                        foreach ($l as $v)
                        {
                                $stmt = $db->prepare("INSERT INTO person_language SET id_l=?, id_p=?");
                                $stmt->execute([$v, $u]);
                        }
                }
                else
                {
                        $stmt = $db->prepare("INSERT INTO person_language SET id_l=?, id_p=?");
                        $stmt->execute([$l, $u]);
                }
        }
        function grabTegs($u, $db) : array
        {
                $values=array();
                $stmt=$db->prepare("SELECT fio, email, year, gender, phone, biography, accept FROM person WHERE id=?");
                $stmt->execute([$u]);
                if ($stmt->rowCount()!=0)
                {
                  $row=$stmt->fetch(PDO::FETCH_ASSOC);
                  foreach (array('fio', 'phone', 'email', 'year', 'gender', 'biography', 'accept') as $v)
                  {
                        $values[$v] = strip_tags($row[$v]);
                  }
                }
                return $values;
        }
?>
