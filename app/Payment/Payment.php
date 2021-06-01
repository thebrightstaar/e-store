<?php

namespace app\Payment;

use Illuminate\Support\Facades\Config;
use app\Facades\Cart;

abstract class Payment
{
    /**
     * Cart object
     *
     * @var Cart
     */
    protected $cart;

    /**
     * Checks if payment method is available
     *
     * @return array
     */
    public function isAvailable()
    {
        return $this->getConfigData('active');
    }

    /**
     * Returns payment method code
     *
     * @return array
     */
    public function getCode()
    {
        if (empty($this->code)) {
            
        }

        return $this->code;
    }

    /**
     * Returns payment method title
     *
     * @return array
     */
    public function getTitle()
    {
        return $this->getConfigData('title');
    }

    /**
     * Returns payment method description
     *
     * @return array
     */
    public function getDescription()
    {
        return $this->getConfigData('description');
    }

    /**
     * Retrieve information from payment configuration
     *
     */
    public function getConfigData($field)
    {
        return core()->getConfigData('sales.paymentmethods.' . $this->getCode() . '.' . $field);
    }

    abstract public function getRedirectUrl();

    /**
     * Assign cart
     *
     * @var void
     */
    public function setCart()
    {
        if (! $this->cart) {
            $this->cart = Cart::getCart();
        }
    }

    /**
     * Returns cart insrance
     *
     * @var mixed
     */
    public function getCart()
    {
        if (! $this->cart) {
            $this->setCart();
        }

        return $this->cart;
    }

    /**
     * Return paypal redirect url
     *
     * @var Collection
     */
    public function getCartItems()
    {
        if (! $this->cart) {
            $this->setCart();
        }

        return $this->cart->items;
    }

    /**
     * Returns payment method sort order
     *
     * @return int
     */
    public function getSortOrder()
    {
        return $this->getConfigData('sort');
    }

    /**
     * Returns payment method additional information
     *
     * @return array
     */
    public function getAdditionalDetails()
    {
        if (! $this->getConfigData('instructions')
            || $this->getConfigData('instructions') == ''
        ) {
            return [];
        }

        return [
            'title' => 'instructions',
            'value' => $this->getConfigData('instructions'),
        ];
    }
}