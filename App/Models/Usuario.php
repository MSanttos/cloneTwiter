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

    public function getAll(){

      $query = "
        select 
          u.id,
          u.nome, 
          u.email,
          (
            select 
              count(*) 
            from 
              usuarios_seguidores as us 
            where 
              us.id_usuario = :id_usuario and us.id_usuario_seguindo = u.id
          ) as seguindo_sn
        from 
          usuarios as u
        where 
          u.nome like :nome and u.id != :id_usuario
        ";
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':nome', '%'.$this->__get('nome').'%');//% -> qualquer caractere a direita ou esquerda
      $stmt->bindValue(':id_usuario', $this->__get('id'));
      $stmt->execute();

      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function seguirUsuario($id_usuario_seguindo){
      $query = "insert into usuarios_seguidores(id_usuario, id_usuario_seguindo) values(:id_usuario, :id_usuario_seguindo)";
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':id_usuario', $this->__get('id'));
      $stmt->bindValue(':id_usuario_seguindo', $id_usuario_seguindo);
      $stmt->execute();

      return true;

    }

    public function deixarSeguirUsuario($id_usuario_seguindo){
      $query = "delete from usuarios_seguidores where id_usuario = :id_usuario and id_usuario_seguindo = :id_usuario_seguindo";
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':id_usuario', $this->__get('id'));
      $stmt->bindValue(':id_usuario_seguindo', $id_usuario_seguindo);
      $stmt->execute();

      return $this;

    }

    //recuperar info do usuario para exibir no perfil
    public function getInfoUsuario(){
      $query = "select nome from usuarios where id = :id_usuario";
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':id_usuario', $this->__get('id'));
      $stmt->execute();

      return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    //total de tweets para
    public function getTotalTweets(){
      $query = "select count(*) as total_tweets from tweets where id_usuario = :id_usuario";
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':id_usuario', $this->__get('id'));
      $stmt->execute();

      return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    //total que estamos seguindo
    public function getTotalSeguindo(){
      $query = "select count(*) as total_seguindo from usuarios_seguidores where id_usuario = :id_usuario";
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':id_usuario', $this->__get('id'));
      $stmt->execute();

      return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    //total de seguidores
    public function getTotalSeguidores(){
      $query = "select count(*) as total_seguidores from usuarios_seguidores where id_usuario_seguindo = :id_usuario";
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':id_usuario', $this->__get('id'));
      $stmt->execute();

      return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
  }

?>