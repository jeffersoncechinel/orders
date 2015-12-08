<?php
/**
 * apigility-ionic - OrderItemHydratorStrategy.php
 * Initial version by: Jefferson Cechinel (jefferson@homeyou.com)
 * Initial version created on: 14/11/2015 16:51
 */

namespace CodeOrders\V1\Rest\Orders;


use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class OrderItemHydratorStrategy implements StrategyInterface
{

    /**
     * @var ClassMethods
     */
    private $hydrator;

    public function __construct(ClassMethods $hydrator)
    {

        $this->hydrator = $hydrator;
    }

    /**
     * Converts the given value so that it can be extracted by the hydrator.
     *
     * @param mixed $value The original value.
     * @param object $object (optional) The original object for context.
     * @return mixed Returns the value that should be extracted.
     */
    public function extract($items)
    {
        $data = [];
        foreach ($items as $item) {
            $data[] = $this->hydrator->extract($item);
        }

        return $data;
    }

    /**
     * Converts the given value so that it can be hydrated by the hydrator.
     *
     * @param mixed $value The original value.
     * @param array $data (optional) The original data for context.
     * @return mixed Returns the value that should be hydrated.
     */
    public function hydrate($value)
    {
        throw new \RuntimeException('Hydrate is not supported.');
    }
}