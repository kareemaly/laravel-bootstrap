<?php namespace Website\Place;

use \Illuminate\Database\Query\Builder;

class PlaceAlgorithm extends \BaseAlgorithm {

	/**
	 * Get an empty query for this model.
	 *
     * @return Builder
	 */
    public function emptyQuery()
    {
        return Place::query();
    }
}