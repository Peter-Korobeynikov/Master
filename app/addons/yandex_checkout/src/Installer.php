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

namespace Tygh\Addons\YandexCheckout;

use Tygh\Addons\InstallerInterface;
use Tygh\Core\ApplicationInterface;
use Tygh\Enum\YesNo;

class Installer implements InstallerInterface
{
    /**
     * @var \Tygh\Core\ApplicationInterface
     */
    protected $app;

    public function __construct(ApplicationInterface $app)
    {
        $this->app = $app;
    }

    public static function factory(ApplicationInterface $app)
    {
        return new self($app);
    }

    public function onBeforeInstall()
    {

    }

    public function onInstall()
    {
        $processor = [
            'processor' => 'Яндекс.Касса',
            'processor_script' => 'yandex_checkout.php',
            'admin_template' => 'yandex_checkout.tpl',
            'callback'  => YesNo::YES,
            'type' => 'P',
            'position' => 10,
            'addon' => 'yandex_checkout',
        ];

        db_replace_into('payment_processors', $processor);
    }

    public function onUninstall()
    {
        $payment_ids = db_get_fields('SELECT payment_id FROM ?:payments LEFT JOIN ?:payment_processors ON ?:payments.processor_id = ?:payment_processors.processor_id WHERE ?:payment_processors.admin_template = ?s', 'yandex_checkout.tpl');

        foreach ($payment_ids as $payment_id) {
            fn_delete_payment($payment_id);
        }

        db_query('DELETE FROM ?:payment_processors WHERE admin_template = ?s', 'yandex_checkout.tpl');
    }


}