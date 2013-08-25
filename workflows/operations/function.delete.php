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
 * Delete operation.
 * @param object $entity The treated object.
 * @param array  $params Additional arguments.
 *
 * @return bool False on failure or true if everything worked well.
 */
function Datasheete_operation_delete(&$entity, $params)
{
    $dom = ZLanguage::getModuleDomain('Datasheete');


    // initialise the result flag
    $result = false;

    // get entity manager
    $serviceManager = ServiceUtil::getManager();
    $entityManager = $serviceManager->getService('doctrine.entitymanager');
    
    // delete entity
    try {
        $entityManager->remove($entity);
        $entityManager->flush();
        $result = true;
    } catch (Exception $e) {
        LogUtil::registerError($e->getMessage());
    }

    // return result of this operation
    return $result;
}
