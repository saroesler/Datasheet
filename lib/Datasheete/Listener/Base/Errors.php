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
 * Event handler base class for error-related events.
 */
class Datasheete_Listener_Base_Errors
{
    /**
     * Listener for the `setup.errorreporting` event.
     *
     * Invoked during `System::init()`.
     * Used to activate `set_error_handler()`.
     * Event must `stop()`.
     *
     * @param Zikula_Event $event The event instance.
     */
    public static function setupErrorReporting(Zikula_Event $event)
    {
    }
    
    /**
     * Listener for the `systemerror` event.
     *
     * Invoked on any system error.
     * args gets `array('errorno' => $errno, 'errstr' => $errstr, 'errfile' => $errfile, 'errline' => $errline, 'errcontext' => $errcontext)`.
     *
     * @param Zikula_Event $event The event instance.
     */
    public static function systemError(Zikula_Event $event)
    {
    }
}
