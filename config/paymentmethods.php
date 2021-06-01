<?php
return [
    'cashondelivery'  => [
        'code'        => 'cashondelivery',
        'title'       => 'Cash On Delivery',
        'description' => 'Cash On Delivery',
        'class'       => 'Payment\CashOnDelivery',
        'active'      => true,
        'sort'        => 1,
    ],

    'moneytransfer'   => [
        'code'        => 'moneytransfer',
        'title'       => 'Money Transfer',
        'description' => 'Money Transfer',
        'class'       => 'Payment\MoneyTransfer',
        'active'      => true,
        'sort'        => 2,
    ]
    'paypal_smart_button' => [
        'code'             => 'paypal_smart_button',
        'title'            => 'PayPal Smart Button',
        'description'      => 'PayPal',
        'client_id'        => 'sb',
        'class'            => 'Payment\SmartButton',
        'sandbox'          => true,
        'active'           => true,
        'sort'             => 0,
    ],

    'paypal_standard' => [
        'code'             => 'paypal_standard',
        'title'            => 'PayPal Standard',
        'description'      => 'PayPal Standard',
        'class'            => 'Payment\Standard',
        'sandbox'          => true,
        'active'           => true,
        'business_account' => 'test@ ',//name of host 
        'sort'             => 3,
    ]
];