<?php
/* SVN FILE: $Id: xml.php 7403 2008-08-01 04:53:16Z nate $ */
/**
 * XML Helper class file.
 *
 * Simplifies the output of XML documents.
 *
 * CakePHP(tm) :  Rapid Development Framework <http://www.cakephp.org/>
 * Copyright 2005-2008, Cake Software Foundation, Inc.
 *								1785 E. Sahara Avenue, Suite 490-204
 *								Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright		Copyright 2005-2008, Cake Software Foundation, Inc.
 * @link				http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package			cake
 * @subpackage		cake.cake.libs.view.helpers
 * @since			CakePHP(tm) v 1.2
 * @version			$Revision: 7403 $
 * @modifiedby		$LastChangedBy: nate $
 * @lastmodified	$Date: 2008-08-01 06:53:16 +0200 (Fr, 01 Aug 2008) $
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 */
App::import('Core', array('Xml', 'Set'));

/**
 * XML Helper class for easy output of XML structures.
 *
 * XmlHelper encloses all methods needed while working with XML documents.
 *
 * @package		cake
 * @subpackage	cake.cake.libs.view.helpers
 */
class XmlHelper extends AppHelper {

/**
 * Default document encoding
 *
 * @access public
 * @var string
 */
	var $encoding = 'UTF-8';
/**
 * Constructor
 * @return void
 */
	function __construct() {
		parent::__construct();
		$this->Xml =& new Xml();
		$this->Xml->options(array('verifyNs' => false));
	}
/**
 * Returns an XML document header
 *
 * @param  array $attrib Header tag attributes
 * @return string XML header
 */
	function header($attrib = array()) {
		if (Configure::read('App.encoding') !== null) {
			$this->encoding = Configure::read('App.encoding');
		}

		if (is_array($attrib)) {
			$attrib = array_merge(array('encoding' => $this->encoding), $attrib);
		}
		if (is_string($attrib) && strpos($attrib, 'xml') !== 0) {
			$attrib = 'xml ' . $attrib;
		}

		return $this->output($this->Xml->header($attrib));
	}
/**
 * Adds a namespace to any documents generated
 *
 * @param  string  $name The namespace name
 * @param  string  $url  The namespace URI; can be empty if in the default namespace map
 * @return boolean False if no URL is specified, and the namespace does not exist
 *                 default namespace map, otherwise true
 * @deprecated
 * @see Xml::addNs()
 */
	function addNs($name, $url = null) {
		return $this->Xml->addNamespace($name, $url);
	}
/**
 * Removes a namespace added in addNs()
 *
 * @param  string  $name The namespace name or URI
 * @deprecated
 * @see Xml::removeNs()
 */
	function removeNs($name) {
		return $this->Xml->removeGlobalNamespace($name);
	}
/**
 * Generates an XML element
 *
 * @param  string   $name The name of the XML element
 * @param  array    $attrib The attributes of the XML element
 * @param  mixed    $content XML element content
 * @param  boolean  $endTag Whether the end tag of the element should be printed
 * @return string XML
 */
	function elem($name, $attrib = array(), $content = null, $endTag = true) {
		$namespace = null;
		if (isset($attrib['namespace'])) {
			$namespace = $attrib['namespace'];
			unset($attrib['namespace']);
		}
		$cdata = false;
		if (is_array($content) && isset($content['cdata'])) {
			$cdata = true;
			unset($content['cdata']);
		}
		if (is_array($content) && isset($content['value'])) {
			$content = $content['value'];
		}
		$children = array();
		if (is_array($content)) {
			$children = $content;
			$content = null;
		}

		$elem =& $this->Xml->createElement($name, $content, $attrib, $namespace);
		foreach ($children as $child) {
			$elem->createElement($child);
		}
		$out = $elem->toString(array('cdata' => $cdata, 'leaveOpen' => !$endTag));

		if (!$endTag) {
			$this->Xml =& $elem;
		}
		return $this->output($out);
	}
/**
 * Create closing tag for current element
 *
 * @return string
 */
	function closeElem() {
		$name = $this->Xml->name();
		if ($parent =& $this->Xml->parent()) {
			$this->Xml =& $parent;
		}
		return $this->output('</' . $name . '>');
	}
/**
 * Serializes a model resultset into XML
 *
 * @param  mixed  $data The content to be converted to XML
 * @param  array  $options The data formatting options
 * @return string A copy of $data in XML format
 */
	function serialize($data, $options = array()) {
		$data =& new Xml($data, array_merge(array('attributes' => false, 'format' => 'attributes'), $options));
		return $data->toString(array_merge(array('header' => false), $options));
	}
}

?>