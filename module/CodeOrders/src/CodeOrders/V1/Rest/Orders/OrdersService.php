<?php
/**
 * apigility-ionic - OrdersService.php
 * Initial version by: Jefferson Cechinel (jefferson@homeyou.com)
 * Initial version created on: 14/11/2015 17:22
 */

namespace CodeOrders\V1\Rest\Orders;


use CodeOrders\V1\Rest\Products\ProductsRepository;
use CodeOrders\V1\Rest\Users\UsersRepository;
use Zend\Stdlib\Hydrator\ObjectProperty;

class OrdersService
{

    /**
     * @var OrdersRepository
     */
    private $repository;
    /**
     * @var UsersRepository
     */
    private $usersRepository;
    /**
     * @var ProductsRepository
     */
    private $productsRepository;

    public function __construct(OrdersRepository $repository, UsersRepository $usersRepository, ProductsRepository $productsRepository)
    {
        $this->repository = $repository;
        $this->usersRepository = $usersRepository;
        $this->productsRepository = $productsRepository;
    }

    public function insert($data)
    {
        $hydrator = new ObjectProperty(); //Hidrate StdClass to array
        $data->user_id = $this->usersRepository->getAuthenticated()->getId();
        $data->created_at = (new \DateTime())->format('Y-m-d');
        $data->total = 0;
        $items = $data->item;
        unset($data->item);

        $orderData = $hydrator->extract($data);

        $tableGateway = $this->repository->getTableGateway();

        try {
            $tableGateway->getAdapter()->getDriver()->getConnection()->beginTransaction();
            $order_id = $this->repository->insert($orderData);

            foreach ($items as $key => $item) {
                $product = $this->productsRepository->find(($item['product_id']));

                $item['order_id'] = $order_id;
                $item['price'] = $product->getPrice();
                $item['total'] = $items[$key]['total'] = $item['quantity'] * $item['price'];
                $total += $item['total'];

                $this->repository->insertItem($item);
            }

            $this->repository->update(['total' => $total], $order_id);
            $tableGateway->getAdapter()->getDriver()->getConnection()->commit();
            return ['order_id' => $order_id];

        } catch (\Exception $e) {
            $tableGateway->getAdapter()->getDriver()->getConnection()->rollback();
            return 'error';
        }

    }
}