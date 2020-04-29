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

namespace Tygh\Notifications\Transports\Mail;

use Tygh\Exceptions\DeveloperException;
use Tygh\Mailer\Mailer;
use Tygh\Notifications\Transports\BaseMessageSchema;
use Tygh\Notifications\Transports\ITransport;

/**
 * Class MailTransport implements a transport that send emails based on an event message.
 *
 * @package Tygh\Events\Transports
 */
class MailTransport implements ITransport
{
    /**
     * @var \Tygh\Mailer\Mailer
     */
    protected $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public static function getId()
    {
        return 'mail';
    }

    /**
     * @inheritDoc
     */
    public function process(BaseMessageSchema $schema)
    {
        if (!$schema instanceof MailMessageSchema) {
            throw new DeveloperException('Input data should be instance of MailMessageSchema');
        }

        return $this->mailer->send([
            'to'            => $schema->to,
            'from'          => $schema->from,
            'reply_to'      => $schema->reply_to,
            'data'          => $schema->data,
            'template_code' => $schema->template_code,
            'tpl'           => $schema->legacy_template,
            'company_id'    => $schema->company_id,
            'attachments'   => $schema->attachments
        ], $schema->area, $schema->language_code);
    }
}
