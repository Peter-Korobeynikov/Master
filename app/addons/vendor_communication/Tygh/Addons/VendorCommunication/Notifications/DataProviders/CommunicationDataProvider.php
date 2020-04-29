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


namespace Tygh\Addons\VendorCommunication\Notifications\DataProviders;

use Tygh\Enum\UserTypes;
use Tygh\Enum\YesNo;
use Tygh\Notifications\DataProviders\BaseDataProvider;

/**
 * Class CommunicationDataProvider provides a data for message transports that required for sending messages
 * about events added in Vendor Communication addon.
 *
 * @package Tygh\Addons\VendorCommunication\Notifications\DataProviders
 */
class CommunicationDataProvider extends BaseDataProvider
{
    protected $lang_code = null;
    protected $company_id = 0;

    public function __construct(array $data)
    {
        $this->company_id = isset($data['company_id']) ? $data['company_id'] : 0;

        $data['lang_code'] = $this->getLangCode();
        $data['to'] = $this->getTo($data);
        $data = $this->getActionUrls($data);
        $data['message_author'] = $this->getMessageAuthor($data);
        $data['company_name'] = fn_get_company_name($this->company_id);
        if (fn_allowed_for('MULTIVENDOR')) {
            $data['admin_user_id'] = fn_get_company_admin_user_id($this->company_id);
        }
        parent::__construct($data);
    }

    protected function getActionUrls(array $data)
    {
        $data['action_url'] = fn_url("vendor_communication.view?thread_id={$data['thread_id']}", 'A');
        if (fn_allowed_for('MULTIVENDOR')) {
            $data['vendor_action_url'] = fn_url("vendor_communication.view?thread_id={$data['thread_id']}", 'V');
        }
        return $data;
    }

    protected function getLangCode()
    {
        if (isset($this->lang_code)) {
            return $this->lang_code;
        }

        return $this->lang_code = fn_get_company_language($this->company_id);
    }

    protected function getTo(array $data)
    {
        return [
            'vendor' => $this->getVendorReceiver($data),
            'customer' => $this->getCustomerReceiver($data),
        ];
    }

    protected function getMessageAuthor(array $data)
    {
        if (!empty($data['last_message_user_id'])) {
            $message_from = fn_vendor_communication_get_user_name($data['last_message_user_id']);
        }
        return !empty($message_from) ? $message_from : __('customer');
    }

    protected function getVendorReceiver(array $data)
    {
        $to = db_get_field('SELECT email FROM ?:companies WHERE company_id = ?i', $data['company_id']);
        return $to;
    }

    protected function getCustomerReceiver(array $data)
    {
        $to = fn_get_user_short_info($data['user_id']);
        return $to['email'];
    }
}