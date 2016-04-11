<?php
/**
 * @Author: jefferson
 * @Date:   2015-08-23 16:16:48
 * @Last Modified by:   jefferson
 * @Last Modified time: 2015-08-23 21:53:43
 */

namespace CodeOrders\V1\Rest\Products;

use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Paginator\Adapter\DbTableGateway;

class ProductsRepository
{

    protected $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function findAll()
    {
        //return $this->tableGateway->select();
        $paginatorAdapter = new DbTableGateway($this->tableGateway);

        return new ProductsCollection($paginatorAdapter);
    }

    public function find($id)
    {
        $resultSet = $this->tableGateway->select(['id' => (int)$id]);
        if ($resultSet->count() == 1) {
            return $resultSet->current();
        }

        return false;
    }

    public function delete($id)
    {
        $result = $this->tableGateway->delete(['id' => (int)$id]);

        return $result;
    }

    public function insert($data)
    {
        $result = $this->tableGateway->insert($data);

        return $result;
    }

    public function update($data, $where)
    {
        $result = $this->tableGateway->update($data, $where);

        return $result;
    }

}
