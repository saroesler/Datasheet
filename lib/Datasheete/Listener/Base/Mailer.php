<?php
/**
 * Datasheete.
 *
 * @copyright Sascha Rösler (SR)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @package Datasheete
 * @author Sascha Rösler <i-loko@t-online.de>.
 * @link http://github.com/sarom5
 * @link http://zikula.org
 * @version Generated by ModuleStudio 0.6.0 (http://modulestudio.de) at Thu Mar 21 11:00:37 CET 2013.
 */

/**
 * Event handler base class for mailing events.
 */
class Datasheete_Listener_Base_Mailer
{
    /**
     * Listener for the `module.mailer.api.sendmessage` event.
     *
     * Invoked from `Mailer_Api_User#sendmessage`.
     * Subject is `Mailer_Api_User` with `$args`.
     * This is a notifyUntil event so the event must `$event->stop()` and set any
     * return data into `$event->data`, or `$event->setData()`.
     *
     * @param Zikula_Event $event The event instance.
     */
    public static function sendMessage(Zikula_Event $event)
    {
    }
}
