<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;

use App\Models\Cart;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class WishlistController extends BaseController
{
    //
   


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $products =  auth()->user()
            ->wishlist()
            ->latest()
            ->get(); 
            if($products->count()==0)
             return $this->SendError('there is no wishlist');
  
        return $this->SendResponse($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {

           }

    
    public function destroy()
    {
        auth()->user()->wishlist()->detach(request('product_id'));
    }
}


