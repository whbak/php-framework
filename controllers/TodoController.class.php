<?php

session_set_cookie_params(['samesite' => 'Lax']);
session_start();
require_once('services/UserService.class.php');
require 'redbean/rb-mysql.php';

class TodoController
{
	public $twig;
	public function __construct($twig)
	{
		$this->twig = $twig;
	}

	public function add()
	{
		/* redbeans */
		R::setup('mysql:host=localhost;dbname=mytododata', 'bit_academy', 'bit_academy');
		$userservice = new UserService($this->twig);
		$validuser = $userservice->validateLoggedIn();
		if ($validuser == true) {
			$username = $_SESSION['loggedInUser'];
			if ($_SERVER['REQUEST_METHOD'] <> 'POST') {
				return $this->twig->render('TodoAdd.html', ['username' => $username]);
			} else {
				$this->addpost();
			}	
		} else {
			header('Location: /Home');
		}
	}

	public function addpost()
	{
		/* redbeans */
		if ($_SERVER['REQUEST_METHOD'] <> 'POST') {
			header('Location: /Todo/add');
		} else {
			$userservice = new UserService($this->twig);
			$validuser = $userservice->validateLoggedIn();
			if ($validuser == true) {
				/* redbeans */
				$mytodos = R::dispense("mytodo");
				$mytodos->sequence = $_POST['sequence'];
				$mytodos->username = $_POST['username'];
				$mytodos->todo = $_POST['todo'];
				$mytodos->begin = $_POST['begin'];
				$mytodos->finish = $_POST['finish'];
				$mytodos->status = $_POST['status'];
				$id = R::store($mytodos);
				header('Location: /Todo/report');
			} else {
				header('Location: /View');
			}
		}
	}

	public function report()
	{
		if ((isset($_POST['id'])) && (!isset($_POST['status']))) {
			R::setup('mysql:host=localhost;dbname=mytododata', 'bit_academy', 'bit_academy');
			$userservice = new UserService($this->twig);
			$validuser = $userservice->validateLoggedIn();
			if ($validuser == true) {
				/* redbeans */
				$username = $_SESSION['loggedInUser'];
				$id = $_POST['id'];
				$mytodos = R::getAll(
					"SELECT * FROM mytodo WHERE username = :username AND id = :id",
					[':username' => $username, ':id' => $id]
				);
				return $this->twig->render('TodoEdit.html', ['username' => $username, 'mytodos' => $mytodos]);
			} else {
				header('Location: /View');
			}
		}
		if ((isset($_POST['id'])) && (isset($_POST['status']))) {
			R::setup('mysql:host=localhost;dbname=mytododata', 'bit_academy', 'bit_academy');
			$userservice = new UserService($this->twig);
			$validuser = $userservice->validateLoggedIn();
			if ($validuser == true) {
				/* redbeans */
				$username = $_SESSION['loggedInUser'];
				$id = $_POST['id'];
				$sequence = $_POST['sequence'];
				$todo = $_POST['todo'];
				$begin = $_POST['begin'];
				$finish = $_POST['finish'];
				$status = $_POST['status'];
				$mytodos = R::exec(
					"UPDATE mytodo SET sequence = :sequence, todo = :todo, begin = :begin, finish = :finish, status = :status WHERE id = :id AND username = :username",
					[':sequence' => $sequence, ':todo' => $todo, ':begin' => $begin, ':finish' => $finish, ':status' => $status, ':id' => $id, ':username' => $username]
				);
				header('Location: /Todo/report');
			} else {
				header('Location: /View');
			}
		} else {
			R::setup('mysql:host=localhost;dbname=mytododata', 'bit_academy', 'bit_academy');
			$userservice = new UserService($this->twig);
			$validuser = $userservice->validateLoggedIn();
			if ($validuser == true) {
				/* redbeans */
				$username = $_SESSION['loggedInUser'];
				$mytodos = R::getAll(
					'SELECT * FROM mytodo WHERE username = :username ORDER BY sequence',
					[':username' => $username],
				);
				return $this->twig->render('TodoReport.html', ['username' => $username, 'mytodos' => $mytodos]);
			} else {
				header('Location: /View');
			}
		}
	}

	public function sequence()
	{
		R::setup('mysql:host=localhost;dbname=mytododata', 'bit_academy', 'bit_academy');
		$userservice = new UserService($this->twig);
		$validuser = $userservice->validateLoggedIn();
		if ($validuser == true) {
			if ($_SERVER['REQUEST_METHOD'] <> 'POST') {
				/* redbeans */
				$username = $_SESSION['loggedInUser'];
				$mytodos = R::getAll(
					'SELECT mytodo.id, mytodo.sequence, mytodo.todo FROM mytodo WHERE username = :username ORDER BY sequence',
					[':username' => $username],
				);
				return $this->twig->render('TodoSequence.html', ['username' => $username, 'mytodos' => $mytodos]);
			} else {
				$username = $_SESSION['loggedInUser'];
				$mytodos = R::getAll(
					'SELECT mytodo.id, mytodo.sequence, mytodo.todo FROM mytodo WHERE username = :username ORDER BY sequence',
					[':username' => $username],
				);
				$count = count($mytodos);
				for ($cnt = 0; $cnt < $count; $cnt++) {
					$id = $mytodos[$cnt]['id'];
					$sequenceedit = $_POST[$mytodos[$cnt]['id']];
					$mytodosupdate = R::exec(
						"UPDATE mytodo SET sequence = :sequence WHERE id = :id AND username = :username",
						[':sequence' => $sequenceedit, ':id' => $id, ':username' => $username]
					);
				}
				$mytodos = R::getAll(
					'SELECT mytodo.id, mytodo.sequence, mytodo.todo FROM mytodo WHERE username = :username ORDER BY sequence',
					[':username' => $username],
				);
				return $this->twig->render('TodoSequence.html', ['username' => $username, 'mytodos' => $mytodos]);
			}
		} else {
			header('Location: /View');
		}
	}

	public function status()
	{
		R::setup('mysql:host=localhost;dbname=mytododata', 'bit_academy', 'bit_academy');
		$userservice = new UserService($this->twig);
		$validuser = $userservice->validateLoggedIn();
		if ($validuser == true) {
			if ((isset($_POST['id'])) && (!isset($_POST['status']))) {
				/* redbeans */
				$username = $_SESSION['loggedInUser'];
				$id = $_POST['id'];
				$mytodos = R::getAll(
					"SELECT * FROM mytodo WHERE username = :username AND id = :id",
					[':username' => $username, ':id' => $id]
				);
				return $this->twig->render('TodoStatusEdit.html', ['username' => $username, 'mytodos' => $mytodos]);
			}
			if ((isset($_POST['id'])) && (isset($_POST['status']))) {
				/* redbeans */
				$username = $_SESSION['loggedInUser'];
				$id = $_POST['id'];
				$status = $_POST['status'];
				$mytodos = R::exec(
					"UPDATE mytodo SET status = :status WHERE id = :id AND username = :username",
					[':status' => $status, ':id' => $id, ':username' => $username]
				);
				header('Location: /Todo/status');
			} else {
				/* redbeans */
				$username = $_SESSION['loggedInUser'];
				$mytodos = R::getAll(
					'SELECT * FROM mytodo WHERE username = :username ORDER BY sequence',
					[':username' => $username],
				);
				return $this->twig->render('TodoStatus.html', ['username' => $username, 'mytodos' => $mytodos]);
			}
		} else {
			header('Location: /View');
		}
	}
}
