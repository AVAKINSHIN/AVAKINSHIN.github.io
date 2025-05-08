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
                        print($d);
                  print("</td>");
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
?>
