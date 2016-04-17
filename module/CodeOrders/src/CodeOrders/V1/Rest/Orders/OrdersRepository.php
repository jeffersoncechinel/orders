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
    private $clientsTableGateway;

    public function __construct(TableGatewayInterface $tableGateway, TableGatewayInterface $orderItemTableGateway, TableGatewayInterface $clientsTableGateway)
    {
        $this->tableGateway = $tableGateway;
        $this->orderItemTableGateway = $orderItemTableGateway;
        $this->clientsTableGateway = $clientsTableGateway;
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
                $order->addItem($item);
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

        if ($resultSet->count() == 1) {
            $hydrator = new ClassMethods();
            $hydrator->addStrategy('items', new OrderItemHydratorStrategy(new ClassMethods()));
            $order = $resultSet->current();

            $client = $this->clientsTableGateway->select(['id' => $order->getClientId()])->current();
            $sql = $this->orderItemTableGateway->getSql();
            $select = $sql->select();
            $select->join('products', 'order_items.product_id = products.id',
                ['product_name' => 'name'])
                ->where(['order_id' => $order->getId()]);

            $items = $this->orderItemTableGateway->selectWith($select);
            $order->setClient($client);

            foreach ($items as $item) {
                $order->addItem($item);
            }

            $data = $hydrator->extract($order);
            return $data;

        }

        return false;
        //return $resultSet->current();
    }

    public function delete($id)
    {
        $this->deleteItem($id);
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

    public function deleteItem($id)
    {
        //Eu tinha feito delete on cascade no banco anteriormente. Por isso não tinha implementado esse metodo aqui.
        $result = $this->orderItemTableGateway->delete(['order_id' => (int)$id]);

        return $result;
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
