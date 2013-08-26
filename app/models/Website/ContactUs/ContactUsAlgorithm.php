<?php namespace Website\ContactUs;

use \Illuminate\Database\Query\Builder;

class ContactUsAlgorithm extends \BaseAlgorithm {

	/**
	 * Get an empty query for this model.
	 *
     * @return Builder
	 */
    public function emptyQuery()
    {
        return ContactUs::query();
    }
}