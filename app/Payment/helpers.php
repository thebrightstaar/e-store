<?php
    use app\Payment;
    
    if (! function_exists('payment')) {
        function payment()
        {
            return new Payment;
        }
    }
