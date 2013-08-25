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
 * @version Generated by ModuleStudio 0.6.0 (http://modulestudio.de) at Thu Mar 21 11:00:36 CET 2013.
 */

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use DoctrineExtensions\StandardFields\Mapping\Annotation as ZK;

/**
 * Entity class that defines the entity structure and behaviours.
 *
 * This is the base entity class for datasheet entities.
 *
 * @abstract
 */
abstract class Datasheete_Entity_Base_Datasheet extends Zikula_EntityAccess
{
    /**
     * @var string The tablename this object maps to.
     */
    protected $_objectType = 'datasheet';
    
    /**
     * @var array List of primary key field names.
     */
    protected $_idFields = array();
    /**
     * @var Datasheete_Entity_Validator_Datasheet The validator for this entity.
     */
    protected $_validator = null;
    
    /**
     * @var boolean Whether this entity supports unique slugs.
     */
    protected $_hasUniqueSlug = false;
    
    /**
     * @var array List of available item actions.
     */
    protected $_actions = array();
    
    /**
     * @var array The current workflow data of this object.
     */
    protected $__WORKFLOW__ = array();
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", unique=true)
     * @var integer $id.
     */
    protected $id = 0;
    
    /**
     * @ORM\Column(length=20)
     * @var string $workflowState.
     */
    protected $workflowState = 'initial';
    
    /**
     * @ORM\Column(length=255)
     * @var string $name.
     */
    protected $name = '';
    
    /**
     * @ORM\Column(length=255)
     * @var string $kind.
     */
    protected $kind = '';
    
    /**
     * Datasheet meta data array.
     *
     * @ORM\Column(type="array")
     * @var array $datasheetMeta.
     */
    protected $datasheetMeta = array();
    
    /**
     * @ORM\Column(length=255)
     * @var string $datasheet.
     */
    protected $datasheet = '';
    
    /**
     * The full path to the datasheet.
     *
     * @var string $datasheetFullPath.
     */
    protected $datasheetFullPath = '';
    
    /**
     * Full datasheet path as url.
     *
     * @var string $datasheetFullPathUrl.
     */
    protected $datasheetFullPathUrl = '';
    /**
     * @ORM\Column(type="text", length=2000)
     * @var text $description.
     */
    protected $description = '';
    
    
    /**
     * @ORM\Column(type="integer")
     * @ZK\StandardFields(type="userid", on="create")
     * @var integer $createdUserId.
     */
    protected $createdUserId;
    
    /**
     * @ORM\Column(type="integer")
     * @ZK\StandardFields(type="userid", on="update")
     * @var integer $updatedUserId.
     */
    protected $updatedUserId;
    
    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     * @var datetime $createdDate.
     */
    protected $createdDate;
    
    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     * @var datetime $updatedDate.
     */
    protected $updatedDate;
    
    
    /**
     * Constructor.
     * Will not be called by Doctrine and can therefore be used
     * for own implementation purposes. It is also possible to add
     * arbitrary arguments as with every other class method.
     *
     * @param TODO
     */
    public function __construct()
    {
        $this->workflowState = 'initial';
        $this->_idFields = array('id');
        $this->initValidator();
        $this->initWorkflow();
        $this->_hasUniqueSlug = false;
    }
    
    /**
     * Get _object type.
     *
     * @return string
     */
    public function get_objectType()
    {
        return $this->_objectType;
    }
    
    /**
     * Set _object type.
     *
     * @param string $_objectType.
     *
     * @return void
     */
    public function set_objectType($_objectType)
    {
        $this->_objectType = $_objectType;
    }
    
    /**
     * Get _id fields.
     *
     * @return array
     */
    public function get_idFields()
    {
        return $this->_idFields;
    }
    
    /**
     * Set _id fields.
     *
     * @param array $_idFields.
     *
     * @return void
     */
    public function set_idFields(array $_idFields = Array())
    {
        $this->_idFields = $_idFields;
    }
    
    /**
     * Get _validator.
     *
     * @return Datasheete_Entity_Validator_Datasheet
     */
    public function get_validator()
    {
        return $this->_validator;
    }
    
    /**
     * Set _validator.
     *
     * @param Datasheete_Entity_Validator_Datasheet $_validator.
     *
     * @return void
     */
    public function set_validator(Datasheete_Entity_Validator_Datasheet $_validator = null)
    {
        $this->_validator = $_validator;
    }
    
    /**
     * Get _has unique slug.
     *
     * @return boolean
     */
    public function get_hasUniqueSlug()
    {
        return $this->_hasUniqueSlug;
    }
    
    /**
     * Set _has unique slug.
     *
     * @param boolean $_hasUniqueSlug.
     *
     * @return void
     */
    public function set_hasUniqueSlug($_hasUniqueSlug)
    {
        $this->_hasUniqueSlug = $_hasUniqueSlug;
    }
    
    /**
     * Get _actions.
     *
     * @return array
     */
    public function get_actions()
    {
        return $this->_actions;
    }
    
    /**
     * Set _actions.
     *
     * @param array $_actions.
     *
     * @return void
     */
    public function set_actions(array $_actions = Array())
    {
        $this->_actions = $_actions;
    }
    
    /**
     * Get __ w o r k f l o w__.
     *
     * @return array
     */
    public function get__WORKFLOW__()
    {
        return $this->__WORKFLOW__;
    }
    
    /**
     * Set __ w o r k f l o w__.
     *
     * @param array $__WORKFLOW__.
     *
     * @return void
     */
    public function set__WORKFLOW__(array $__WORKFLOW__ = Array())
    {
        $this->__WORKFLOW__ = $__WORKFLOW__;
    }
    
    
    /**
     * Get id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Set id.
     *
     * @param integer $id.
     *
     * @return void
     */
    public function setId($id)
    {
        if ($id != $this->id) {
            $this->id = $id;
        }
    }
    
    /**
     * Get workflow state.
     *
     * @return string
     */
    public function getWorkflowState()
    {
        return $this->workflowState;
    }
    
    /**
     * Set workflow state.
     *
     * @param string $workflowState.
     *
     * @return void
     */
    public function setWorkflowState($workflowState)
    {
        if ($workflowState != $this->workflowState) {
            $this->workflowState = $workflowState;
        }
    }
    
    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Set name.
     *
     * @param string $name.
     *
     * @return void
     */
    public function setName($name)
    {
        if ($name != $this->name) {
            $this->name = $name;
        }
    }
    
    /**
     * Get kind.
     *
     * @return string
     */
    public function getKind()
    {
        return $this->kind;
    }
    
    /**
     * Set kind.
     *
     * @param string $kind.
     *
     * @return void
     */
    public function setKind($kind)
    {
        if ($kind != $this->kind) {
            $this->kind = $kind;
        }
    }
    
    /**
     * Get datasheet.
     *
     * @return string
     */
    public function getDatasheet()
    {
        return $this->datasheet;
    }
    
    /**
     * Set datasheet.
     *
     * @param string $datasheet.
     *
     * @return void
     */
    public function setDatasheet($datasheet)
    {
        if ($datasheet != $this->datasheet) {
            $this->datasheet = $datasheet;
        }
    }
    
    /**
     * Get datasheet full path.
     *
     * @return string
     */
    public function getDatasheetFullPath()
    {
        return $this->datasheetFullPath;
    }
    
    /**
     * Set datasheet full path.
     *
     * @param string $datasheetFullPath.
     *
     * @return void
     */
    public function setDatasheetFullPath($datasheetFullPath)
    {
        if ($datasheetFullPath != $this->datasheetFullPath) {
            $this->datasheetFullPath = $datasheetFullPath;
        }
    }
    
    /**
     * Get datasheet full path url.
     *
     * @return string
     */
    public function getDatasheetFullPathUrl()
    {
        return $this->datasheetFullPathUrl;
    }
    
    /**
     * Set datasheet full path url.
     *
     * @param string $datasheetFullPathUrl.
     *
     * @return void
     */
    public function setDatasheetFullPathUrl($datasheetFullPathUrl)
    {
        if ($datasheetFullPathUrl != $this->datasheetFullPathUrl) {
            $this->datasheetFullPathUrl = $datasheetFullPathUrl;
        }
    }
    
    /**
     * Get datasheet meta.
     *
     * @return array
     */
    public function getDatasheetMeta()
    {
        return $this->datasheetMeta;
    }
    
    /**
     * Set datasheet meta.
     *
     * @param array $datasheetMeta.
     *
     * @return void
     */
    public function setDatasheetMeta($datasheetMeta = Array())
    {
        if ($datasheetMeta != $this->datasheetMeta) {
            $this->datasheetMeta = $datasheetMeta;
        }
    }
    
    /**
     * Get description.
     *
     * @return text
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * Set description.
     *
     * @param text $description.
     *
     * @return void
     */
    public function setDescription($description)
    {
        if ($description != $this->description) {
            $this->description = $description;
        }
    }
    
    /**
     * Get created user id.
     *
     * @return integer[]
     */
    public function getCreatedUserId()
    {
        return $this->createdUserId;
    }
    
    /**
     * Set created user id.
     *
     * @param integer[] $createdUserId.
     *
     * @return void
     */
    public function setCreatedUserId($createdUserId)
    {
        $this->createdUserId = $createdUserId;
    }
    
    /**
     * Get updated user id.
     *
     * @return integer[]
     */
    public function getUpdatedUserId()
    {
        return $this->updatedUserId;
    }
    
    /**
     * Set updated user id.
     *
     * @param integer[] $updatedUserId.
     *
     * @return void
     */
    public function setUpdatedUserId($updatedUserId)
    {
        $this->updatedUserId = $updatedUserId;
    }
    
    /**
     * Get created date.
     *
     * @return datetime[]
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }
    
    /**
     * Set created date.
     *
     * @param datetime[] $createdDate.
     *
     * @return void
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;
    }
    
    /**
     * Get updated date.
     *
     * @return datetime[]
     */
    public function getUpdatedDate()
    {
        return $this->updatedDate;
    }
    
    /**
     * Set updated date.
     *
     * @param datetime[] $updatedDate.
     *
     * @return void
     */
    public function setUpdatedDate($updatedDate)
    {
        $this->updatedDate = $updatedDate;
    }
    
    
    
    /**
     * Initialise validator and return it's instance.
     *
     * @return Datasheete_Entity_Validator_Datasheet The validator for this entity.
     */
    public function initValidator()
    {
        if (!is_null($this->_validator)) {
            return $this->_validator;
        }
        $this->_validator = new Datasheete_Entity_Validator_Datasheet($this);
    
        return $this->_validator;
    }
    
    /**
     * Sets/retrieves the workflow details.
     */
    public function initWorkflow()
    {
        $currentFunc = FormUtil::getPassedValue('func', 'main', 'GETPOST', FILTER_SANITIZE_STRING);
    
        // apply workflow with most important information
        $idColumn = 'id';
        $workflowHelper = new Datasheete_Util_Workflow(ServiceUtil::getManager());
        $schemaName = $workflowHelper->getWorkflowName($this['_objectType']);
        $this['__WORKFLOW__'] = array(
            'state' => $this['workflowState'],
            'obj_table' => $this['_objectType'],
            'obj_idcolumn' => $idColumn,
            'obj_id' => $this[$idColumn],
            'schemaname' => $schemaName);
        
        // load the real workflow only when required (e. g. when func is edit or delete)
        if (!in_array($currentFunc, array('main', 'view', 'display'))) {
            $result = Zikula_Workflow_Util::getWorkflowForObject($this, $this['_objectType'], $idColumn, 'Datasheete');
            if (!$result) {
                $dom = ZLanguage::getModuleDomain('Datasheete');
                LogUtil::registerError(__('Error! Could not load the associated workflow.', $dom));
            }
        }
        
        if (!is_object($this['__WORKFLOW__']) && !isset($this['__WORKFLOW__']['schemaname'])) {
            $workflow = $this['__WORKFLOW__'];
            $workflow['schemaname'] = $schemaName;
            $this['__WORKFLOW__'] = $workflow;
        }
    }
    
    /**
     * Resets workflow data back to initial state.
     * To be used after cloning an entity object.
     */
    public function resetWorkflow()
    {
        $this->setWorkflowState('initial');
        $workflowHelper = new Datasheete_Util_Workflow(ServiceUtil::getManager());
        $schemaName = $workflowHelper->getWorkflowName($this['_objectType']);
        $this['__WORKFLOW__'] = array(
            'state' => $this['workflowState'],
            'obj_table' => $this['_objectType'],
            'obj_idcolumn' => 'id',
            'obj_id' => 0,
            'schemaname' => $schemaName);
    }
    
    /**
     * Start validation and raise exception if invalid data is found.
     *
     * @return void.
     * @throws Zikula_Exception
     */
    public function validate()
    {
        $result = $this->initValidator()->validateAll();
        if (is_array($result)) {
            throw new Zikula_Exception($result['message'], $result['code'], $result['debugArray']);
        }
    }
    
    /**
     * Return entity data in JSON format.
     *
     * @return string JSON-encoded data.
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }
    
    /**
     * Collect available actions for this entity.
     */
    protected function prepareItemActions()
    {
        if (!empty($this->_actions)) {
            return;
        }
    
        $currentType = FormUtil::getPassedValue('type', 'user', 'GETPOST', FILTER_SANITIZE_STRING);
        $currentFunc = FormUtil::getPassedValue('func', 'main', 'GETPOST', FILTER_SANITIZE_STRING);
        $dom = ZLanguage::getModuleDomain('Datasheete');
        if ($currentType == 'admin') {
            if (in_array($currentFunc, array('main', 'view'))) {
                $this->_actions[] = array(
                    'url' => array('type' => 'admin', 'func' => 'display', 'arguments' => array('ot' => 'datasheet', 'id' => $this['id'])),
                    'icon' => 'display',
                    'linkTitle' => str_replace('"', '', $this['name']),
                    'linkText' => __('Details', $dom)
                );
            }
            if (in_array($currentFunc, array('main', 'view', 'display'))) {
                $component = 'Datasheete:Datasheet:';
                $instance = $this->id . '::';
                if (SecurityUtil::checkPermission($component, $instance, ACCESS_EDIT)) {
                    $this->_actions[] = array(
                        'url' => array('type' => 'admin', 'func' => 'edit', 'arguments' => array('ot' => 'datasheet', 'id' => $this['id'])),
                        'icon' => 'edit',
                        'linkTitle' => __('Edit', $dom),
                        'linkText' => __('Edit', $dom)
                    );
                    $this->_actions[] = array(
                        'url' => array('type' => 'admin', 'func' => 'edit', 'arguments' => array('ot' => 'datasheet', 'astemplate' => $this['id'])),
                        'icon' => 'saveas',
                        'linkTitle' => __('Reuse for new item', $dom),
                        'linkText' => __('Reuse', $dom)
                    );
                }
                if (SecurityUtil::checkPermission($component, $instance, ACCESS_DELETE)) {
                    $this->_actions[] = array(
                        'url' => array('type' => 'admin', 'func' => 'delete', 'arguments' => array('ot' => 'datasheet', 'id' => $this['id'])),
                        'icon' => 'delete',
                        'linkTitle' => __('Delete', $dom),
                        'linkText' => __('Delete', $dom)
                    );
                }
            }
            if ($currentFunc == 'display') {
                $this->_actions[] = array(
                    'url' => array('type' => 'admin', 'func' => 'view', 'arguments' => array('ot' => 'datasheet')),
                    'icon' => 'back',
                    'linkTitle' => __('Back to overview', $dom),
                    'linkText' => __('Back to overview', $dom)
                );
            }
        }
    }
    
    /**
     * Creates url arguments array for easy creation of display urls.
     *
     * @return Array The resulting arguments list. 
     */
    public function createUrlArgs()
    {
        $args = array('ot' => $this['_objectType']);
    
        $args['id'] = $this['id'];
    
        if (isset($this['slug'])) {
            $args['slug'] = $this['slug'];
        }
    
        return $args;
    }
    
    /**
     * Create concatenated identifier string (for composite keys).
     *
     * @return String concatenated identifiers.
     */
    public function createCompositeIdentifier()
    {
        $itemId = $this['id'];
    
        return $itemId;
    }
    
    /**
     * Return lower case name of multiple items needed for hook areas.
     *
     * @return string
     */
    public function getHookAreaPrefix()
    {
        return 'datasheete.ui_hooks.datasheets';
    }

    
    /**
     * Post-Process the data after the entity has been constructed by the entity manager.
     * The event happens after the entity has been loaded from database or after a refresh call.
     *
     * Restrictions:
     *     - no access to entity manager or unit of work apis
     *     - no access to associations (not initialised yet)
     *
     * @see Datasheete_Entity_Datasheet::postLoadCallback()
     * @return boolean true if completed successfully else false.
     */
    protected function performPostLoadCallback()
    {
        // echo 'loaded a record ...';
        $currentFunc = FormUtil::getPassedValue('func', 'main', 'GETPOST', FILTER_SANITIZE_STRING);
        
        // initialise the upload handler
        $uploadManager = new Datasheete_UploadHandler();
        $serviceManager = ServiceUtil::getManager();
        $controllerHelper = new Datasheete_Util_Controller($serviceManager);
        
        $this['id'] = (int) ((isset($this['id']) && !empty($this['id'])) ? DataUtil::formatForDisplay($this['id']) : 0);
        if ($currentFunc != 'edit') {
            $this['workflowState'] = (((isset($this['workflowState']) && !empty($this['workflowState'])) || $this['workflowState'] == 0) ? DataUtil::formatForDisplayHTML($this['workflowState']) : '');
        }
        if ($currentFunc != 'edit') {
            $this['name'] = ((isset($this['name']) && !empty($this['name'])) ? DataUtil::formatForDisplayHTML($this['name']) : '');
        }
        if ($currentFunc != 'edit') {
            $this['kind'] = (((isset($this['kind']) && !empty($this['kind'])) || $this['kind'] == 0) ? DataUtil::formatForDisplayHTML($this['kind']) : '');
        }
        if (!empty($this['datasheet'])) {
            try {
                $basePath = $controllerHelper->getFileBaseFolder('datasheet', 'datasheet');
            } catch (Exception $e) {
                return LogUtil::registerError($e->getMessage());
            }
            $fullPath = $basePath .  $this['datasheet'];
            $this['datasheetFullPath'] = $fullPath;
            $this['datasheetFullPathURL'] = System::getBaseUrl() . $fullPath;
        
            // just some backwards compatibility stuff
            if (!isset($this['datasheetMeta']) || !is_array($this['datasheetMeta']) || !count($this['datasheetMeta'])) {
                // assign new meta data
                $this['datasheetMeta'] = $uploadManager->readMetaDataForFile($this['datasheet'], $fullPath);
            }
        }
        if ($currentFunc != 'edit') {
            $this['description'] = ((isset($this['description']) && !empty($this['description'])) ? DataUtil::formatForDisplayHTML($this['description']) : '');
        }
    
        $this->prepareItemActions();
    
        return true;
    }
    
    /**
     * Pre-Process the data prior to an insert operation.
     * The event happens before the entity managers persist operation is executed for this entity.
     *
     * Restrictions:
     *     - no access to entity manager or unit of work apis
     *     - no identifiers available if using an identity generator like sequences
     *     - Doctrine won't recognize changes on relations which are done here
     *       if this method is called by cascade persist
     *     - no creation of other entities allowed
     *
     * @see Datasheete_Entity_Datasheet::prePersistCallback()
     * @return boolean true if completed successfully else false.
     */
    protected function performPrePersistCallback()
    {
        // echo 'inserting a record ...';
        $this->validate();
        return true;
    }
    
    /**
     * Post-Process the data after an insert operation.
     * The event happens after the entity has been made persistant.
     * Will be called after the database insert operations.
     * The generated primary key values are available.
     *
     * Restrictions:
     *     - no access to entity manager or unit of work apis
     *
     * @see Datasheete_Entity_Datasheet::postPersistCallback()
     * @return boolean true if completed successfully else false.
     */
    protected function performPostPersistCallback()
    {
        // echo 'inserted a record ...';
        return true;
    }
    
    /**
     * Pre-Process the data prior a delete operation.
     * The event happens before the entity managers remove operation is executed for this entity.
     *
     * Restrictions:
     *     - no access to entity manager or unit of work apis
     *     - will not be called for a DQL DELETE statement
     *
     * @see Datasheete_Entity_Datasheet::preRemoveCallback()
     * @return boolean true if completed successfully else false.
     */
    protected function performPreRemoveCallback()
    {
        // delete workflow for this entity
        $workflow = $this['__WORKFLOW__'];
        if ($workflow['id'] > 0) {
            $result = (bool) DBUtil::deleteObjectByID('workflows', $workflow['id']);
            if ($result === false) {
                $dom = ZLanguage::getModuleDomain('Datasheete');
                return LogUtil::registerError(__('Error! Could not remove stored workflow. Deletion has been aborted.', $dom));
            }
        }
    
        return true;
    }
    
    /**
     * Post-Process the data after a delete.
     * The event happens after the entity has been deleted.
     * Will be called after the database delete operations.
     *
     * Restrictions:
     *     - no access to entity manager or unit of work apis
     *     - will not be called for a DQL DELETE statement
     *
     * @see Datasheete_Entity_Datasheet::postRemoveCallback()
     * @return boolean true if completed successfully else false.
     */
    protected function performPostRemoveCallback()
    {
        // echo 'deleted a record ...';
        $objectId = $this['id'];
        // initialise the upload handler
        $uploadManager = new Datasheete_UploadHandler();
    
        $uploadFields = array('datasheet');
        foreach ($uploadFields as $uploadField) {
            if (empty($this->$uploadField)) {
                continue;
            }
    
            // remove upload file (and image thumbnails)
            $uploadManager->deleteUploadFile('datasheet', $this, $uploadField, $objectId);
        }
    
        return true;
    }
    
    /**
     * Pre-Process the data prior to an update operation.
     * The event happens before the database update operations for the entity data.
     *
     * Restrictions:
     *     - no access to entity manager or unit of work apis
     *     - will not be called for a DQL UPDATE statement
     *     - changes on associations are not allowed and won't be recognized by flush
     *     - changes on properties won't be recognized by flush as well
     *     - no creation of other entities allowed
     *
     * @see Datasheete_Entity_Datasheet::preUpdateCallback()
     * @return boolean true if completed successfully else false.
     */
    protected function performPreUpdateCallback()
    {
        // echo 'updating a record ...';
        $this->validate();
        return true;
    }
    
    /**
     * Post-Process the data after an update operation.
     * The event happens after the database update operations for the entity data.
     *
     * Restrictions:
     *     - no access to entity manager or unit of work apis
     *     - will not be called for a DQL UPDATE statement
     *
     * @see Datasheete_Entity_Datasheet::postUpdateCallback()
     * @return boolean true if completed successfully else false.
     */
    protected function performPostUpdateCallback()
    {
        // echo 'updated a record ...';
        return true;
    }
    
    /**
     * Pre-Process the data prior to a save operation.
     * This combines the PrePersist and PreUpdate events.
     * For more information see corresponding callback handlers.
     *
     * @see Datasheete_Entity_Datasheet::preSaveCallback()
     * @return boolean true if completed successfully else false.
     */
    protected function performPreSaveCallback()
    {
        // echo 'saving a record ...';
        $this->validate();
        return true;
    }
    
    /**
     * Post-Process the data after a save operation.
     * This combines the PostPersist and PostUpdate events.
     * For more information see corresponding callback handlers.
     *
     * @see Datasheete_Entity_Datasheet::postSaveCallback()
     * @return boolean true if completed successfully else false.
     */
    protected function performPostSaveCallback()
    {
        // echo 'saved a record ...';
        return true;
    }
    

    /**
     * ToString interceptor implementation.
     * This method is useful for debugging purposes.
     */
    public function __toString()
    {
        return $this->getId();
    }

    /**
     * Clone interceptor implementation.
     * This method is for example called by the reuse functionality.
     * Performs a quite simple shallow copy.
     *
     * See also:
     * (1) http://docs.doctrine-project.org/en/latest/cookbook/implementing-wakeup-or-clone.html
     * (2) http://www.sunilb.com/php/php5-oops-tutorial-magic-methods-__clone-method
     * (3) http://stackoverflow.com/questions/185934/how-do-i-create-a-copy-of-an-object-in-php
     */
    public function __clone()
    {
        // If the entity has an identity, proceed as normal.
        if ($this->id) {
            // create new instance
            
            $entity = new \Datasheete_Entity_Datasheet();
            // unset identifiers
            $entity->setId(null);
            // copy simple fields
            $entity->set_objectType($this->get_objectType());
            $entity->set_idFields($this->get_idFields());
            $entity->set_hasUniqueSlug($this->get_hasUniqueSlug());
            $entity->set_actions($this->get_actions());
            $entity->initValidator();
            $entity->setName($this->getName());
            $entity->setKind($this->getKind());
            $entity->setDatasheet($this->getDatasheet());
            $entity->setDescription($this->getDescription());
    
    
            return $entity;
        }
        // otherwise do nothing, do NOT throw an exception!
    }
}
