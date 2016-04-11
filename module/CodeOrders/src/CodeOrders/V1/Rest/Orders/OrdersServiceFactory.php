<?php
/**
 * apigility-ionic - OrdersServiceFactory.php
 * Initial version by: Jefferson Cechinel (jefferson@homeyou.com)
 * Initial version created on: 14/11/2015 17:24
 */

namespace CodeOrders\V1\Rest\Orders;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class OrdersServiceFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $orderRepository = $serviceLocator->get('CodeOrders\\V1\\Rest\\Orders\\OrdersRepository');
        $userRepository = $serviceLocator->get('CodeOrders\\V1\\Rest\\Users\\UsersRepository');
        $productRepository = $serviceLocator->get('CodeOrders\\V1\\Rest\\Products\\ProductsRepository');

        return new OrdersService($orderRepository, $userRepository, $productRepository);
    }
}