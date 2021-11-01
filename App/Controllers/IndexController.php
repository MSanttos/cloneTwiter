<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class IndexController extends Action {

	public function index() {

		$this->render('index');
	}

	public function inscreverse() {

		$this->view->erroCadastro = false;

		$this->render('inscreverse');
	}

	public function registrar() {
		
		$usuario = Container::getModel('Usuario');//receber os dados do formulario

		$usuario->__set('nome', $_POST['nome']);
		$usuario->__set('email', $_POST['email']);
		$usuario->__set('senha', md5($_POST['senha']));

		if($usuario->validarcadastro() && count($usuario->getUsuarioPorEmail()) == 0){

			$usuario->salvar();
			$this->render('cadastro');

		}else{

			$this->view->erroCadastro = true;

			$this->render('inscreverse');
		}
	}
}


?>