<?php declare(strict_types=1); 

namespace App\Entitys;

class BarbeiroEntity
{
     private int $id_barbeiro;
    
    public function __construct(
        private string $nome,
        private string $email,
        private string $password,
        private int $telefone,
        private string $especialidade,
        private string $status 
    ){}

     /**
      * Get the value of id_barbeiro
      */ 
     public function getId_barbeiro()
     {
          return $this->id_barbeiro;
     }

     /**
      * Set the value of id_barbeiro
      *
      * @return  self
      */ 
     public function setId_barbeiro(int $id_barbeiro)
     {
          $this->id_barbeiro = $id_barbeiro;

          return $this;
     }

        /**
         * Get the value of nome
         */ 
        public function getNome()
        {
                return $this->nome;
        }

        /**
         * Set the value of nome
         *
         * @return  self
         */ 
        public function setNome($nome)
        {
                $this->nome = $nome;

                return $this;
        }

        /**
         * Get the value of email
         */ 
        public function getEmail()
        {
                return $this->email;
        }

        /**
         * Set the value of email
         *
         * @return  self
         */ 
        public function setEmail($email)
        {
                $this->email = $email;

                return $this;
        }

        /**
         * Get the value of password
         */ 
        public function getPassword()
        {
                return $this->password;
        }

        /**
         * Set the value of password
         *
         * @return  self
         */ 
        public function setPassword($password)
        {
                $this->password = $password;

                return $this;
        }

        /**
         * Get the value of telefone
         */ 
        public function getTelefone()
        {
                return $this->telefone;
        }

        /**
         * Set the value of telefone
         *
         * @return  self
         */ 
        public function setTelefone($telefone)
        {
                $this->telefone = $telefone;

                return $this;
        }

        /**
         * Get the value of especialidade
         */ 
        public function getEspecialidade()
        {
                return $this->especialidade;
        }

        /**
         * Set the value of especialidade
         *
         * @return  self
         */ 
        public function setEspecialidade($especialidade)
        {
                $this->especialidade = $especialidade;

                return $this;
        }

        /**
         * Get the value of status
         */ 
        public function getStatus()
        {
                return $this->status;
        }

        /**
         * Set the value of status
         *
         * @return  self
         */ 
        public function setStatus($status)
        {
                $this->status = $status;

                return $this;
        }
}