<?php
header('Content-Type: text/html; charset=UTF-8');
if (isset($_COOKIE[session_name()]) && session_start() && !empty($_SESSION['login']))
{
        $min = intval(date('i')) - $_SESSION['min'];
        if ($_SESSION['date'] != date('Y-m-d') || $_SESSION['hr'] != date('H') || $min > 30 || isset($_POST['exit']))
        {
                session_destroy();
                header('Location: index.php');
                exit();
        }
        if (isset($_COOKIE['page']))
        {
                if ($_COOKIE['page'] == "bookpage")
                {
                        include("bookpage.php");
                        exit();
                }
                if ($_COOKIE['page'] == "add")
                {
                        include("formaddbook.php");
                        exit();
                }
                if ($_COOKIE['page'] == 'smenarol')
                {
                        include("smenarol.php");
                        exit();
                }
                include("mybooks.php");
                exit();
        }
        include("mainpage.php");
}
else
{
        include("login.php");
}
?>
