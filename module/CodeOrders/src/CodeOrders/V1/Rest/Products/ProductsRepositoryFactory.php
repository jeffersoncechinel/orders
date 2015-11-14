<?php
/**
 * @Author: jefferson
 * @Date:   2015-08-23 16:22:33
 * @Last Modified by:   jefferson
 * @Last Modified time: 2015-08-23 18:50:24
 */

namespace CodeOrders\V1\Rest\Products;

use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class ProductsRepositoryFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $dbAdapter = $serviceLocator->get('dbAdapter');
        $hydrator = new HydratingResultSet(new ClassMethods(), new ProductsEntity());
        $tableGateway = new TableGateway('products', $dbAdapter, null, $hydrator);

        $productsRepository = new ProductsRepository($tableGateway);

        return $productsRepository;
    }
}
