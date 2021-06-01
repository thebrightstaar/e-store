<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;

use App\Models\Cart;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;



class cartControler extends BaseController
{
    public function addToCart(Product $product) {
        
        

        if (session()->has('cart')) {
            $cart = new Cart(session()->get('cart'));
        } else {
            $cart = new Cart();
        }
                if ($this->has($product)) {
            $quantity = $this->get($product)['quantity'] + $quantity;
        }
                    else {
                     $cart->add($product);
                        }
        
        session()->put('cart', $cart);
        return $this->redirect()->route('product.index')->with('success', 'Product was added');
    }

    public function showCart() {

        if (session()->has('cart')) {
            $cart = new Cart(session()->get('cart'));
        } else {
            $cart = null;
        }

        return $this->sendRrsponse($cart);
    }



}
