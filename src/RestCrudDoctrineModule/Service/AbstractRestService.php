<?php

namespace RestCrudDoctrineModule\Service;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend ServiceManager\ServiceManager;

use Doctrine\ORM\QueryBuilder;

abstract class AbstractRestService implements ServiceManagerAwareInterface
{
	protected $sm;

	protected $count;

	public function getAll($start = 0, $count = 100, $orderBy = array()) {
		$qb = $this->getQueryBuilder($start, $count);
		if(count($orderby)) {
			$qb->addOrderBy('entity._' . $orderBy['sort'], $orderBy['order']);
		}

		$query = $qb->getQuery();
		$results = $query->getResults();
		$this->setCount(count($results));
		return $results;
	}

	public function getById($id)
	{
		$em = $this->sm->get('doctrine.entitymanager.orm_default');

        $entity = $em->find($this->entityName, $id);
        if (!$model) {
            throw new Exception\NotFound(
                $this->entityName . ' ' . $id . ' not found'
            );
        }

        return $entity;
	}

	public function save($id, $data) 
	{
		//todo map id/data to the model
	}

	public function delete($id) 
	{
		$entity = $this->getById($id);
		$em = $this->sm->get('doctrine.entitymanager.orm_default');
        $em->remove($entity);
        $em->flush();
	}

	public function setCount($count) {
		$this->count = $count;
	}

	protected function getQueryBuilder($start = 0, $count = 100) 
	{
		$em = $this->sm->get('doctrine.entitymanager.orm_default');
		$qb = $em->createQueryBuilder();
		$qb->select('entity')->from($this->getEntity(), 'entity');

		$qb->setFirstResult($start);
		$qb->setMaxResults($count);

		return $qb;
	}

	public function setEntity($entity)
	{
		$this->entity;
	}

	public function setServiceManager(ServiceManager $sm) {
		$this->sm = $sm;
	}
}