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

namespace Tygh\Notifications\Transports\Internal;

use Tygh\Database\Connection;
use Tygh\Enum\RecipientSearchMethods;
use Tygh\Enum\UserTypes;
use Tygh\Exceptions\DeveloperException;
use Tygh\Notifications\Transports\BaseMessageSchema;
use Tygh\Notifications\Transports\ITransport;
use Tygh\NotificationsCenter\IFactory;

/**
 * Class InternalTransport implements a transport that creates notifications in the Notifications center
 * based on an event message.
 *
 * @package Tygh\Events\Transports
 */
class InternalTransport implements ITransport
{
    /**
     * @var \Tygh\NotificationsCenter\NotificationsCenter
     */
    protected $notifications_center;

    /**
     * @var \Tygh\Database\Connection
     */
    protected $db;

    /**
     * @var \Tygh\NotificationsCenter\IFactory
     */
    protected $factory;

    public function __construct(
        $notifications_center,
        Connection $db,
        IFactory $factory
    ) {
        $this->notifications_center = $notifications_center;
        $this->db = $db;
        $this->factory = $factory;
    }

    public static function getId()
    {
        return 'internal';
    }

    /**
     * @param \Tygh\Notifications\Transports\BaseMessageSchema $schema
     *
     * @return bool
     */
    public function process(BaseMessageSchema $schema)
    {
        if (!$schema instanceof InternalMessageSchema) {
            throw new DeveloperException('Input data should be instance of InternalMessageSchema');
        }

        $recipients = $this->getRecipients($schema->recipient_search_method, $schema->recipient_search_criteria);

        foreach ($recipients as $user_id => $area) {
            $notificaion_data = array_filter([
                'user_id'       => $user_id,
                'title'         => $schema->title,
                'message'       => $schema->message,
                'severity'      => $schema->severity,
                'section'       => $schema->section,
                'tag'           => $schema->tag,
                'area'          => $area,
                'action_url'    => $schema->action_url,
                'is_read'       => $schema->is_read,
                'timestamp'     => $schema->timestamp,
                'language_code' => $schema->language_code,
                'template_code' => $schema->template_code,
                'data'          => $schema->data,
            ]);

            $this->notifications_center->add($notificaion_data);
        }

        return true;
    }

    /**
     * Gets message recipients.
     *
     * @param string                    $method   Recipients search method
     * @param int|string|int[]|string[] $criteria Recipients search criteria
     *
     * @see \Tygh\Enum\RecipientSearchMethods Possible $type values
     *
     * @return array
     */
    protected function getRecipients($method, $criteria)
    {
        $conditions = [
            'users.status' => 'A',
        ];

        switch ($method) {
            case RecipientSearchMethods::USER_ID:
                $conditions['users.user_id'] = $criteria;
                break;
            case RecipientSearchMethods::EMAIL:
                $conditions['users.email'] = $criteria;
                break;
            case RecipientSearchMethods::USERGROUP_ID:
                $conditions['usergroups.usergroup_id'] = $criteria;
                $conditions['usergroups.status'] = 'A';
                break;
            default:
                return [];
        }

        $users = $this->db->getSingleHash(
            'SELECT users.user_id AS user_id, (CASE WHEN users.user_type = ?s THEN ?s ELSE ?s END) AS area'
            . ' FROM ?:users AS users'
            . ' LEFT JOIN ?:usergroup_links AS usergroups ON usergroups.user_id = users.user_id'
            . ' WHERE ?w'
            . ' GROUP BY users.user_id',
            ['user_id', 'area'],
            UserTypes::CUSTOMER,
            UserTypes::CUSTOMER,
            UserTypes::ADMIN,
            $conditions
        );

        return $users;
    }
}
