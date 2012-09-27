<?php

namespace RestCrudDoctrineModule\Controller;

use Zend\Mvc\Controller\AbstractRestfulController as ZendAbstractRestfulController;

abstract class AbstractRestfulController extends ZendAbstractRestfulController
{
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
		return array('data' => 'id');
	}

	public function create($data)
	{
		return array('data' => 'create');
	}

	public function update($id, $data)
	{
		return array('data' => 'update');
	}

	public function delete($id)
	{
		return array('data' => 'delete');
	}
}