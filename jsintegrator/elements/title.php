<?php defined('JPATH_BASE') or die();

/**
 * Renders a spacer element
 *
 * @package 	Joomla.Framework
 * @subpackage		Parameter
 * @since		1.5
 */

class JElementTitle extends JElement
{
	/**
	* Element name
	*
	* @access	protected
	* @var		string
	*/
	var	$_name = 'Title';


	function fetchElement($name, $value, &$node, $control_name)
	{
		$html = '';
		if ($value) {
			$html .= '<div style="margin: 10px 0 5px 0; font-weight: bold; padding: 5px; background-color: #C9DDF9;">';
			$html .= JText::_($value);
			$html .= '</div>';
		}
		
		return $html;
	}
}
