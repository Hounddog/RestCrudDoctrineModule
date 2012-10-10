<?php

namespace RestCrudDoctrineModule\Mapper;

use Doctrine\ORM\EntityManager;
use Zend\Stdlib\Hydrator\HydratorInterface;

abstract class AbstractDBMapper
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    protected $entityClassName;

    public function __construct(EntityManager $em, $entityClassName)
    {
        $this->em      = $em;
        $this->entityClassName  = $entityClassName;
    }

    public function findAll(HydratorInterface $hydrator = null) 
    {
        $er = $this->em->getRepository($this->entityClassName);
        $results = $er->findAll();
        if(null !== $hydrator) {
            $data = array();
            foreach($results as $entity) {
                $data[] = $hydrator->extract($entity);
            }
            $results = $data;
        }
        return $results;
    }

    public function findById($id)
    {
        $er = $this->em->getRepository($this->entityClassName);
        return $er->find($id);
    }

    public function insert($entity)
    {
        return $this->persist($entity);
    }

    public function update($entity)
    {
        return $this->persist($entity);
    }

    public function remove($entity)
    {
        $this->em->remove($entity);
        $this->em->flush();
    }

    protected function persist($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }

    public function getEntityClassName() 
    {
        return $this->entityClassName;
    }
}