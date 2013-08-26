<?php namespace Gallery\Group;

use \Illuminate\Database\Query\Builder;

class GroupAlgorithm extends \BaseAlgorithm {

    /**
     * @param string $name
     * @return $this
     */
    public function byName( $name )
    {
        $this->getQuery()->where('name', $name);

        return $this;
    }

	/**
	 * Get an empty query for this model.
	 *
     * @return Builder
	 */
    public function emptyQuery()
    {
        return Group::query();
    }
}