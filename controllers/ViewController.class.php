<?php

require 'redbean/rb-mysql.php';

class ViewController
{
	public $twig;
	public function __construct($twig)
	{
		$this->twig = $twig;
	}

	public function list()
	{
		/* redbeans */
		R::setup('mysql:host=localhost;dbname=mytododata', 'bit_academy', 'bit_academy');
		$mytodos = R::getAll('SELECT mytodo.todo, mytodo.status FROM mytodo ORDER BY todo;');
		return $this->twig->render('ViewList.html', ['mytodos' => $mytodos]);
	}

	public function status()
	{
		/* redbeans */
		R::setup('mysql:host=localhost;dbname=mytododata', 'bit_academy', 'bit_academy');
		$mytodos = R::getAll('SELECT mytodo.todo, mytodo.status FROM mytodo ORDER BY status');
		return $this->twig->render('ViewStatus.html', ['mytodos' => $mytodos]);
	}
}
