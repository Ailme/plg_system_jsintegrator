<?php defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

class plgSystemJSIntegrator extends JPlugin
{
	private $js;

	public function __construct(&$subject, $config)
	{
        $document = &JFactory::getDocument(); // set document for next usage
        $doctype = $document->getType(); // get document type

        // disable plugin for non-HTML interface (like RSS feed or PDF)
        if ($doctype !== 'html') return false;

		parent::__construct( $subject, $config );

		JPlugin::loadLanguage('plg_system_jsintegrator', JPATH_ADMINISTRATOR); // define language

		//$this->js = JURI::root() . 'plugins/system/jsintegrator/js/';
		$this->js = '/plugins/system/jsintegrator/js/';
	}

	private function addScript($file)
	{
        $document = &JFactory::getDocument();
		$document->addScript($file);
	}

    private function jquery()
	{
		if ($this->enableLibrary('jquery')) {
			switch ((int) $this->params->get('jquery_load', 0))
			{
				case 1:
					$this->addScript($this->js . 'jquery/jquery-1.5.2_min.js');
					break;
				case 2:
				default:
					$this->addScript('http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js');
					break;
			}
			$this->jqueryNoConflict();
		}
	}

    private function jqueryNoConflict()
	{
		if ((int) $this->params->get('jquery_no_conflict_load', 0) === 1) {
			$this->addScript($this->js . 'jquery/jquery.noconflict.js');
		}
	}

    private function mootools()
	{
		global $mainframe;

		$document = &JFactory::getDocument();

		if ((int) $this->params->get('mootools_load', 0) == 1) return false;
		if ($mainframe->isAdmin()) return false;
		$headerstuff = $document->getHeadData();
		$scripts = $headerstuff['scripts'];
		$headerstuff['scripts'] = array();

		foreach($scripts as $url=>$type) {
			if (strpos($url, 'mootools.js') === false) {
				$headerstuff['scripts'][$url] = $type;
			}
		}
		$document->setHeadData($headerstuff);
	}

    private function caption()
	{
		global $mainframe;

		$document = &JFactory::getDocument();

		if ((int) $this->params->get('caption_load', 0) == 1) return false;
		if ($mainframe->isAdmin()) return false;
		$headerstuff = $document->getHeadData();
		$scripts = $headerstuff['scripts'];
		$headerstuff['scripts'] = array();

		foreach($scripts as $url=>$type) {
			if (strpos($url, 'caption.js') === false) {
				$headerstuff['scripts'][$url] = $type;
			}
		}
		$document->setHeadData($headerstuff);
	}

	public function onAfterRoute()
	{
		$this->jquery();
	}

	public function onAfterDispatch()
	{
		$this->mootools();
		$this->caption();
	}

    /**
     * Check if the library will be loaded
     *
     * @param $library
     * @return boolean
     */
    private function enableLibrary($library = null)
	{
    	global $mainframe;

    	// prevent empty library
    	if (!$library) return false;
		if ((int) $this->params->get($library . '_load', 0) == 0) return false;
		$load = false;

		$where = (int)$this->params->get($library . '_where', 0);
		switch ($where)
		{
			case 0:
				if ($mainframe->isSite()) $load = true;
				break;
			case 1:
				if ($mainframe->isAdmin()) $load = true;
				break;
			case 2:
				$load = true;
				break;
		}

		return $load;
    }

}