<?php

namespace RestCrudDoctrineModule\Mapper;

use Doctrine\ORM\EntityManager;

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

    public function findAll() 
    {
        $er = $this->em->getRepository($this->entityClassName);
        return $er->findAll();
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