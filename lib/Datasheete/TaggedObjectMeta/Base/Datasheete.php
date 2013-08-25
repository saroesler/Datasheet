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
 * @version Generated by ModuleStudio 0.6.0 (http://modulestudio.de) at Thu Mar 21 11:00:38 CET 2013.
 */

/**
 * This class provides object meta data for the Tag module.
 */
class Datasheete_TaggedObjectMeta_Base_Datasheete extends Tag_AbstractTaggedObjectMeta
{
    /**
     * Constructor.
     *
     * @param integer             $objectId  Identifier of treated object.
     * @param integer             $areaId    Name of hook area.
     * @param string              $module    Name of the owning module.
     * @param string              $urlString **deprecated**
     * @param Zikula_ModUrl $urlObject Object carrying url arguments.
     */
    function __construct($objectId, $areaId, $module, $urlString = null, Zikula_ModUrl $urlObject = null)
    {
        // call base constructor to store arguments in member vars
        parent::__construct($objectId, $areaId, $module, $urlString, $urlObject);
    
        // derive object type from url object
        $urlArgs = $urlObject->getArgs();
        $objectType = isset($urlArgs['ot']) ? $urlArgs['ot'] : 'datasheet';
    
        $component = $module . ':' . ucwords($objectType) . ':';
        $perm = SecurityUtil::checkPermission($component, $objectId . '::', ACCESS_READ);
        if (!$perm) {
            return;
        }
    
        $entityClass = $module . '_Entity_' . ucwords($objectType);
        $serviceManager = ServiceUtil::getManager();
        $entityManager = $serviceManager->getService('doctrine.entitymanager');
        $repository = $entityManager->getRepository($entityClass);
        $useJoins = false;
    
        /** TODO support composite identifiers properly at this point */
        $entity = $repository->selectById($objectId, $useJoins);
        if ($entity === false || (!is_array($entity) && !is_object($entity))) {
            return;
        }
    
        $this->setObjectTitle($entity[$repository->getTitleFieldName()]);
    
        $dateFieldName = $repository->getStartDateFieldName();
        if ($dateFieldName != '') {
            $this->setObjectDate($entity[$dateFieldName]);
        } else {
            $this->setObjectDate('');
        }
    
        if (method_exists($entity, 'getCreatedUserId')) {
            $this->setObjectAuthor(UserUtil::getVar('uname', $entity['createdUserId']));
        } else {
            $this->setObjectAuthor('');
        }
    }
    
    /**
     * Sets the object title.
     *
     * @param string $title
     */
    public function setObjectTitle($title)
    {
        $this->title = $title;
    }
    
    /**
     * Sets the object date.
     *
     * @param string $date
     */
    public function setObjectDate($date)
    {
    
        $this->date = DateUtil::formatDatetime($date, 'datetimebrief');
    }
    
    /**
     * Sets the object author.
     *
     * @param string $author
     */
    public function setObjectAuthor($author)
    {
        $this->author = $author;
    }
}
