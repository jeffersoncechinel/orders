<?php
/**
 * @Author: jefferson
 * @Date:   2015-08-23 16:22:33
 * @Last Modified by:   jefferson
 * @Last Modified time: 2015-08-23 18:50:24
 */

namespace CodeOrders\V1\Rest\Users;

use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class UsersRepositoryFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $dbAdapter = $serviceLocator->get('dbAdapter');
        //$usersMapper = new UsersMapper(); //Used if database fild names differ from the Entity otherwise use ClassMethods.
        //$hydrator = new HydratingResultSet($usersMapper, new UsersEntity());
        $hydrator = new HydratingResultSet(new ClassMethods(), new UsersEntity());
        $tableGateway = new TableGateway('oauth_users', $dbAdapter, null, $hydrator);
        $auth = $serviceLocator->get('api-identity');

        $usersRepository = new UsersRepository($tableGateway, $auth);

        return $usersRepository;
    }
}
