<?php

namespace App\Utils;

use Doctrine\Common\Persistence\ObjectManager;

class StatsService{
    private $manager;

    public function __construct(ObjectManager $manager){
        $this->manager = $manager;
    }

    public function getStats(){
        $users = $this->getUsersCount();
        $demands = $this->getDemandsCount();
        $executions = $this->getExecutionsCOunt();
        $comments = $this->getCommentsCount();

        //compact -> fonction php qui va retourner un tableau
        return compact('users', 'demands', 'executions', 'comments');
    }

    public function getUsersCount(){
        return $this->manager->createQuery('SELECT COUNT(u) FROM App\Entity\User u')->getSingleScalarResult();
    }

    public function getDemandsCount(){
        return $this->manager->createQuery('SELECT COUNT(d) FROM App\Entity\Demand d')->getSingleScalarResult();
    }

    public function getExecutionsCount(){
        return $this->manager->createQuery('SELECT COUNT(e) FROM App\Entity\Execution e')->getSingleScalarResult();
    }

    public function getCOmmentsCOunt(){
        return $this->manager->createQuery('SELECT COUNT(c) FROM App\Entity\Comment c')->getSingleScalarResult();
    }

    public function getProvidersStats($order){
        return $this->manager->createQuery(
            'SELECT AVG(c.rating) as note, p.name, p.id
             FROM App\Entity\Comment c
             JOIN c.provider p
             GROUP BY p
             ORDER BY note ' . $order
        )
            ->setMaxResults(5)
            ->getResult();
    }


}