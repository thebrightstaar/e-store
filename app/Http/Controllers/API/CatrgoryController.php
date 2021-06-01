<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Models\Catrgory;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Catrgory as CatrgoryResource;



class CatrgoryController extends BaseController
{
    //
    public function index()
    {

        $categories=[],

        if (array_key_exists($id, $categories)) {
            return$this-> sendResponse(CatrgoryResource::collection($categories[$id]);
        
          }
        return  $this->sendResponse(CatrgoryResource::collection ($categories[$id] = $id
               ? $this->model::orderBy('position', 'ASC')->where('status', 1)->descendantsAndSelf($id)->toTree($id)
               : $this->model::orderBy('position', 'ASC')->where('status', 1)->get()->toTree();));
    


    }
  
    public function create()
    {
         return $this-> ->sendResponse(CatrgoryResource::collection( $categories =  $id
               ? $this-> category::orderBy('position', 'ASC')->where('id', '!=', $id)->get()->toTree()
               : $this-> category::orderBy('position', 'ASC')->get()->toTree();));
                

  

   }
    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        
            

        $category = request()->all());

        $validator = Validator::make($input,[
            'catrgory_name'=>'required',
             'up_name'=>'required',
             'parent_id'=>'required',
          
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validate Error',$validator->errors() );
        }

        $user = Auth::user()->where(['is_admin'=> 1]);
        if ( $user->is_admin != 1) {
            return $this->sendError('you dont have rights' , $validator->errors());
        }
        
        $category = category::create($input);
        return $this->sendResponse($category, 'category added Successfully!' );

    }

    public function update($id)
    {
        $input = $request->all();
        $validator = Validator::make($input,[
            'catrgory_name'=>'required',
             'up_name'=>'required',
             'parent_id'=>'required',
          ]);
        if ($validator->fails()) {
            return $this->sendError('Validation error' , $validator->errors());
        }
        $user = Auth::user()->where('is_admin'=> 1);
         if ( $user->is_admin != 1) {
            return $this->sendError('you dont have rights' , $validator->errors());
        }
         
        $category->catrgory_name = $input['catrgory_name'];
        $category->up_name = $input['up_name'];
        $category->parent_id = $input['parent_id'];
        $category->save();

        return $this->sendResponse(new CatrgoryResource($category), 'category updated Successfully!' );
  }
}


    