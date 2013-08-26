<?php

use Zizaco\FactoryMuff\FactoryMuff;

class TestCase extends Illuminate\Foundation\Testing\TestCase {

    /**
     * @var array
     */
    protected $nestedViewData = array();

    public function __construct()
    {
        $this->factory = new FactoryMuff();
    }

    /**
     * Setting up tests..
     */
    public function setUp()
    {
        // I forgot to call the parent setUp method
        parent::setUp();

        Mail::pretend();

        // We first have to set up the database with our migrations..
        $this->setUpDB();
    }

    /**
     * Setting Up database
     */
    private function setUpDB()
    {
        Artisan::call('migrate');

        Artisan::call('db:seed');
    }

    public function tearDown()
    {
        Mockery::close();
    }

	/**
	 * Creates the application.
	 *
	 * @return Symfony\Component\HttpKernel\HttpKernelInterface
	 */
	public function createApplication()
	{
		$unitTesting = true;

		$testEnvironment = 'testing';

		return require __DIR__.'/../../bootstrap/start.php';
	}

    /**
     * @param BaseModel $model
     * @return string
     */
    protected function showValidationMessages( BaseModel $model )
    {
        return implode(PHP_EOL, $model->getValidatorMessages()->all());
    }

    /**
     * @param $view
     */
    public function registerNestedView($view)
    {
        View::composer($view, function($view){
            $this->nestedViewsData[$view->getName()] = $view->getData();
        });
    }

    /**
     * Assert that the given view has a given piece of bound data.
     *
     * @param $view
     * @param  string|array $key
     * @param  mixed $value
     * @return void
     */
    public function assertNestedViewHas($view, $key, $value = null)
    {
        if (is_array($key)) return $this->assertNestedViewHasAll($view, $key);

        if ( ! isset($this->nestedViewsData[$view]))
        {
            return $this->assertTrue(false, 'The view was not called.');
        }

        $data = $this->nestedViewsData[$view];

        if (is_null($value))
        {
            $this->assertArrayHasKey($key, $data);
        }
        else
        {
            if(isset($data[$key]))
                $this->assertEquals($value, $data[$key]);
            else
                return $this->assertTrue(false, 'The View has no bound data with this key.');
        }
    }

    /**
     * Assert that the view has a given list of bound data.
     *
     * @param $view
     * @param  array $bindings
     * @return void
     */
    public function assertNestedViewHasAll($view, array $bindings)
    {
        foreach ($bindings as $key => $value)
        {
            if (is_int($key))
            {
                $this->assertNestedViewHas($view, $value);
            }
            else
            {
                $this->assertNestedViewHas($view, $key, $value);
            }
        }
    }

    /**
     * @param $view
     */
    public function assertNestedView($view)
    {
        $this->assertArrayHasKey($view, $this->nestedViewsData);
    }

}
