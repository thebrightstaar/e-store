<?php

namespace app\Helpers;

use app\Repositories\OrderRepository;
use app\Repositories\InvoiceRepository;

class Ipn
{
    /**
     * Ipn post data
     *
     * @var array
     */
    protected $post;

    /**
     * Order object
     *
     */
    protected $order;

    /**
     * OrderRepository object
     *
     */
    protected $orderRepository;

    /**
     * InvoiceRepository object
     *
     */
    protected $invoiceRepository;

    /**
     * Create a new helper instance.
     *
     */
    public function __construct(
        OrderRepository $orderRepository,
        InvoiceRepository $invoiceRepository
    )
    {
        $this->orderRepository = $orderRepository;

        $this->invoiceRepository = $invoiceRepository;
    }

    /**
     * This function process the ipn sent from paypal end
     *
     */
    public function processIpn($post)
    {
        $this->post = $post;

        if (! $this->postBack()) {
            return;
        }

        try {
            if (isset($this->post['txn_type']) && 'recurring_payment' == $this->post['txn_type']) {

            } else {
                $this->getOrder();

                $this->processOrder();
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Load order via ipn invoice id
     *
     */
    protected function getOrder()
    {
        if (empty($this->order)) {
            $this->order = $this->orderRepository->findOneByField(['cart_id' => $this->post['invoice']]);
        }
    }

    /**
     * Process order and create invoice
     *
     */
    protected function processOrder()
    {
        if ($this->post['payment_status'] == 'Completed') {
            if ($this->post['mc_gross'] != $this->order->grand_total) {
                return;
            } else {
                $this->orderRepository->update(['status' => 'processing'], $this->order->id);

                if ($this->order->canInvoice()) {
                    $invoice = $this->invoiceRepository->create($this->prepareInvoiceData());
                }
            }
        }
    }

    /**
     * Prepares order's invoice data for creation
     *
     */
    protected function prepareInvoiceData()
    {
        $invoiceData = ["order_id" => $this->order->id,];

        foreach ($this->order->items as $item) {
            $invoiceData['invoice']['items'][$item->id] = $item->qty_to_invoice;
        }

        return $invoiceData;
    }

    /**
     * Post back to PayPal to check whether this request is a valid one
     *
     */
    protected function postBack()
    {
        if (array_key_exists('test_ipn', $this->post) && 1 === (int) $this->post['test_ipn']) {
            $url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
        } else {
            $url = 'https://www.paypal.com/cgi-bin/webscr';
        }

        $request = curl_init();

        curl_setopt_array($request, [
            CURLOPT_URL            => $url,
            CURLOPT_POST           => TRUE,
            CURLOPT_POSTFIELDS     => http_build_query(array('cmd' => '_notify-validate') + $this->post),
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HEADER         => FALSE,
        ]);

        $response = curl_exec($request);
        $status = curl_getinfo($request, CURLINFO_HTTP_CODE);

        curl_close($request);

        if ($status == 200 && $response == 'VERIFIED') {
            return true;
        }

        return false;
    }
}