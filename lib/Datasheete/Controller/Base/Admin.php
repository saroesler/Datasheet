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
 * Admin controller class.
 */
class Datasheete_Controller_Base_Admin extends Zikula_AbstractController
{
    /**
     * Post initialise.
     *
     * Run after construction.
     *
     * @return void
     */
    protected function postInitialize()
    {
        // Set caching to false by default.
        $this->view->setCaching(Zikula_View::CACHE_DISABLED);
    }

    /**
     * This method provides a generic handling of simple delete requests.
     *
     * @param array $args List of arguments.
     * @param string  $ot           Treated object type.
     * @param int     $id           Identifier of entity to be deleted.
     * @param boolean $confirmation Confirm the deletion, else a confirmation page is displayed.
     * @param string  $tpl          Name of alternative template (for alternative display options, feeds and xml output)
     * @param boolean $raw          Optional way to display a template instead of fetching it (needed for standalone output)
     *
     * @return mixed Output.
     */
    public function delete(array $args = array())
    {
        $controllerHelper = new Datasheete_Util_Controller($this->serviceManager);
        
        // parameter specifying which type of objects we are treating
        $objectType = (isset($args['ot']) && !empty($args['ot'])) ? $args['ot'] : $this->request->query->filter('ot', 'datasheet', FILTER_SANITIZE_STRING);
        $utilArgs = array('controller' => 'admin', 'action' => 'delete');
        if (!in_array($objectType, $controllerHelper->getObjectTypes('controllerAction', $utilArgs))) {
            $objectType = $controllerHelper->getDefaultObjectType('controllerAction', $utilArgs);
        }
        $this->throwForbiddenUnless(SecurityUtil::checkPermission($this->name . ':' . ucwords($objectType) . ':', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        $idFields = ModUtil::apiFunc($this->name, 'selection', 'getIdFields', array('ot' => $objectType));
        
        // retrieve identifier of the object we wish to delete
        $idValues = $controllerHelper->retrieveIdentifier($this->request, $args, $objectType, $idFields);
        $hasIdentifier = $controllerHelper->isValidIdentifier($idValues);
        
        $this->throwNotFoundUnless($hasIdentifier, $this->__('Error! Invalid identifier received.'));
        
        $entity = ModUtil::apiFunc($this->name, 'selection', 'getEntity', array('ot' => $objectType, 'id' => $idValues));
        $this->throwNotFoundUnless($entity != null, $this->__('No such item.'));
        
        $entity->initWorkflow();
        
        $workflowHelper = new Datasheete_Util_Workflow($this->serviceManager);
        $deleteActionId = 'delete';
        $deleteAllowed = false;
        $actions = $workflowHelper->getActionsForObject($entity);
        if ($actions === false || !is_array($actions)) {
            return LogUtil::registerError($this->__('Error! Could not determine workflow actions.'));
        }
        foreach ($actions as $actionId => $action) {
            if ($actionId != $deleteActionId) {
                continue;
            }
            $deleteAllowed = true;
            break;
        }
        if (!$deleteAllowed) {
            return LogUtil::registerError($this->__('Error! It is not allowed to delete this entity.'));
        }
        
        $confirmation = (bool) (isset($args['confirmation']) && !empty($args['confirmation'])) ? $args['confirmation'] : $this->request->request->filter('confirmation', false, FILTER_VALIDATE_BOOLEAN);
        if ($confirmation) {
            $this->checkCsrfToken();
        
            $hookAreaPrefix = $entity->getHookAreaPrefix();
            $hookType = 'validate_delete';
            // Let any hooks perform additional validation actions
            $hook = new Zikula_ValidationHook($hookAreaPrefix . '.' . $hookType, new Zikula_Hook_ValidationProviders());
            $validators = $this->notifyHooks($hook)->getValidators();
            if (!$validators->hasErrors()) {
                // execute the workflow action
                $success = $workflowHelper->executeAction($entity, $deleteActionId);
                if ($success) {
                    $this->registerStatus($this->__('Done! Item deleted.'));
                }
        
                // Let any hooks know that we have created, updated or deleted an item
                $hookType = 'process_delete';
                $hook = new Zikula_ProcessHook($hookAreaPrefix . '.' . $hookType, $entity->createCompositeIdentifier());
                $this->notifyHooks($hook);
        
                // An item was deleted, so we clear all cached pages this item.
                $cacheArgs = array('ot' => $objectType, 'item' => $entity);
                ModUtil::apiFunc($this->name, 'cache', 'clearItemCache', $cacheArgs);
        
                // redirect to the list of the current object type
                $this->redirect(ModUtil::url($this->name, 'admin', 'view',
                                                                                            array('ot' => $objectType)));
            }
        }
        
        $entityClass = $this->name . '_Entity_' . ucwords($objectType);
        $repository = $this->entityManager->getRepository($entityClass);
        
        // set caching id
        $this->view->setCaching(Zikula_View::CACHE_DISABLED);
        
        // assign the object we loaded above
        $this->view->assign($objectType, $entity)
                   ->assign($repository->getAdditionalTemplateParameters('controllerAction', $utilArgs));
        
        // fetch and return the appropriate template
        $viewHelper = new Datasheete_Util_View($this->serviceManager);
        
        return $viewHelper->processTemplate($this->view, 'admin', $objectType, 'delete', $args);
    }
    
    /**
     * This method provides a generic handling of all edit requests.
     *
     * @param array $args List of arguments.
     * @param string  $ot           Treated object type.
     * @param string  $tpl          Name of alternative template (for alternative display options, feeds and xml output)
     * @param boolean $raw          Optional way to display a template instead of fetching it (needed for standalone output)
     *
     * @return mixed Output.
     */
    public function edit(array $args = array())
    {
        $controllerHelper = new Datasheete_Util_Controller($this->serviceManager);
        
        // parameter specifying which type of objects we are treating
        $objectType = (isset($args['ot']) && !empty($args['ot'])) ? $args['ot'] : $this->request->query->filter('ot', 'datasheet', FILTER_SANITIZE_STRING);
        $utilArgs = array('controller' => 'admin', 'action' => 'edit');
        if (!in_array($objectType, $controllerHelper->getObjectTypes('controllerAction', $utilArgs))) {
            $objectType = $controllerHelper->getDefaultObjectType('controllerAction', $utilArgs);
        }
        $this->throwForbiddenUnless(SecurityUtil::checkPermission($this->name . ':' . ucwords($objectType) . ':', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        
        // create new Form reference
        $view = FormUtil::newForm($this->name, $this);
        
        // build form handler class name
        $handlerClass = $this->name . '_Form_Handler_Admin_' . ucfirst($objectType) . '_Edit';
        
        // determine the output template
        $viewHelper = new Datasheete_Util_View($this->serviceManager);
        $template = $viewHelper->getViewTemplate($this->view, 'admin', $objectType, 'edit', $args);
        
        // execute form using supplied template and page event handler
        return $view->execute($template, new $handlerClass());
    }
    
    /**
     * This method provides a generic item detail view.
     *
     * @param array $args List of arguments.
     * @param string  $ot           Treated object type.
     * @param string  $tpl          Name of alternative template (for alternative display options, feeds and xml output)
     * @param boolean $raw          Optional way to display a template instead of fetching it (needed for standalone output)
     *
     * @return mixed Output.
     */
    public function display(array $args = array())
    {
        $controllerHelper = new Datasheete_Util_Controller($this->serviceManager);
        
        // parameter specifying which type of objects we are treating
        $objectType = (isset($args['ot']) && !empty($args['ot'])) ? $args['ot'] : $this->request->query->filter('ot', 'datasheet', FILTER_SANITIZE_STRING);
        $utilArgs = array('controller' => 'admin', 'action' => 'display');
        if (!in_array($objectType, $controllerHelper->getObjectTypes('controllerAction', $utilArgs))) {
            $objectType = $controllerHelper->getDefaultObjectType('controllerAction', $utilArgs);
        }
        $this->throwForbiddenUnless(SecurityUtil::checkPermission($this->name . ':' . ucwords($objectType) . ':', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        $entityClass = $this->name . '_Entity_' . ucwords($objectType);
        $repository = $this->entityManager->getRepository($entityClass);
        
        $idFields = ModUtil::apiFunc($this->name, 'selection', 'getIdFields', array('ot' => $objectType));
        
        // retrieve identifier of the object we wish to view
        $idValues = $controllerHelper->retrieveIdentifier($this->request, $args, $objectType, $idFields);
        $hasIdentifier = $controllerHelper->isValidIdentifier($idValues);
        $this->throwNotFoundUnless($hasIdentifier, $this->__('Error! Invalid identifier received.'));
        
        $entity = ModUtil::apiFunc($this->name, 'selection', 'getEntity', array('ot' => $objectType, 'id' => $idValues));
        $this->throwNotFoundUnless($entity != null, $this->__('No such item.'));
        unset($idValues);
        
        $entity->initWorkflow();
        
        // build ModUrl instance for display hooks; also create identifier for permission check
        $currentUrlArgs = array('ot' => $objectType);
        $instanceId = '';
        foreach ($idFields as $idField) {
            $currentUrlArgs[$idField] = $entity[$idField];
            if (!empty($instanceId)) {
                $instanceId .= '_';
            }
            $instanceId .= $entity[$idField];
        }
        $currentUrlArgs['id'] = $instanceId;
        if (isset($entity['slug'])) {
            $currentUrlArgs['slug'] = $entity['slug'];
        }
        $currentUrlObject = new Zikula_ModUrl($this->name, 'admin', 'display', ZLanguage::getLanguageCode(), $currentUrlArgs);
        
        $this->throwForbiddenUnless(SecurityUtil::checkPermission($this->name . ':' . ucwords($objectType) . ':', $instanceId . '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        
        $viewHelper = new Datasheete_Util_View($this->serviceManager);
        $templateFile = $viewHelper->getViewTemplate($this->view, 'admin', $objectType, 'display', $args);
        
        // set cache id
        $component = $this->name . ':' . ucwords($objectType) . ':';
        $instance = $instanceId . '::';
        $accessLevel = ACCESS_READ;
        if (SecurityUtil::checkPermission($component, $instance, ACCESS_COMMENT)) {
            $accessLevel = ACCESS_COMMENT;
        }
        if (SecurityUtil::checkPermission($component, $instance, ACCESS_EDIT)) {
            $accessLevel = ACCESS_EDIT;
        }
        $this->view->setCacheId($objectType . '|' . $instanceId . '|a' . $accessLevel);
        
        // assign output data to view object.
        $this->view->assign($objectType, $entity)
                   ->assign('currentUrlObject', $currentUrlObject)
                   ->assign($repository->getAdditionalTemplateParameters('controllerAction', $utilArgs));
        
        // fetch and return the appropriate template
        return $viewHelper->processTemplate($this->view, 'admin', $objectType, 'display', $args, $templateFile);
    }
    
    /**
     * This method provides a generic item list overview.
     *
     * @param array $args List of arguments.
     * @param string  $ot           Treated object type.
     * @param string  $sort         Sorting field.
     * @param string  $sortdir      Sorting direction.
     * @param int     $pos          Current pager position.
     * @param int     $num          Amount of entries to display.
     * @param string  $tpl          Name of alternative template (for alternative display options, feeds and xml output)
     * @param boolean $raw          Optional way to display a template instead of fetching it (needed for standalone output)
     *
     * @return mixed Output.
     */
    public function view(array $args = array())
    {
        $controllerHelper = new Datasheete_Util_Controller($this->serviceManager);
        
        // parameter specifying which type of objects we are treating
        $objectType = (isset($args['ot']) && !empty($args['ot'])) ? $args['ot'] : $this->request->query->filter('ot', 'datasheet', FILTER_SANITIZE_STRING);
        $utilArgs = array('controller' => 'admin', 'action' => 'view');
        if (!in_array($objectType, $controllerHelper->getObjectTypes('controllerAction', $utilArgs))) {
            $objectType = $controllerHelper->getDefaultObjectType('controllerAction', $utilArgs);
        }
        $this->throwForbiddenUnless(SecurityUtil::checkPermission($this->name . ':' . ucwords($objectType) . ':', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        $entityClass = $this->name . '_Entity_' . ucwords($objectType);
        $repository = $this->entityManager->getRepository($entityClass);
        $viewHelper = new Datasheete_Util_View($this->serviceManager);
        
        // parameter for used sorting field
        $sort = (isset($args['sort']) && !empty($args['sort'])) ? $args['sort'] : $this->request->query->filter('sort', '', FILTER_SANITIZE_STRING);
        if (empty($sort) || !in_array($sort, $repository->getAllowedSortingFields())) {
            $sort = $repository->getDefaultSortingField();
        }
        
        // parameter for used sort order
        $sdir = (isset($args['sortdir']) && !empty($args['sortdir'])) ? $args['sortdir'] : $this->request->query->filter('sortdir', '', FILTER_SANITIZE_STRING);
        $sdir = strtolower($sdir);
        if ($sdir != 'asc' && $sdir != 'desc') {
            $sdir = 'asc';
        }
        
        // convenience vars to make code clearer
        $currentUrlArgs = array('ot' => $objectType);
        
        $selectionArgs = array(
            'ot' => $objectType,
            'where' => '',
            'orderBy' => $sort . ' ' . $sdir
        );
        
        $showOwnEntries = (int) (isset($args['own']) && !empty($args['own'])) ? $args['own'] : $this->request->query->filter('own', 0, FILTER_VALIDATE_INT);
        $showAllEntries = (int) (isset($args['all']) && !empty($args['all'])) ? $args['all'] : $this->request->query->filter('all', 0, FILTER_VALIDATE_INT);
        
        $this->view->assign('showOwnEntries', $showOwnEntries)
                   ->assign('showAllEntries', $showAllEntries);
        if ($showOwnEntries == 1) {
            $currentUrlArgs['own'] = 1;
        }
        if ($showAllEntries == 1) {
            $currentUrlArgs['all'] = 1;
        }
        
        // prepare access level for cache id
        $accessLevel = ACCESS_READ;
        $component = 'Datasheete:' . ucwords($objectType) . ':';
        $instance = '::';
        if (SecurityUtil::checkPermission($component, $instance, ACCESS_COMMENT)) {
            $accessLevel = ACCESS_COMMENT;
        }
        if (SecurityUtil::checkPermission($component, $instance, ACCESS_EDIT)) {
            $accessLevel = ACCESS_EDIT;
        }
        
        $templateFile = $viewHelper->getViewTemplate($this->view, 'admin', $objectType, 'view', $args);
        $cacheId = 'view|ot_' . $objectType . '_sort_' . $sort . '_' . $sdir;
        
        $resultsPerPage = 0;
        if ($showAllEntries == 1) {
            // set cache id
            $this->view->setCacheId($cacheId . '_all_1_own_' . $showOwnEntries . '_' . $accessLevel);
        
            // if page is cached return cached content
            if ($this->view->is_cached($templateFile)) {
                return $viewHelper->processTemplate($this->view, 'admin', $objectType, 'view', $args, $templateFile);
            }
        
            // retrieve item list without pagination
            $entities = ModUtil::apiFunc($this->name, 'selection', 'getEntities', $selectionArgs);
        } else {
            // the current offset which is used to calculate the pagination
            $currentPage = (int) (isset($args['pos']) && !empty($args['pos'])) ? $args['pos'] : $this->request->query->filter('pos', 1, FILTER_VALIDATE_INT);
        
            // the number of items displayed on a page for pagination
            $resultsPerPage = (int) (isset($args['num']) && !empty($args['num'])) ? $args['num'] : $this->request->query->filter('num', 0, FILTER_VALIDATE_INT);
            if ($resultsPerPage == 0) {
                $csv = (int) (isset($args['usecsv']) && !empty($args['usecsv'])) ? $args['usecsv'] : $this->request->query->filter('usecsvext', 0, FILTER_VALIDATE_INT);
                $resultsPerPage = ($csv == 1) ? 999999 : $this->getVar('pageSize', 10);
            }
        
            // set cache id
            $this->view->setCacheId($cacheId . '_amount_' . $resultsPerPage . '_page_' . $currentPage . '_own_' . $showOwnEntries . '_' . $accessLevel);
        
            // if page is cached return cached content
            if ($this->view->is_cached($templateFile)) {
                return $viewHelper->processTemplate($this->view, 'admin', $objectType, 'view', $args, $templateFile);
            }
        
            // retrieve item list with pagination
            $selectionArgs['currentPage'] = $currentPage;
            $selectionArgs['resultsPerPage'] = $resultsPerPage;
            list($entities, $objectCount) = ModUtil::apiFunc($this->name, 'selection', 'getEntitiesPaginated', $selectionArgs);
        
            $this->view->assign('currentPage', $currentPage)
                       ->assign('pager', array('numitems'     => $objectCount,
                                               'itemsperpage' => $resultsPerPage));
        }
        
        foreach ($entities as $k => $entity) {
            $entity->initWorkflow();
        }
        
        // build ModUrl instance for display hooks
        $currentUrlObject = new Zikula_ModUrl($this->name, 'admin', 'view', ZLanguage::getLanguageCode(), $currentUrlArgs);
        
        // assign the object data, sorting information and details for creating the pager
        $this->view->assign('items', $entities)
                   ->assign('sort', $sort)
                   ->assign('sdir', $sdir)
                   ->assign('pageSize', $resultsPerPage)
                   ->assign('currentUrlObject', $currentUrlObject)
                   ->assign($repository->getAdditionalTemplateParameters('controllerAction', $utilArgs));
        
        // fetch and return the appropriate template
        return $viewHelper->processTemplate($this->view, 'admin', $objectType, 'view', $args, $templateFile);
    }
    
    /**
     * This method is the default function handling the admin area called without defining arguments.
     *
     * @param array $args List of arguments.
     *
     * @return mixed Output.
     */
    public function main(array $args = array())
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        // set caching id
        $this->view->setCacheId('main');
        
        
        // return main template
        return $this->view->fetch('admin/main.tpl');
        
    }
    

    /**
     * Process status changes for multiple items.
     *
     * This function processes the items selected in the admin view page.
     * Multiple items may have their state changed or be deleted.
     *
     * @param array  items  Identifier list of the items to be processed.
     * @param string action The action to be executed.
     *
     * @return bool true on sucess, false on failure.
     */
    public function handleselectedentries(array $args = array())
    {
        $this->checkCsrfToken();
    
        $returnUrl = ModUtil::url($this->name, 'admin', 'main');
    
        // Determine object type
        $objectType = isset($args['ot']) ? $args['ot'] : $this->request->request->get('ot', '');
        if (!$objectType) {
            return System::redirect($returnUrl);
        }
        $returnUrl = ModUtil::url($this->name, 'admin', 'view', array('ot' => $objectType));
    
        // Get other parameters
        $items = isset($args['items']) ? $args['items'] : $this->request->request->get('items', null);
        $action = isset($args['action']) ? $args['action'] : $this->request->request->get('action', null);
        $action = strtolower($action);
    
        $workflowHelper = new Datasheete_Util_Workflow($this->serviceManager);
    
        // process each item
        foreach ($items as $itemid) {
            // check if item exists, and get record instance
            $selectionArgs = array('ot' => $objectType, 'id' => $itemid, 'useJoins' => false);
            $entity = ModUtil::apiFunc($this->name, 'selection', 'getEntity', $selectionArgs);
    
            $entity->initWorkflow();
    
            // check if $action can be applied to this entity (may depend on it's current workflow state)
            $allowedActions = $workflowHelper->getActionsForObject($entity);
            $actionIds = array_keys($allowedActions);
            if (!in_array($action, $actionIds)) {
                // action not allowed, skip this object
                continue;
            }
    
            $hookAreaPrefix = $entity->getHookAreaPrefix();
    
            // Let any hooks perform additional validation actions
            $hookType = $action == 'delete' ? 'validate_delete' : 'validate_edit';
            $hook = new Zikula_ValidationHook($hookAreaPrefix . '.' . $hookType, new Zikula_Hook_ValidationProviders());
            $validators = $this->notifyHooks($hook)->getValidators();
            if ($validators->hasErrors()) {
                continue;
            }
    
            $success = false;
            try {
                // execute the workflow action
                $success = $workflowHelper->executeAction($entity, $action);
            } catch(\Exception $e) {
                LogUtil::registerError($this->__f('Sorry, but an unknown error occured during the %s action. Please apply the changes again!', array($action)));
            }
    
            if (!$success) {
                continue;
            }
    
            if ($action == 'delete') {
                LogUtil::registerStatus($this->__('Done! Item deleted.'));
            } else {
                LogUtil::registerStatus($this->__('Done! Item updated.'));
            }
    
            // Let any hooks know that we have updated or deleted an item
            $hookType = $action == 'delete' ? 'process_delete' : 'process_edit';
            $url = null;
            if ($action != 'delete') {
                $urlArgs = $entity->createUrlArgs();
                $url = new Zikula_ModUrl($this->name, 'admin', 'display', ZLanguage::getLanguageCode(), $urlArgs);
            }
            $hook = new Zikula_ProcessHook($hookAreaPrefix . '.' . $hookType, $entity->createCompositeIdentifier(), $url);
            $this->notifyHooks($hook);
    
            // An item was updated or deleted, so we clear all cached pages for this item.
            $cacheArgs = array('ot' => $objectType, 'item' => $entity);
            ModUtil::apiFunc($this->name, 'cache', 'clearItemCache', $cacheArgs);
        }
    
        // clear view cache to reflect our changes
        $this->view->clear_cache();
    
        return System::redirect($returnUrl);
    }

    /**
     * This method cares for a redirect within an inline frame.
     *
     * @return boolean
     */
    public function handleInlineRedirect()
    {
        $itemId = (int) $this->request->query->filter('id', 0, FILTER_VALIDATE_INT);
        $idPrefix = $this->request->query->filter('idp', '', FILTER_SANITIZE_STRING);
        $commandName = $this->request->query->filter('com', '', FILTER_SANITIZE_STRING);
        if (empty($idPrefix)) {
            return false;
        }

        $this->view->assign('itemId', $itemId)
                   ->assign('idPrefix', $idPrefix)
                   ->assign('commandName', $commandName)
                   ->assign('jcssConfig', JCSSUtil::getJSConfig());

        $view->display('admin/inlineRedirectHandler.tpl');

        return true;
    }
}
