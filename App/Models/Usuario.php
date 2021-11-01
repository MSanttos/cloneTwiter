<?php

  namespace App\Models;

  use MF\Model\Model;

  class Usuario extends Model{

    private $id;
    private $nome;
    private $email;
    private $senha;

    //get recebe atributo e retorna o mesmo atributo
    public function __get($atributo){
      return $this->$atributo;
    }
    //set recebe o atributo e seu valor e atribui o valor ao atributo
    public function __set($atributo, $valor){
      $this->$atributo = $valor;
    }

    //salvar com PDO e seus parametros para fazer o bind
    public function salvar(){
      $query = "insert into usuarios(nome, email, senha) values(:nome, :email, :senha)";
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':nome', $this->__get('nome'));//substitui o parametro nome pelo valor do atributo nome do objeto usuário
      $stmt->bindValue(':email', $this->__get('email'));
      $stmt->bindValue(':senha', $this->__get('senha'));//md5() converte a senha para md5 -> hash com 32 caracteres
      $stmt->execute();

      return $this;//retorna o proprio objeto
    }

    /* MÉTODO validar se cadastro pode ser feito */
    public function validarCadastro(){
      $valido = true;

      if(strlen($this->__get('nome')) < 3){
        $valido = false;
      }

      if(strlen($this->__get('email')) < 3){
        $valido = false;
      }

      if(strlen($this->__get('senha')) < 3){
        $valido = false;
      }

      return $valido;
    }

    /* MÉTODO validar se login pode ser feito */
    public function getUsuarioPorEmail(){
      $query = "select nome, email from usuarios where email = :email";
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':email', $this->__get('email'));
      $stmt->execute();

      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /* MÉTODO autenticar usuário */
    public function autenticar(){
      $query = "select id, nome, email from usuarios where email = :email and senha = :senha";
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':email', $this->__get('email'));
      $stmt->bindValue(':senha', $this->__get('senha'));
      $stmt->execute();

      $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

      if(!empty($usuario['id']) && !empty($usuario['nome'])){
        $this->__set('id', $usuario['id']);
        $this->__set('nome', $usuario['nome']);
      }

      return $this; 
    }
  }

?>