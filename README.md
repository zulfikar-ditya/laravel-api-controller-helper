# Laravel Api Controller Helper 
This is my controller api helper for laravel app

## Usage

> Move file ControllerHelper.php to App\Http\Helpers
> then add ControllerHelper trait in \App\Http\Controllers\Controller

```
...
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use ControllerHelper;
...
```

## Example
```
<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Airline as model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class AirlineController extends Controller
{
    /**
     * listing data table
     *
     * @var array $data_table
     */
    public $data_table = ['name'];

    /**
     * set get searching query
     *
     * @param string $value
     */
    public function search_data_table($value)
    {
        $arr = [];
        foreach($this->data_table as $item) {
            array_push($arr, [$item, 'like', '%'.$value.'%']);
        }
        return $arr;
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $order_direction = $request->get('order_direction', 'DESC');
        $order_by = $request->get('order_by', 'created_at');
        $paginate = $request->get('paginate', 10);
        $search = $request->get('search');
        $new_model = new model();
        if(!is_null($search) or $search != '') {
            $data = $new_model->where($this->search_data_table($search));
        } else {
            $data = $new_model;
        }
        $model = $data->orderBy($order_by, $order_direction)->paginate($paginate);
        if(empty($model->items())) return $this->ResponseJsonDataTable($model->items(), $model->total(), 'data kosong', 204);;
        return $this->ResponseJsonDataTable($model->items(), $model->total());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model = new model();
        $validate = Validator::make($request->all(), $model->rules('create'));
        if($validate->fails()) return $this->ResponseJsonValidate($validate->errors());
        $model->loadModel($request->all());
        if($request->hasFile('image')) $model->image = $this->upload_file($request->file('image'), 'airline');
        try {
            $model->save();
        } catch (\Throwable $th) {
            return $this->ResponseJsonMessageCRUD(false, 'create', null, $th->getMessage(), 500);
        }
        return $this->ResponseJsonMessageCRUD(true);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = model::find($id);
        if(!$model) return $this->ResponseJsonNotFound();
        return $this->ResponseJsonData($model);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $model = model::find($id);
        if(!$model) return $this->ResponseJsonNotFound();
        $old_file = $model->image;
        $new_model = new model;
        $validate = Validator::make($request->all(), $new_model->rules('update', $id));
        if($validate->fails()) return $this->ResponseJsonValidate($validate->errors());
        $model->loadModel($request->all());
        if ($request->hasFile('image')) {
            $this->delete_file($old_file);
            $model->image = $this->upload_file($request->file('image'), 'airline');
        }
        try {
            $model->save();
        } catch (\Throwable $th) {
            return $this->ResponseJsonMessageCRUD(false, 'edit', null, $th->getMessage(), 500);
        }
        return $this->ResponseJsonMessageCRUD(true, 'edit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = model::find($id);
        if(!$model) return $this->ResponseJsonNotFound();
        $this->delete_file($model->image);
        try {
            $model->delete();
        } catch (\Throwable $th) {
            return $this->ResponseJsonMessageCRUD(false, 'delete', null, $th->getMessage(), 500);
        }
        return $this->ResponseJsonMessageCRUD(true, 'delete');
    }
}


```
