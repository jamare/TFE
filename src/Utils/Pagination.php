<?php

namespace App\Utils;

use Doctrine\Common\Persistence\ObjectManager;

class Pagination{

    private $entityClass;
    private $limit = 10;
    private $currentPage = 1;
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        // Grâce au manager, on récupère notre repository
        $this->manager = $manager;
    }

    public function getPages(){
        // Message d'erreur simplifié pour développeurs.
        if(empty($this->entityClass)){
            throw new \Exception("Vous n'avez pas spécifié l'entité sur laquelle nous devons paginer !!
            Utilisez la méthode setEntityClass() de votre objet Pagination");
        }
        // connaître le total des enregistrements de la table
        $repo=$this->manager->getRepository($this->entityClass);
        $total = count($repo->findAll());
       // ceil : arrondit à la page supérieure en cas de 3.4 pages ou 3.7 ...( 4 )
        $pages = ceil($total / $this->limit);
        return $pages;
    }

    public function getData(){
        // Message d'erreur simplifié pour développeurs.
        if(empty($this->entityClass)){
            throw new \Exception("Vous n'avez pas spécifié l'entité sur laquelle nous devons paginer !!
            Utilisez la méthode setEntityClass() de votre objet Pagination");
        }

        // pour déterminer si on démarre à 0 ou à 10 ou 20
        $offset = $this->currentPage * $this->limit - $this->limit;

        $repo = $this->manager->getRepository($this->entityClass);

        // 1: Array de critère de recherche - 2 : Array de critère d'ordre
        // 3: limite 4: A partir de ou nous commençons la pagination
        $data = $repo->findBy([],[],$this->limit,$offset);

        return $data;
    }

    public function setPage($page){
        $this->currentPage = $page;
        return $this;
    }

    public function getPage(){
        return $this->currentPage;
    }

    public function setLimit($limit){
        $this->limit = $limit;
        return $this;
    }

    public function getLimit(){
        return $this->limit;
    }

    public function setEntityClass($entityClass){
        $this->entityClass = $entityClass;

        return $this;
    }
    public function getEntityClass(){
        return $this->entityClass;
    }
}