<?php namespace Extensions\Ordered;

use Illuminate\Database\Eloquent\Collection;
use BaseModel;

class OrderedCollection extends Collection {

    /**
     * @param $order
     * @return BaseModel
     */
    public function getByOrder( $order )
    {
        foreach($this->items as $item)
        {
            if($item->getOrder() == $order) return $item;
        }
    }

    /**
     * Order collection
     *
     * @return $this
     */
    public function order()
    {
        $this->sort(function( BaseModel $a, BaseModel $b )
        {
            if($a->getOrder() == $b->getOrder()) return 0;

            return $a->getOrder() < $b->getOrder() ? -1 : 1;
        });

        return $this;
    }
}