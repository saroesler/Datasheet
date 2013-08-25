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
 * Wrapper class for styling <div> elements and a validation summary.
 */
class Datasheete_Form_Plugin_FormFrame extends Zikula_Form_AbstractPlugin
{
    /**
     * Whether a tabbed panel should be used or not.
     *
     * @var boolean
     */
    public $useTabs;

    /**
     * Name of css class to be used for the frame element.
     *
     * @var string
     */
    public $cssClass = 'tabs';

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
     * This fires once, immediately <i>after</i> member variables have been populated from Smarty parameters
     * (in {@link readParameters()}). Default action is to do nothing.
     *
     * @see Zikula_Form_View::registerPlugin()
     *
     * @param Zikula_Form_View $view    Reference to Zikula_Form_View object.
     * @param array            &$params Parameters passed from the Smarty plugin function.
     *
     * @see    Zikula_Form_AbstractPlugin
     * @return void
     */
    public function create(Zikula_Form_View $view, &$params)
    {
        $this->useTabs = (array_key_exists('useTabs', $params) ? $params['useTabs'] : false);
    }

    /**
     * RenderBegin event handler.
     *
     * Default action is to return an empty string.
     *
     * @param Zikula_Form_View $view Reference to Zikula_Form_View object.
     *
     * @return string The rendered output.
     */
    public function renderBegin(Zikula_Form_View $view)
    {
        $tabClass = $this->useTabs ? ' ' . $this->cssClass : '';

        return '<div class="datasheeteForm' . $tabClass . '">' . "\n";
    }

    /**
     * RenderEnd event handler.
     *
     * Default action is to return an empty string.
     *
     * @param Zikula_Form_View $view Reference to Zikula_Form_View object.
     *
     * @return string The rendered output.
     */
    public function renderEnd(Zikula_Form_View $view)
    {
        return '</div>' . "\n";
    }
}
