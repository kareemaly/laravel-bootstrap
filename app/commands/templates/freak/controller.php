<?php

return '<?php

use core\Model;

class '. ucfirst($model) .'Controller extends AppController {

    protected $app;

	public function __construct(app\models\App\AppRepository $app)
	{
		parent::__construct(Model::get( \''. ucfirst($model) .'\' ));

		$this->app = $app;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		Asset::addPlugin(\'datatables\');

		$'.Str::plural(strtolower($model)).' = '.ucfirst($model).'::all();

		return View::make(\''. Str::plural(strtolower($model)) .'.data\', compact(\''.Str::plural(strtolower($model)).'\'));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$'.strtolower($model).' = '.ucfirst($model).'::find( $id );

		return View::make(\''. Str::plural(strtolower($model)) .'.detail\', compact(\''.strtolower($model).'\'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		Asset::addPage(\'model_add\');

		return View::make(\''. Str::plural(strtolower($model)) .'.add\')->with(\''.strtolower($model).'\', new EmptyClass);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		return $this->create()->with(\''.strtolower($model).'\', '.ucfirst($model).'::find($id))->with(\'edit\', true);
	}

	/**
	 * Validate resource before storing in storage
	 *
	 * @return Response
	 */
	public function validate()
	{
		$validator = Validator::make(Input::get(\''.ucfirst($model).'\'), array(

			// Validations

		));

		if($validator->fails())
		
			return Response::json(array( \'message\' => \'failed\', \'body\' => $validator->messages()->all(\':message\')));

		else

			return Response::json(array( \'message\' => \'success\', \'body\' => \'Inputs are validated successfully\'));

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$'.strtolower($model).' = new '.ucfirst($model).'(Input::get(\''.ucfirst($model).'\'));

        $'.strtolower($model).'->save();

		return Response::json(array(\'insert_id\' => $'.strtolower($model).'->id, \'message\' => \'success\'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$'.strtolower($model).' = '.ucfirst($model).'::find($id);

        $'.strtolower($model).'->update(Input::get(\'' . ucfirst($model) . '\'));
		
		return Response::json(array(\'message\' => \'success\'));
	}

	/**
	 * Upload Image
	 *
	 * @param  int    $id
	 * @return Response
	 */
	public function upload( $id )
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		'.ucfirst($model).'::find($id)->delete();
	
		return Redirect::back()->with(\'success\', \''.ucfirst($model).' deleted successfully.\');
	}

	/**
	 * Destroy images from storage
	 * 
	 * @param  int  $id
	 * @return Response
	 */
	public function destroyImages( $id )
	{

	}

}';