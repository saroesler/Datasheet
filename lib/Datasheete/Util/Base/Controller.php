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
 * Utility base class for controller helper methods.
 */
class Datasheete_Util_Base_Controller extends Zikula_AbstractBase
{
    /**
     * Returns an array of all allowed object types in Datasheete.
     *
     * @param string $context Usage context (allowed values: controllerAction, api, actionHandler, block, contentType).
     * @param array  $args    Additional arguments.
     *
     * @return array List of allowed object types.
     */
    public function getObjectTypes($context = '', $args = array())
    {
        if (!in_array($context, array('controllerAction', 'api', 'actionHandler', 'block', 'contentType'))) {
            $context = 'controllerAction';
        }
    
        $allowedObjectTypes = array();
        $allowedObjectTypes[] = 'datasheet';
    
        return $allowedObjectTypes;
    }

    /**
     * Returns the default object type in Datasheete.
     *
     * @param string $context Usage context (allowed values: controllerAction, api, actionHandler, block, contentType).
     * @param array  $args    Additional arguments.
     *
     * @return string The name of the default object type.
     */
    public function getDefaultObjectType($context = '', $args = array())
    {
        if (!in_array($context, array('controllerAction', 'api', 'actionHandler', 'block', 'contentType'))) {
            $context = 'controllerAction';
        }
    
        $defaultObjectType = 'datasheet';
    
        return $defaultObjectType;
    }

    /**
     * Retrieve identifier parameters for a given object type.
     *
     * @param Zikula_Request_Http $request    Instance of Zikula_Request_Http.
     * @param array               $args       List of arguments used as fallback if request does not contain a field.
     * @param string              $objectType Name of treated entity type.
     * @param array               $idFields   List of identifier field names.
     *
     * @return array List of fetched identifiers.
     */
    public function retrieveIdentifier(Zikula_Request_Http $request, array $args, $objectType = '', array $idFields)
    {
        $idValues = array();
        foreach ($idFields as $idField) {
            $defaultValue = isset($args[$idField]) && is_numeric($args[$idField]) ? $args[$idField] : 0;
            
            
            $id = $request->query->filter($idField, $defaultValue);
            if (!$id && $idField != 'id' && count($idFields) == 1) {
                $defaultValue = isset($args['id']) && is_numeric($args['id']) ? $args['id'] : 0;
                $id = (int) $request->query->filter('id', $defaultValue, FILTER_VALIDATE_INT);
            }
            $idValues[$idField] = $id;
        }
    
        return $idValues;
    }

    /**
     * Checks if all identifiers are set properly.
     *
     * @param array  $idValues List of identifier field values.
     *
     * @return boolean Whether all identifiers are set or not.
     */
    public function isValidIdentifier(array $idValues)
    {
        if (!count($idValues)) {
            return false;
        }
    
        foreach ($idValues as $idField => $idValue) {
            if (!$idValue) {
                return false;
            }
        }
    
        return true;
    }

    /**
     * Create nice permalinks.
     *
     * @param string $name The given object title.
     *
     * @return string processed permalink.
     * @deprecated made obsolete by Doctrine extensions.
     */
    public function formatPermalink($name)
    {
        $name = str_replace(array('ä', 'ö', 'ü', 'Ä', 'Ö', 'Ü', 'ß', '.', '?', '"', '/', ':', 'é', 'è', 'â'),
                            array('ae', 'oe', 'ue', 'Ae', 'Oe', 'Ue', 'ss', '', '', '', '-', '-', 'e', 'e', 'a'),
                            $name);
        $name = DataUtil::formatPermalink($name);
    
        return strtolower($name);
    }

    /**
     * Retrieve the base path for given object type and upload field combination.
     *
     * @param string $objectType Name of treated entity type.
     * @param string $fieldName  Name of upload field.
     *
     * @return mixed Output.
     * @throws Exception if invalid object type is given.
     */
    public function getFileBaseFolder($objectType, $fieldName)
    {
        if (!in_array($objectType, $this->getObjectTypes())) {
            throw new Exception('Error! Invalid object type received.');
        }
    
        $basePath = FileUtil::getDataDirectory() . '/Datasheete/';
    
        switch ($objectType) {
            case 'datasheet':
                $basePath .= 'datasheets/datasheet/';
            break;
        }
    
        return DataUtil::formatForOS($basePath);
    }

    /**
     * Creates upload folder including a subfolder for thumbnail and an .htaccess file within it.
     *
     * @param string $objectType        Name of treated entity type.
     * @param string $fieldName         Name of upload field.
     * @param string $allowedExtensions String with list of allowed file extensions (separated by ", ").
     *
     * @return Boolean whether everything went okay or not.
     */
    public function checkAndCreateUploadFolder($objectType, $fieldName, $allowedExtensions = '')
    {
        $uploadPath = $this->getFileBaseFolder($objectType, $fieldName);
    
        // Check if directory exist and try to create it if needed
        if (!is_dir($uploadPath) && !FileUtil::mkdirs($uploadPath, 0777)) {
            LogUtil::registerStatus($this->__f('The upload directory "%s" does not exist and could not be created. Try to create it yourself and make sure that this folder is accessible via the web and writable by the webserver.', array($uploadPath)));
            return false;
        }
    
        // Check if directory is writable and change permissions if needed
        if (!is_writable($uploadPath) && !chmod($uploadPath, 0777)) {
            LogUtil::registerStatus($this->__f('Warning! The upload directory at "%s" exists but is not writable by the webserver.', array($uploadPath)));
            return false;
        }
    
        // Write a htaccess file into the upload directory
        $htaccessFilePath = $uploadPath . '/.htaccess';
        $htaccessFileTemplate = 'modules/Datasheete/docs/htaccessTemplate';
        if (!file_exists($htaccessFilePath) && file_exists($htaccessFileTemplate)) {
            $extensions = str_replace(',', '|', str_replace(' ', '', $allowedExtensions));
            $htaccessContent = str_replace('__EXTENSIONS__', $extensions, FileUtil::readFile($htaccessFileTemplate));
            if (!FileUtil::writeFile($htaccessFilePath, $htaccessContent)) {
                LogUtil::registerStatus($this->__f('Warning! Could not write the .htaccess file at "%s".', array($htaccessFilePath)));
                return false;
            }
        }
    
        return true;
    }
}
