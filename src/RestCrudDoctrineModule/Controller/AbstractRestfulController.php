<?php

namespace RestCrudDoctrineModule\Controller;

use Zend\Mvc\Controller\AbstractRestfulController as ZendAbstractRestfulController;

abstract class AbstractRestfulController extends ZendAbstractRestfulController
{
	protected $service;

	public function getList()
	{
		$request = $this->getRequest();

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

		$query = $request->getQuery();

		return array('data' => 'list');
	}

	public function get($id)
	{
        try {
            $model = $this->service->getById($id);
        } catch (\DysBase\Service\Exception\NotFound $e) {
            throw new \Zend\Mvc\Exception\ExceptionInterface(
                $e->getMessage(),
                404,
                $e
            );
        } catch (\DysBase\Service\Exception\ExceptionInterface $e) {
            throw new \Zend\Mvc\Exception\ExceptionInterface(
                $e->getMessage(),
                500,
                $e
            );
        }
        
        return $model;
	}

	public function create($data)
	{
		$service->save(null, $data);
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
		$service->save($id, $data);
		//$this->save();
		//return array('data' => 'update');
	}


	public function delete($id)
	{
		$service->delete($id);
        try {
            $model = $this->service->getById($id);
        } catch (\RestCrudDoctrineModule\Service\Exception\NotFound $e) {
            throw new \Zend\Controller\Action\Exception(
                $e->getMessage(),
                404,
                $e
            );
        }

        try {
            $service->delete($model);
        } catch (RestCrudDoctrineModule\Service\Exception\UnexpectedValueException $e) {
            throw new \Zend\Controller\Action\Exception(
                $e->getMessage(),
                405,
                $e
            );
        }
        
        $this->getResponse()->setHttpResponseCode(204);
		//return array('data' => 'delete');
	}

	public function setService($service)
	{
		$this->service = $service;
	}
}