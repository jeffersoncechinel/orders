<?php
/**
 * apigility-ionic - OrdersService.php
 * Initial version by: Jefferson Cechinel (jefferson@homeyou.com)
 * Initial version created on: 14/11/2015 17:22
 */

namespace CodeOrders\V1\Rest\Orders;


use Zend\Stdlib\Hydrator\ObjectProperty;

class OrdersService
{

    /**
     * @var OrdersRepository
     */
    private $repository;

    public function __construct(OrdersRepository $repository)
    {
        $this->repository = $repository;
    }

    public function insert($data)
    {
        $hydrator = new ObjectProperty(); //Hidrate StdClass to array
        $data = $hydrator->extract($data);


        $orderData = $data;
        unset($orderData['item']);
        $items = $data['item'];

        $tableGateway = $this->repository->getTableGateway();

        try {
            $tableGateway->getAdapter()->getDriver()->getConnection()->beginTransaction();
            $order_id = $this->repository->insert($orderData);

            foreach ($items as $item) {
                $item['order_id'] = $order_id;
                $this->repository->insertItem($item);
            }

            $tableGateway->getAdapter()->getDriver()->getConnection()->commit();
            return ['order_id' => $order_id];

        } catch (\Exception $e) {
            $tableGateway->getAdapter()->getDriver()->getConnection()->rollback();
            return 'error';
        }

    }
}