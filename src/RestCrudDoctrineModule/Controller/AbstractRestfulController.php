<?php

namespace RestCrudDoctrineModule\Controller;

use Zend\Mvc\Controller\AbstractRestfulController as ZendAbstractRestfulController;
use Zend\View\Model\JsonModel;
use RestCrudDoctrineModule\Mapper\AbstractDBMapper as DBMapper;
use RestCrudDoctrineModule\Service\AbstractRestService as Service;

abstract class AbstractRestfulController extends ZendAbstractRestfulController
{
	protected $service;

    protected $mapper;

    public function __construct(DBMapper $mapper, Service $service) {
        $this->mapper = $mapper;
        $this->service = $service;
    }

	public function getList()
	{
        /*$request = $this->getRequest();

		$start = null;
		$count = null;

		$range = $request->getHeaders()->get('Range');

		if($range) {
			$range = explode('=', $range);
			$range = end($range);
			$range = explode('-', $range);
			$start = $range[0];
			$end = $range[1];
			$count = $end? $end - $start +1: null;
		}

		$order = $request->getPost()->get('orderBy');
		$orderBy = array();
		//check if one order statement have to be passed to the query
		if($order) {
			$orderBy['order'] = (substr($order, 0, 1) == '-')? 'desc': 'asc';
			$orderBy['sort'] = substr($order, 1);
		}

		$query = $request->getQuery();*/

        $data = $this->mapper->findAll();
        //echo "<pre>";
        //print_r($data);
        //exit;
        // need to convert $data to array for json output
		return new JsonModel($data);
	}

	public function get($id)
	{
        try {
            $entity = $this->mapper->findById($id);
        } catch (\DysBase\Service\Exception\NotFound $e) {
            throw new \Zend\Mvc\Exception\ExceptionInterface(
                $e->getMessage(),
                404,
                $e
            );
        } 
        echo "<pre>";
        print_r($entity);
        exit;
        // need to convert $data to array for json output
        return new JsonModel(array('data' => $entity));
	}

	public function create($data)
	{
		$entity = $this->service->create($data);
        $entity = $this->mapper->insert($entity);
        echo "<pre>";
        print_r($entity);
        exit;

        // todo convert to array for jsonmodel
        return new JsonModel(array('data' => $entity));
		// 
        /*$this->getResponse()->setHeader(
            'Location',
            '/' . lcfirst($this->_packageName) . $this->entityName . '/'
            . $dto->id,
            true
        );*/
        //$this->getResponse()->setHttpResponseCode(
		//return array('data' => 'create');
	}

	public function update($id, $data)
	{
        //$data['email'] = 'system@vortex.com';
        //$data['password'] = 'system33';
        $entity = $this->service->update($id, $data);
        $entity = $this->mapper->update($entity);
        echo "<pre>";
        print_r($entity);
        exit;
        // todo convert to array for jsonmodel
        return new JsonModel(array('data' => $entity));
	}


	public function delete($id)
	{
        try {
            $entity = $this->mapper->findById($id);
        } catch (\RestCrudDoctrineModule\Service\Exception\NotFound $e) {
            throw new \Zend\Controller\Action\Exception(
                $e->getMessage(),
                404,
                $e
            );
        }

        try {
            $this->mapper->remove($entity);
        } catch (RestCrudDoctrineModule\Service\Exception\UnexpectedValueException $e) {
            throw new \Zend\Controller\Action\Exception(
                $e->getMessage(),
                405,
                $e
            );
        }
        
        echo 'deleted';
        exit;
        $this->getResponse()->setHttpResponseCode(204);
		//return array('data' => 'delete');
	}

	public function getService()
	{
		return $this->service;
	}

    public function getMapper()
    {
        return $this->mapper;
    }
}