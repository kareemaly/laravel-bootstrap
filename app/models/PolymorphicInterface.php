<?php

interface PolymorphicInterface{

    /**
     * @param BaseModel $model
     * @return $this
     */
    public function attachTo(BaseModel $model);
}