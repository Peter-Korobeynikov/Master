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

namespace Tygh\Addons\YandexCheckout\Payments;

use Tygh\Enum\YesNo;
use YandexCheckout\Client;
use YandexCheckout\Common\Exceptions\BadApiRequestException;
use Exception;

/**
 * Class YandexCheckout
 *
 * @package Tygh\Addons\YandexCheckout
 */
class YandexCheckout
{
    /** @var string  */
    protected $shop_id;

    /** @var string  */
    protected $secret_key;

    /** @var YandexCheckout\Client */
    protected $client;

    /** @var \Tygh\Addons\YandexCheckout\Services\ReceiptService */
    protected $receipt_service;

    /**
     * YandexCheckout constructor.
     *
     * @param $shop_id
     * @param $secret_key
     * @param $service
     */
    public function __construct($shop_id, $secret_key, $service)
    {
        $this->client = new Client();
        $this->shop_id = $shop_id;
        $this->secret_key = $secret_key;
        $this->receipt_service = $service;
        $this->authorize();
    }

    /**
     *
     */
    protected function authorize()
    {
        $this->client->setAuth($this->shop_id, $this->secret_key);
    }

    /**
     * @param array $order_info
     * @param array $processor_data
     *
     * @return \YandexCheckout\Request\Payments\CreatePaymentResponse
     *
     * @throws \YandexCheckout\Common\Exceptions\ApiException
     * @throws \YandexCheckout\Common\Exceptions\BadApiRequestException
     * @throws \YandexCheckout\Common\Exceptions\ForbiddenException
     * @throws \YandexCheckout\Common\Exceptions\InternalServerError
     * @throws \YandexCheckout\Common\Exceptions\NotFoundException
     * @throws \YandexCheckout\Common\Exceptions\ResponseProcessingException
     * @throws \YandexCheckout\Common\Exceptions\TooManyRequestsException
     * @throws \YandexCheckout\Common\Exceptions\UnauthorizedException
     */
    public function createPayment(array $order_info, array $processor_data)
    {
        $params = [
            'amount' => [
                'value' => $order_info['total'],
                'currency' => $processor_data['processor_params']['currency'],
            ],
            'confirmation' => [
                'type' => 'redirect',
                'return_url' => fn_url('yandex_checkout.return_to_store&order_id=' . $order_info['order_id']),
            ],
            'capture' => true,
            'metadata' => [
                'order_id' => $order_info['order_id'],
            ],
        ];

        if (YesNo::toBool($order_info['payment_method']['processor_params']['send_receipt'])) {
            $receipt = $this->receipt_service->getReceiptFromOrder($order_info, 'full_prepayment');
            $params['receipt'] = $receipt;
        }

        $payment = $this->client->createPayment($params);
        return $payment;
    }

    /**
     * @param $payment_id
     *
     * @return \YandexCheckout\Model\PaymentInterface
     *
     * @throws \YandexCheckout\Common\Exceptions\ApiException
     * @throws \YandexCheckout\Common\Exceptions\BadApiRequestException
     * @throws \YandexCheckout\Common\Exceptions\ExtensionNotFoundException
     * @throws \YandexCheckout\Common\Exceptions\ForbiddenException
     * @throws \YandexCheckout\Common\Exceptions\InternalServerError
     * @throws \YandexCheckout\Common\Exceptions\NotFoundException
     * @throws \YandexCheckout\Common\Exceptions\ResponseProcessingException
     * @throws \YandexCheckout\Common\Exceptions\TooManyRequestsException
     * @throws \YandexCheckout\Common\Exceptions\UnauthorizedException
     */
    public function getPaymentInfo($payment_id)
    {
        return $this->client->getPaymentInfo($payment_id);
    }

    /**
     * @param array $order_info
     *
     * @return bool
     * @throws \YandexCheckout\Common\Exceptions\ApiConnectionException
     * @throws \YandexCheckout\Common\Exceptions\ApiException
     * @throws \YandexCheckout\Common\Exceptions\AuthorizeException
     * @throws \YandexCheckout\Common\Exceptions\BadApiRequestException
     * @throws \YandexCheckout\Common\Exceptions\ForbiddenException
     * @throws \YandexCheckout\Common\Exceptions\InternalServerError
     * @throws \YandexCheckout\Common\Exceptions\NotFoundException
     * @throws \YandexCheckout\Common\Exceptions\ResponseProcessingException
     * @throws \YandexCheckout\Common\Exceptions\TooManyRequestsException
     * @throws \YandexCheckout\Common\Exceptions\UnauthorizedException
     */
    public function createReceipt(array $order_info)
    {
        $receipt = $this->receipt_service->getPaymentReceiptFromOrder($order_info);

        $this->client->createReceipt($receipt);
        return true;
    }
}