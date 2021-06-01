<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;



class payment extends Model
{
    use HasFactory;
    protected $fillable = ['amount','user_id','order_id','payment_method','paid_on','payment_reference'];
    
    public function customer(){
    	 return $this -> belongsTo(User::class);}

public function order(){
    	 return $this -> belongsTo(order::class);}



    	 public function getPaymentMethods()
    {
        $paymentMethods = [];

        foreach (Config::get('paymentmethods') as $paymentMethod) {
            $object = app($paymentMethod['class']);

            if ($object->isAvailable()) {
                $paymentMethods[] = [
                    'method'       => $object->getCode(),
                    'method_title' => $object->getTitle(),
                    'description'  => $object->getDescription(),
                    'sort'         => $object->getSortOrder(),
                ];
            }
        }

        usort ($paymentMethods, function($a, $b) {
            if ($a['sort'] == $b['sort']) {
                return 0;
            }

            return ($a['sort'] < $b['sort']) ? -1 : 1;
        });

        return $paymentMethods;
    }
public function getRedirectUrl($cart)
    {
        $payment = app(Config::get('paymentmethods.' . $cart->payment->method . '.class'));

        return $payment->getRedirectUrl();
    }

    /**
     * Returns payment method additional information
     *
     * @param  string  $code
     * @return array
     */
    public static function getAdditionalDetails($code)
    {
        $paymentMethodClass =  app(Config::get('paymentmethods.' . $code . '.class'));
        
        return $paymentMethodClass->getAdditionalDetails();
    }

    protected $fillable = ['amount','user_id','order_id','payment_method','paid_on','payment_reference',];
    public function customer(){
    	 return $this -> belongsTo(User::class, 'user_id');}


}
