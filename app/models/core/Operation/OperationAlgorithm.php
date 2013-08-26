<?php namespace core\Operation;

use \Illuminate\Database\Query\Builder;

class OperationAlgorithm extends \BaseAlgorithm {

	/**
	 * Get an empty query for this model.
	 *
     * @return Builder
	 */
    public function emptyQuery()
    {
        return Operation::query();
    }
}