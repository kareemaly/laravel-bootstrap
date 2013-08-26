<?php namespace Website\Page;

use \Illuminate\Database\Query\Builder;

class PageAlgorithm extends \BaseAlgorithm {

    /**
     * @return $this
     */
    public function inMenu()
    {
        $this->getQuery()->where('show_in_menu', 1);

        return $this;
    }

	/**
	 * Get an empty query for this model.
	 *
     * @return Builder
	 */
    public function emptyQuery()
    {
        return Page::query();
    }
}