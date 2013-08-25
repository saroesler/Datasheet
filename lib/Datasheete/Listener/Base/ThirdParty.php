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
 * Event handler implementation class for special purposes and 3rd party api support.
 */
class Datasheete_Listener_Base_ThirdParty
{
    /**
     * Listener for pending content items.
     *
     * @param Zikula_Event $event The event instance.
     */
    public static function pendingContentListener(Zikula_Event $event)
    {
        // nothing required here as no entities use enhanced workflows including approval actions
    }
    
    /**
     * Listener for the `module.content.gettypes` event.
     *
     * This event occurs when the Content module is 'searching' for Content plugins.
     * The subject is an instance of Content_Types.
     * You can register custom content types as well as custom layout types.
     *
     * @param Zikula_Event $event The event instance.
     */
    public static function contentGetTypes(Zikula_Event $event)
    {
        // intended is using the add() method to add a plugin like below
        $types = $event->getSubject();
        
        // plugin for showing a single item
        $types->add('Datasheete_ContentType_Item');
        
        // plugin for showing a list of multiple items
        $types->add('Datasheete_ContentType_ItemList');
    }
}
