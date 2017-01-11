<?php


namespace App\Transformers;


abstract class Transformer
{
    /**
     * @param $items
     * @return mixed
     */
    public function transformCollection(array $items)
    {
        return array_map(function ($item) {
            return $this->transform($item);
        }, $items);
    }

    /**
     * @param $item
     * @return mixed
     */
    public abstract function transform($item);
}