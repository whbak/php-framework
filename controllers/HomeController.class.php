<?php

session_set_cookie_params(['samesite' => 'Lax']);
session_start();
require 'redbean/rb-mysql.php';

class HomeController
{
    public $twig;
    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    public function index()
    {
        if (isset($_SESSION['loggedInUser'])) {
            $username = 'Ingelogd: ' . $_SESSION['loggedInUser'];
        } else {
            $username = 'Welkom op de home pagina.';
        }
        return $this->twig->render('HomeIndex.html', ['username' => $username]);
    }

    public function inlog()
    {
        if ($_SERVER['REQUEST_METHOD'] <> 'POST') {
            if (isset($_SESSION['loggedInUser'])) {
                $username = 'Ingelogd: ' . $_SESSION['loggedInUser'];
            } else {
                $username = 'Iedereen';
            }
            return $this->twig->render('HomeInlog.html', ['username' => $username]);
        } else {
            $this->inlogcheck();
        }
    }

    public function inlogcheck()
    {
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = $_POST['username'];
            /* redbeans */
            R::setup('mysql:host=localhost;dbname=mytododata', 'bit_academy', 'bit_academy');
            $mysqlhash = R::getRow(
                'SELECT password FROM users WHERE username = :username',
				[':username' => $username]
            );
            $username = $_POST['username'];
            $password = $_POST['password'];
            $wwcontrole = password_verify($password, $mysqlhash['password']);
            $users = R::getAll('SELECT users.username, users.password FROM users');
            $count = count($users);
            for ($cnt = 0; $cnt < $count; $cnt++) {
                if (in_array($username, $users[$cnt]) && in_array($wwcontrole, $users[$cnt])) {
                    $n = 55;
                    $token = bin2hex(random_bytes($n));
                    $_SESSION['loggedInUser'] = $username;
                    $_SESSION[$username] = $token;
                    $sessions = R::dispense('sessions');
                    $sessions->username = $username;
                    $sessions->token = $token;
                    $id = R::store($sessions);
                    header('Location: /Todo/report');
                }
            }
        }
    }

    public function uitlog()
    {
        $username = $_SESSION['loggedInUser'];
        $sessiontoken = $_SESSION[$username];
        R::setup('mysql:host=localhost;dbname=mytododata', 'bit_academy', 'bit_academy');
        R::exec(
            "DELETE FROM sessions WHERE username = :username AND token = :token",
            [':username' => $username, ':token' => $sessiontoken]
        );
            session_unset();
            session_destroy();
            $username = 'Iedereen';
        return $this->twig->render('HomeUitlog.html', ['username' => $username]);
    }
}
