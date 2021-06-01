<?php

namespace App\Http\Controllers\API;


use Illuminate\Support\Facades\Event;
use app\Http\Resources\Wishlist as WishlistResource;
use app\Http\Resources\Cart as CartResource;
use App\Http\Controllers\API\BaseController as BaseController;

use Cart;

class WishlistController extends BaseController
{
   
public function index()

    {
        $wishlistItems = wishlist::getCustomerWhishlist();

        $this->sendResponse(wishlistResource::collection($wishlistItems));
    }

    public function create($id)
    {
        

    
        $wishlistItem = $this-> wishlist::findOneWhere([
            
            'product_id'  => $id,
            'customer_id' => $user->id,
        ]);

        if (! $wishlistItem) {
            $wishlistItem = create([
                'product_id'  => $id,
                'customer_id' => $user->id,
            ]);

            return response()->json([
                'data'    => new WishlistResource($wishlistItem),
                'message' => 'update wishlist successfully',
            ]);
        } else {
            $this-> wishlist::delete($wishlistItem->id);

            return response()->json([
                'data'    => null,
                'message' => 'Item removed from wishlist successfully.',
            ]);
        }
    }
     public function moveToCart($id)
    {
        $wishlistItem = $this-> wishlist::findOrFail($id);

        if ($wishlistItem->customer_id != auth::id() {
            return response()->json([
                'message' => 'You do not have rights',
            ], 400);
        }

        $result = Cart::moveToCart($wishlistItem);

        if ($result) {
            Cart::collectTotals();

            $cart = Cart::getCart();

            return response()->json([
                'data' => $cart ? new CartResource($cart) : null,
                'message' => 'wishlist moved',
            ]);
        } else {
            return response()->json([
                'data' => -1,
                'error' => 'wishlist did not moved',
            ], 400);
        }
    }
}


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



