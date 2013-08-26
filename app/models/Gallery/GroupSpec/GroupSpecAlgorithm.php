<?php namespace Gallery\GroupSpec;

use \Illuminate\Database\Query\Builder;

class GroupSpecAlgorithm extends \BaseAlgorithm {

	/**
	 * Get an empty query for this model.
	 *
     * @return Builder
	 */
    public function emptyQuery()
    {
        return GroupSpec::query();
    }
}