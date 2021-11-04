<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action {

  public function timeline() {

    $this->validaAutenticacao();

    //echo 'Cheguei aqui';
    //print_r($_SESSION);

    //recuperar tweets
    $tweet = Container::getModel('Tweet');
    $tweet->__set('id_usuario', $_SESSION['id']);
    //$tweets = $tweet->getAll();
    //Debug
    // echo '<pre>';
    // print_r($tweets);
    // echo '</pre>';

    //variáveis de paginação
    $total_registros_pagina = 3;
    $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $deslocamento = ($pagina - 1) * $total_registros_pagina;

    //Debug
    //echo "<br/><br/>Página: $pagina | Total de registros: $total_registros_pagina | deslocamento: $deslocamento";

                            
    $tweets = $tweet->getPorPagina($total_registros_pagina, $deslocamento);//limite = $total_registros_pagina | deslocamento = $deslocamento
    $total_tweets = $tweet->getTotalRegistros();

    $this->view->total_de_paginas = ceil($total_tweets['total'] / $total_registros_pagina);
    
    //mostra pagina que o usuário está
    $this->view->pagina_ativa = $pagina;

    $this->view->tweets = $tweets;

    $usuario = Container::getModel('Usuario');
    $usuario->__set('id', $_SESSION['id']);

    $this->view->info_usuario = $usuario->getInfoUsuario();
    $this->view->total_tweets = $usuario->getTotalTweets();
    $this->view->total_seguindo = $usuario->getTotalSeguindo();
    $this->view->total_seguidores = $usuario->getTotalSeguidores();


    $this->render('timeline');

  }

  public function tweet() {
                                    
    $this->validaAutenticacao();
    
    $tweet = Container::getModel('Tweet');
    $tweet->__set('tweet', $_POST['tweet']);
    $tweet->__set('id_usuario', $_SESSION['id']);
    $tweet->salvar();

    header('Location: /timeline');

  }

  public function validaAutenticacao(){

    session_start();

    if(!isset($_SESSION['id']) || $_SESSION['id'] == '' || !isset($_SESSION['nome']) || $_SESSION['nome'] == '') {
      header('Location: /?login=erro');
    }
  }

  public function quemSeguir() {
      
    $this->validaAutenticacao();

    $pesquisarPor = isset($_GET['pesquisarPor']) ? $_GET['pesquisarPor'] : '';

    $usuarios = array();
    
    // print_r($_SESSION);

    if($pesquisarPor != '') {

      $usuario = Container::getModel('Usuario');//instância do objeto usuário
      $usuario->__set('nome', $pesquisarPor);
      $usuario->__set('id', $_SESSION['id']);
      $usuarios = $usuario->getAll();

    }

    $this->view->usuarios = $usuarios;

    $this->render('quemSeguir');
                                      
  }

  public function acao() {

    $this->validaAutenticacao();

    $acao = isset($_GET['acao']) ? $_GET['acao'] : '';
    $id_usuario_seguindo = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';

    $usuario = Container::getModel('Usuario');
    $usuario->__set('id', $_SESSION['id']);
    $usuario->__set('id_usuario_seguindo', $id_usuario_seguindo);
    
    if($acao == 'seguir') {
      $usuario->seguirUsuario($id_usuario_seguindo);
    } else {
      $usuario->deixarSeguirUsuario($id_usuario_seguindo);
    }

    header('Location: /quem_seguir');

  }

  public function excluirTweet(){
    $this->validaAutenticacao();
 
    $tweet = Container::getModel('Tweet');
 
    if(isset($_POST['tweet_id'])) {
      $tweet->__set('id_usuario', $_SESSION['id']);
      $tweet->__set('id', $_POST['tweet_id']);
      $tweet->excluir();

      header('Location: /timeline');
    }
  }
}


?>