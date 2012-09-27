<?php

namespace RestCrudDoctrineModule;

use ZfcBase/Module/AbstractModule as ZfcBaseAbstractModule;

class Module extends ZfcBaseAbstractModule
{
	public function getDir()
	{
		
		return __DIR__;	
	}

	public function getNamespace()
	{
		return __NAMESPACE__;
	}
}