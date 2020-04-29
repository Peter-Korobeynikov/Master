<?php
/***************************************************************************
 *                                                                          *
 *   (c) 2004 Vladimir V. Kalynyak, Alexey V. Vinokurov, Ilya M. Shalnev    *
 *                                                                          *
 * This  is  commercial  software,  only  users  who have purchased a valid *
 * license  and  accept  to the terms of the  License Agreement can install *
 * and use this program.                                                    *
 *                                                                          *
 ****************************************************************************
 * PLEASE READ THE FULL TEXT  OF THE SOFTWARE  LICENSE   AGREEMENT  IN  THE *
 * "copyright.txt" FILE PROVIDED WITH THIS DISTRIBUTION PACKAGE.            *
 ****************************************************************************/

namespace Tygh\Addons\YandexCheckout\Services;

use Tygh\Addons\RusTaxes\TaxType;

/**
 * Class ReceiptService allows modify receipts into needed for Yandex.Checkout API form.
 *
 * @package Tygh\Addons\YandexCheckout\Services
 */
class ReceiptService
{
    /** @var \Tygh\Addons\RusTaxes\ReceiptFactory $receipt_factory */
    protected $receipt_factory;

    protected $map_taxes;

    public function __construct($receipt_factory, array $map_taxes)
    {
        $this->receipt_factory = $receipt_factory;
        $this->map_taxes = $map_taxes;
    }

    /**
     * @param string $phone
     *
     * @return string|string[]
     */
    protected function normalizePhone($phone)
    {
        $phone_normalize = '';

        if (!empty($phone)) {
            if (strpos('+', $phone) !== 0 && $phone[0] == '8') {
                $phone[0] = '7';
            }
            $phone_normalize = preg_replace('/\D/', '', $phone);
        }
        return $phone_normalize;
    }

    /**
     * @param array $order_info
     * @param       $type
     *
     * @return array
     */
    public function getReceiptFromOrder(array $order_info, $type)
    {
        $receipt = $this->receipt_factory->createReceiptFromOrder($order_info, $order_info['payment_method']['processor_params']['currency']);

        $receipt->setPhone($this->normalizePhone($receipt->getPhone()));

        $products = [];
        foreach ($receipt->getItems() as $item) {
            $products[] = [
                'description' => $item->getName(),
                'quantity' => $item->getQuantity(),
                'amount' => [
                    'value' => $item->getPrice(),
                    'currency' => $order_info['payment_method']['processor_params']['currency'],
                ],
                'vat_code' => isset($this->map_taxes[$item->getTaxType()]) ? $this->map_taxes[$item->getTaxType()] : $this->map_taxes[TaxType::NONE],
                'payment_mode' => $type,
            ];
        }
        $customer = [
            'email' => $receipt->getEmail(),
            'phone' => $receipt->getPhone(),
        ];
        return ['customer' => $customer, 'items' => $products];
    }

    /**
     * @param $order_info
     *
     * @return array
     */
    public function getPaymentReceiptFromOrder($order_info)
    {
        $prepayment_receipt = $this->getReceiptFromOrder($order_info, 'full_payment');

        $receipt = [
            'type' => 'payment',
            'payment_id' => $order_info['payment_info']['id'],
            'customer' => $prepayment_receipt['customer'],
            'items' => $prepayment_receipt['items'],
            'send' => true,
            'settlements' => [
                [
                    'type' => 'prepayment',
                    'amount' => [
                        'value' => $order_info['total'],
                        'currency' => $order_info['payment_method']['processor_params']['currency'],
                    ],
                ],
            ],
        ];
        return $receipt;
    }
}