<?php

namespace RestCrudDoctrineModule\Mapper;

use Doctrine\ORM\EntityManager;

abstract class AbstractMapper
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    protected $entity;

    public function __construct(EntityManager $em, $entity)
    {
        $this->em      = $em;
        $this->entity  = $entity;
    }

    public function findAll() 
    {
        $er = $this->em->getRepository($this->entity);
        return $er->findAll();
    }

    public function findById($id)
    {
        $er = $this->em->getRepository($this->entity);
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
}