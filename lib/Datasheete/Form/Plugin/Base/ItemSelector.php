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
 * Item selector plugin base class.
 */
class Datasheete_Form_Plugin_Base_ItemSelector extends Zikula_Form_Plugin_TextInput
{
    /**
     * The treated object type.
     *
     * @var string
     */
    public $objectType = '';

    /**
     * Identifier of selected object.
     *
     * @var integer
     */
    public $selectedItemId = 0;

    /**
     * Get filename of this file.
     * The information is used to re-establish the plugins on postback.
     *
     * @return string
     */
    public function getFilename()
    {
        return __FILE__;
    }

    /**
     * Create event handler.
     *
     * @param Zikula_Form_View $view    Reference to Zikula_Form_View object.
     * @param array            &$params Parameters passed from the Smarty plugin function.
     *
     * @see    Zikula_Form_AbstractPlugin
     * @return void
     */
    public function create(Zikula_Form_View $view, &$params)
    {
        $params['maxLength'] = 11;
        /*$params['width'] = '8em';*/

        // let parent plugin do the work in detail
        parent::create($view, $params);
    }

    /**
     * Helper method to determine css class.
     *
     * @see    Zikula_Form_Plugin_TextInput
     *
     * @return string the list of css classes to apply
     */
    protected function getStyleClass()
    {
        $class = parent::getStyleClass();
        return str_replace('z-form-text', 'z-form-itemlist ' . strtolower($this->objectType), $class);
    }

    /**
     * Render event handler.
     *
     * @param Zikula_Form_View $view Reference to Zikula_Form_View object.
     *
     * @return string The rendered output
     */
    public function render(Zikula_Form_View $view)
    {
        static $firstTime = true;
        if ($firstTime) {
            PageUtil::addVar('javascript', 'prototype');
            PageUtil::addVar('javascript', 'Zikula.UI'); // imageviewer
            PageUtil::addVar('javascript', 'modules/Datasheete/javascript/finder.js');
            PageUtil::addVar('stylesheet', ThemeUtil::getModuleStylesheet('Datasheete'));
        }
        $firstTime = false;

        if (!SecurityUtil::checkPermission('Datasheete:' . ucwords($this->objectType) . ':', '::', ACCESS_COMMENT)) {
            return false;
        }

        $entityClass = 'Datasheete_Entity_' . ucwords($this->objectType);
        $serviceManager = ServiceUtil::getManager();
        $entityManager = $serviceManager->getService('doctrine.entitymanager');
        $repository = $entityManager->getRepository($entityClass);

        $sort = $repository->getDefaultSortingField();
        $sdir = 'asc';

        // convenience vars to make code clearer
        $where = '';
        $sortParam = $sort . ' ' . $sdir;

        $entities = $repository->selectWhere($where, $sortParam);

        $view = Zikula_View::getInstance('Datasheete', false);
        $view->assign('items', $entities)
             ->assign('selectedId', $this->selectedItemId);

        return $view->fetch('external/' . $this->objectType . '/select.tpl');
    }

    /**
     * Decode event handler.
     *
     * @param Zikula_Form_View $view Zikula_Form_View object.
     *
     * @return void
     */
    public function decode(Zikula_Form_View $view)
    {
        parent::decode($view);
        $value = explode(';', $this->text);
        $this->objectType = $value[0];
        $this->selectedItemId = $value[1];
    }

    /**
     * Parses a value.
     *
     * @param Zikula_Form_View $view Reference to Zikula_Form_View object.
     * @param string           $text Text.
     *
     * @return string Parsed Text.
     */
    public function parseValue(Zikula_Form_View $view, $text)
    {
        $valueParts = array($this->objectType, $this->selectedItemId);
        return implode(';', $valueParts);
    }

    /**
     * Load values.
     *
     * Called internally by the plugin itself to load values from the render.
     * Can also by called when some one is calling the render object's Zikula_Form_ViewetValues.
     *
     * @param Zikula_Form_View $view    Reference to Zikula_Form_View object.
     * @param array            &$values Values to load.
     *
     * @return void
     */
    public function loadValue(Zikula_Form_View $view, &$values)
    {
        if (!$this->dataBased) {
            return;
        }

        $value = null;

        if ($this->group == null) {
            if (array_key_exists($this->dataField, $values)) {
                $value = $values[$this->dataField];
            }
        } else {
            if (array_key_exists($this->group, $values) && array_key_exists($this->dataField, $values[$this->group])) {
                $value = $values[$this->group][$this->dataField];
            }
        }

        if ($value !== null) {
            //$this->text = $this->formatValue($view, $value);
            $value = explode(';', $value);
            $this->objectType = $value[0];
            $this->selectedItemId = $value[1];
        }
    }
}
