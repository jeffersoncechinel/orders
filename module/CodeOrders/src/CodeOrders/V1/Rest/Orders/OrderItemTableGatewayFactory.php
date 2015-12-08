<?php
/**
 * apigility-ionic - OrderItemTableGatewayFactory.php
 * Initial version by: Jefferson Cechinel (jefferson@homeyou.com)
 * Initial version created on: 14/11/2015 01:45
 */

namespace CodeOrders\V1\Rest\Orders;


use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class OrderItemTableGatewayFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $dbAdapter = $serviceLocator->get('dbAdapter');
        $hydrator = new HydratingResultSet(new ClassMethods(), new OrderItemEntity());
        $tableGateway = new TableGateway('order_items', $dbAdapter, null, $hydrator);

        return $tableGateway;

    }
}