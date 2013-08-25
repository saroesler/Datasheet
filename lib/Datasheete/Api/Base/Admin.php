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
 * This is the Admin api helper class.
 */
class Datasheete_Api_Base_Admin extends Zikula_AbstractApi
{
    /**
     * Returns available admin panel links.
     *
     * @return array Array of admin links.
     */
    public function getlinks()
    {
        $links = array();

        if (SecurityUtil::checkPermission($this->name . ':Datasheet:', '::', ACCESS_READ)) {
            $links[] = array('url' => ModUtil::url($this->name, 'admin', 'view', array('ot' => 'datasheet')),
                             'text' => $this->__('Datasheets'),
                             'title' => $this->__('Datasheet list'));
        }

        return $links;
    }
}