<?php

namespace app\Controllers\API;

use Webkul\Checkout\Facades\Cart;
use app\models\Order;
use Webkul\Paypal\Helpers\Ipn;

class StandardController extends Controller
{
  protected $ipnHelper;

    public function __construct(
        OrderRepository $orderRepository,
        Ipn $ipnHelper
    )
    {
        $this->orderRepository = $orderRepository;

        $this->ipnHelper = $ipnHelper;
    }

    public function redirect()
    {
        return view('paypal::standard-redirect');
    }

  
    public function cancel()
    {
        session()->flash('error', 'Paypal payment has been canceled.');

        return redirect()->route('shop.checkout.cart.index');
    }

    /**
     * Success payment
     */
    public function success()
    {
        $order = $this->orderRepository->create(Cart::prepareDataForOrder());

        Cart::deActivateCart();

        session()->flash('order', $order);

        return redirect()->route('shop.checkout.success');
    }

    public function ipn()
    {
        $this->ipnHelper->processIpn(request()->all());
    }
}