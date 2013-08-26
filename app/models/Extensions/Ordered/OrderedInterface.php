<?php namespace Extensions\Ordered;

use BaseModel;

interface OrderedInterface {

    /**
     * @param int $order
     */
    public function setOrder( $order );

    /**
     * @return int
     */
    public function getOrder();

    /**
     * Exchange orders and save them to database
     *
     * @param BaseModel $model
     */
    public function exchange( BaseModel $model );

    /**
     * This will override the given model which means it will be deleted
     * from database...
     *
     * @param BaseModel $model
     * @return
     */
    public function override( BaseModel $model );

    /**
     * @return int
     */
    public function getLastOrder();

    /**
     * @throws SameOrderException
     */
    public function failIfOrderExists();
}