<?php
namespace RestCrudDoctrineModule;

return array(
    'service_manager' => array(
        'factories' => array(
            'rest_crud_base_mapper' => function($sm) {
                return new Mapper\BaseMapper(
                    $sm->get('doctrine.entitymanager.orm_default'),
                    'ZfcUserDoctrineORM\Entity\User'
                );
            },
            'rest_crud_base_service' => function($sm) {
                return new Service\BaseRestService(
                    $sm->get('rest_crud_base_mapper')
                );
            },
        ),
    ),
);
