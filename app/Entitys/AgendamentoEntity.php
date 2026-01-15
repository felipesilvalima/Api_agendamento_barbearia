<?php declare(strict_types=1); 

namespace App\Entitys;

class AgendamentoEntity
{
    public function __construct(
        private int $id_barbeiro,
        private int $id_cliente,
        private string $data,
        private string $hora,
        private array $servicos,
        private string $status
    ){}

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

        /**
         * Get the value of servicos
         */ 
        public function getServicos()
        {
                return $this->servicos;
        }

        /**
         * Set the value of servicos
         *
         * @return  self
         */ 
        public function setServicos($servicos)
        {
                $this->servicos = $servicos;

                return $this;
        }

        /**
         * Get the value of hora
         */ 
        public function getHora()
        {
                return $this->hora;
        }

        /**
         * Set the value of hora
         *
         * @return  self
         */ 
        public function setHora($hora)
        {
                $this->hora = $hora;

                return $this;
        }

        /**
         * Get the value of data
         */ 
        public function getData()
        {
                return $this->data;
        }

        /**
         * Set the value of data
         *
         * @return  self
         */ 
        public function setData($data)
        {
                $this->data = $data;

                return $this;
        }

        /**
         * Get the value of id_cliente
         */ 
        public function getId_cliente()
        {
                return $this->id_cliente;
        }

        /**
         * Set the value of id_cliente
         *
         * @return  self
         */ 
        public function setId_cliente($id_cliente)
        {
                $this->id_cliente = $id_cliente;

                return $this;
        }

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
        public function setId_barbeiro($id_barbeiro)
        {
                $this->id_barbeiro = $id_barbeiro;

                return $this;
        }
}