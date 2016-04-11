<?php
/**
 * apigility-ionic - ClientsTableGatewayFactory.php
 * Initial version by: Jefferson Cechinel (jefferson@homeyou.com)
 * Initial version created on: 10/04/2016 01:45
 */

namespace CodeOrders\V1\Rest\Clients;


use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;


class ClientsTableGatewayFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $dbAdapter = $serviceLocator->get('dbAdapter');
        $hydrator = new HydratingResultSet(new ClassMethods(), new ClientsEntity());
        $tableGateway = new TableGateway('clients', $dbAdapter, null, $hydrator);

        return $tableGateway;

    }
}