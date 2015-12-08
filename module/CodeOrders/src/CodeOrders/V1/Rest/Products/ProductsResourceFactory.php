<?php
namespace CodeOrders\V1\Rest\Products;

class ProductsResourceFactory
{
    public function __invoke($services)
    {
        $productsRepository = $services->get('CodeOrders\\V1\\Rest\\Products\\ProductsRepository');
        $usersRepository = $services->get('CodeOrders\\V1\\Rest\\Users\\UsersRepository');

        return new ProductsResource($productsRepository, $usersRepository);
    }
}
