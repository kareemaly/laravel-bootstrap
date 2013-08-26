<?php

return '<?php namespace ' .$namespace . ';

use \Illuminate\Database\Query\Builder;

class ' .$class. 'Algorithm extends \BaseAlgorithm {

	/**
	 * Get an empty query for this model.
	 *
     * @return Builder
	 */
    public function emptyQuery()
    {
        return '. $class . '::query();
    }
}';