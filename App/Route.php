<?php

namespace App;

use MF\Init\Bootstrap;

class Route extends Bootstrap {

	protected function initRoutes() {

		$routes['home'] = array(
			'route' => '/',
			'controller' => 'indexController',
			'action' => 'index'
		);


		//inscrever-se
		$routes['inscreverse'] = array(
			'route' => '/inscreverse',
			'controller' => 'indexController',
			'action' => 'inscreverse'
		);


		//registrar conta
		$routes['registrar'] = array(
			'route' => '/registrar',
			'controller' => 'indexController',
			'action' => 'registrar'
		);


		//autenticação
		$routes['autenticar'] = array(
			'route' => '/autenticar',
			'controller' => 'AuthController',
			'action' => 'autenticar'
		);


		//ver timeline
		$routes['timeline'] = array(
			'route' => '/timeline',
			'controller' => 'AppController',
			'action' => 'timeline'
		);


		//sair
		$routes['sair'] = array(
			'route' => '/sair',
			'controller' => 'AuthController',
			'action' => 'sair'
		);


		//postar tweet
		$routes['tweet'] = array(
			'route' => '/tweet',
			'controller' => 'AppController',
			'action' => 'tweet'
		);


		//quem seguir
		$routes['quem_seguir'] = array(
			'route' => '/quem_seguir',
			'controller' => 'AppController',
			'action' => 'quemSeguir'
		);

		//ação seguir e deixar de seguir
		$routes['acao'] = array(
			'route' => '/acao',
			'controller' => 'AppController',
			'action' => 'acao'
		);

		//remover tweet
		$routes['excluir_tweet'] = array(
			'route' => '/excluir_tweet',
			'controller' => 'AppController',
			'action' => 'excluirTweet'
		);

		$this->setRoutes($routes);
	}

}

?>