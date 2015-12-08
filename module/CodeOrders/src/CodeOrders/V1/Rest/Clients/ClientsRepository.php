<?php
/**
 * apigility-ionic - ClientsRepository.php
 * Initial version by: Jefferson Cechinel (jefferson@homeyou.com)
 * Initial version created on: 08/12/2015 00:19
 */

namespace CodeOrders\V1\Rest\Clients;

use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Paginator\Adapter\DbTableGateway;

class ClientsRepository
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

        return new ClientsCollection($paginatorAdapter);
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
