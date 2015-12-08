<?php
/**
 * @Author: jefferson
 * @Date:   2015-08-23 16:16:48
 * @Last Modified by:   jefferson
 * @Last Modified time: 2015-08-23 21:53:43
 */

namespace CodeOrders\V1\Rest\Orders;

use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Paginator\Adapter\DbTableGateway;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Stdlib\Hydrator\ObjectProperty;

class OrdersRepository
{

    protected $tableGateway;
    /**
     * @var TableGatewayInterface
     */
    private $orderItemTableGateway;

    public function __construct(TableGatewayInterface $tableGateway, TableGatewayInterface $orderItemTableGateway)
    {
        $this->tableGateway = $tableGateway;
        $this->orderItemTableGateway = $orderItemTableGateway;
    }

    public function findAll()
    {
        $orders = $this->tableGateway->select();
        $hydrator = new ClassMethods();
        $hydrator->addStrategy('items', new OrderItemHydratorStrategy(new ClassMethods()));
        $res = [];

        foreach ($orders as $order) {
            $items = $this->orderItemTableGateway->select(['order_id' => $order->getId()]);
            foreach ($items as $item) {
                $order->addItems($item);
            }

            $data = $hydrator->extract($order);
            $res[] = $data;
        }


        $paginatorAdapter = new ArrayAdapter($res);

        return new OrdersCollection($paginatorAdapter);
    }

    public function find($id)
    {
        $resultSet = $this->tableGateway->select(['id' => (int)$id]);

        return $resultSet->current();
    }

    public function delete($id)
    {
        $result = $this->tableGateway->delete(['id' => (int)$id]);

        return $result;
    }

    public function insert(array $data)
    {
        $this->tableGateway->insert($data);
        $id = $this->tableGateway->getLastInsertValue();

        return $id;
    }

    public function update($data, $where)
    {
        $result = $this->tableGateway->update($data, $where);

        return $result;
    }

    public function insertItem(array $data)
    {
        $this->orderItemTableGateway->insert($data);
        $id = $this->orderItemTableGateway->getLastInsertValue();

        return $id;
    }

    public function findOneByUserId($user_id, $id)
    {
        return $this->tableGateway->select(['user_id' => $user_id, 'id' => $id])->current();
    }

    public function findAllByUserId($user_id)
    {
        return $this->tableGateway->select(['user_id' => $user_id]);
    }

    public function getTableGateway()
    {
        return $this->tableGateway;
    }

}
