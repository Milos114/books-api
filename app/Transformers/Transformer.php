<?php


namespace App\Transformers;


abstract class Transformer
{
    /**
     * @param $items
     * @return mixed
     */
    public function transformCollection($items)
    {
        return $items->map(function ($item) {
            return $this->transform($item);
        });
    }

    /**
     * @param $item
     * @return mixed
     */
    public abstract function transform($item);
}