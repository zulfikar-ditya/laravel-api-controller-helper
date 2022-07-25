# Laravel Api Controller Helper 
This is my controller api helper for laravel app

## Usage

> - Update your Handler.php
> - Move file ControllerHelper.php to App\Http\Helpers
> - then add ControllerHelper trait in \App\Http\Controllers\Controller

```
...
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ControllerHelper;
...
```

## Example using helper
```
<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\User as model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * listing data table
     * 
     * @var array $data_table
     */
    public $data_table = ['name', 'email', 'phone'];

    public $search = '';

    /**
     * Display a listing of the resource.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // * search value order by, paginate
        $order_direction = $request->get('order_direction', 'DESC');
        $order_by = $request->get('order_by', 'created_at');
        $paginate = $request->get('paginate', 10);
        $this->search = $request->get('search');
        
        // * get data
        $new_model = new model();
        if(!is_null($this->search) or $this->search != '') {
            $data = $new_model->Where(function ($query) {
                foreach ($this->data_table as $key => $value) {
                    $query->orWhere($value, 'LIKE', "%$this->search%");
                }
            });
        } else {
            $data = $new_model;
        }
        $model = $data->orderBy($order_by, $order_direction)->with(['roles'])->paginate($paginate);
        
        // * model empty return 204
        if (empty($model->items())) return $this->ResponseJsonDataTable($model->items(), $model->total(), 'data null', 204);
        
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
        
        // * validate
        $this->validate_api($request->all(), $model->rules());
        
        // * load data
        $model->loadModel($request->all());
        $model->password = Hash::make($request->password);
        if ($request->hasFile('avatar')) $model->avatar = $this->upload_file($request->file('avatar'), 'avatar');
        try {
            $model->save();
        } catch (\Throwable $th) {
            return $this->ResponseJsonMessageCRUD(false, 'create', null, $th->getMessage(), 422);
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
        $model = model::findOrFail($id);
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
        // * get data
        $model = model::findOrFail($id);
        $old_image = $model->avatar;

        // * validate
        if ($request->hasFile('avatar')) {
            $img_validate = [
                'avatar' => 'required|file|image|max:8048|mimes:jpg,jpeg,png',
            ];
        }
        $new_model = new model;
        $this->validate_api($request->all(), array_merge($new_model->rules('update', $id), $img_validate ?? []));
        
        // * load data
        $model->loadModel($request->all());
        if($request->password) {
            $model->password = Hash::make($request->password);
        }
        
        // * delete and store new image
        if ($request->hasFile('avatar')) {
            $this->delete_file($old_image ?? '');
            $model->avatar = $this->upload_file($request->file('avatar'), 'avatar');
        }
        
        try {
            $model->save();
        } catch (\Throwable $th) {
            return $this->ResponseJsonMessageCRUD(false, 'edit', null, $th->getMessage(), 422);
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
        $model = model::findOrFail($id);
        $this->delete_file($model->avatar ?? '');
        try {
            $model->delete();
        } catch (\Throwable $th) {
            return $this->ResponseJsonMessageCRUD(false, 'delete', null, $th->getMessage(), 422);
        }
        return $this->ResponseJsonMessageCRUD(true, 'delete');
    }
}

```
