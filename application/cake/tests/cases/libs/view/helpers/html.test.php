<?php
/* SVN FILE: $Id: html.test.php 7295 2008-06-27 08:17:02Z gwoo $ */
/**
 * Short description for file.
 *
 * Long description for file
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) Tests <https://trac.cakephp.org/wiki/Developement/TestSuite>
 * Copyright 2006-2008, Cake Software Foundation, Inc.
 *								1785 E. Sahara Avenue, Suite 490-204
 *								Las Vegas, Nevada 89104
 *
 *  Licensed under The Open Group Test Suite License
 *  Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright		Copyright 2006-2008, Cake Software Foundation, Inc.
 * @link				https://trac.cakephp.org/wiki/Developement/TestSuite CakePHP(tm) Tests
 * @package			cake.tests
 * @subpackage		cake.tests.cases.libs.view.helpers
 * @since			CakePHP(tm) v 1.2.0.4206
 * @version			$Revision: 7295 $
 * @modifiedby		$LastChangedBy: gwoo $
 * @lastmodified	$Date: 2008-06-27 10:17:02 +0200 (Fr, 27 Jun 2008) $
 * @license			http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
App::import('Core', array('Helper', 'AppHelper', 'ClassRegistry', 'Controller', 'Model'));
App::import('Helper', array('Html', 'Form'));
/**
 * TheHtmlTestController class
 * 
 * @package              cake
 * @subpackage           cake.tests.cases.libs.view.helpers
 */
class TheHtmlTestController extends Controller {
/**
 * name property
 * 
 * @var string 'TheTest'
 * @access public
 */
	var $name = 'TheTest';
/**
 * uses property
 * 
 * @var mixed null
 * @access public
 */
	var $uses = null;
}
/**
 * HtmlHelperTest class
 * 
 * @package              cake
 * @subpackage           cake.tests.cases.libs.view.helpers
 */
class HtmlHelperTest extends CakeTestCase {
/**
 * html property
 * 
 * @var mixed null
 * @access public
 */
	var $html = null;
/**
 * setUp method
 * 
 * @access public
 * @return void
 */
	function setUp() {
		$this->Html =& new HtmlHelper();
		$view =& new View(new TheHtmlTestController());
		ClassRegistry::addObject('view', $view);
	}
/**
 * testDocType method
 * 
 * @access public
 * @return void
 */
	function testDocType() {
		$result = $this->Html->docType();
		$expected = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
		$this->assertEqual($result, $expected);

		$result = $this->Html->docType('html4-strict');
		$expected = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">';
		$this->assertEqual($result, $expected);

		$this->assertNull($this->Html->docType('non-existing-doctype'));
	}
/**
 * testLink method
 * 
 * @access public
 * @return void
 */
	function testLink() {
		$result = $this->Html->link('/home');
		$expected = array('a' => array('href' => '/home'), 'preg:/\/home/', '/a');
		$this->assertTags($result, $expected);

		$result = $this->Html->link('Home', '/home', array('confirm' => 'Are you sure you want to do this?'));
		$expected = array(
			'a' => array('href' => '/home', 'onclick' => 'return confirm(&#039;Are you sure you want to do this?&#039;);'),
			'Home',
			'/a'
		);
		$this->assertTags($result, $expected, true);

		$result = $this->Html->link('Home', '/home', array('default' => false));
		$expected = array(
			'a' => array('href' => '/home', 'onclick' => 'event.returnValue = false; return false;'),
			'Home',
			'/a'
		);
		$this->assertTags($result, $expected);

		$result = $this->Html->link('Next >', '#');
		$expected = array(
			'a' => array('href' => '#'),
			'Next &gt;',
			'/a'
		);
		$this->assertTags($result, $expected);

		$result = $this->Html->link('Next >', '#', array('escape' => true));
		$expected = array(
			'a' => array('href' => '#'),
			'Next &gt;',
			'/a'
		);
		$this->assertTags($result, $expected);

		$result = $this->Html->link('Next >', '#', array('escape' => 'utf-8'));
		$expected = array(
			'a' => array('href' => '#'),
			'Next &gt;',
			'/a'
		);
		$this->assertTags($result, $expected);

		$result = $this->Html->link('Next >', '#', array('escape' => false));
		$expected = array(
			'a' => array('href' => '#'),
			'Next >',
			'/a'
		);
		$this->assertTags($result, $expected);

		$result = $this->Html->link($this->Html->image('test.gif'), '#', array(), false, false, false);
		$expected = array(
			'a' => array('href' => '#'),
			'img' => array('src' => 'img/test.gif', 'alt' => ''),
			'/a'
		);
		$this->assertTags($result, $expected);

		$result = $this->Html->image('test.gif', array('url' => '#'));
		$expected = array(
			'a' => array('href' => '#'),
			'img' => array('src' => 'img/test.gif', 'alt' => ''),
			'/a'
		);
		$this->assertTags($result, $expected);
	}
/**
 * testImageTag method
 * 
 * @access public
 * @return void
 */
	function testImageTag() {
		$result = $this->Html->image('test.gif');
		$this->assertTags($result, array('img' => array('src' => 'img/test.gif', 'alt' => '')));

		$result = $this->Html->image('http://google.com/logo.gif');
		$this->assertTags($result, array('img' => array('src' => 'http://google.com/logo.gif', 'alt' => '')));

		$result = $this->Html->image(array('controller' => 'test', 'action' => 'view', 1, 'ext' => 'gif'));
		$this->assertTags($result, array('img' => array('src' => '/test/view/1.gif', 'alt' => '')));

		$result = $this->Html->image('/test/view/1.gif');
		$this->assertTags($result, array('img' => array('src' => '/test/view/1.gif', 'alt' => '')));

		Configure::write('Asset.timestamp', true);
		$result = $this->Html->image('logo.gif');
		$this->assertTags($result, array('img' => array('src' => 'preg:/img\/logo\.gif\?\d*/', 'alt' => '')));
		Configure::write('Asset.timestamp', false);
	}
/**
 * testStyle method
 * 
 * @access public
 * @return void
 */
	function testStyle() {
		$result = $this->Html->style(array('display'=> 'none', 'margin'=>'10px'));
		$expected = 'display:none; margin:10px;';
		$this->assertPattern('/^display\s*:\s*none\s*;\s*margin\s*:\s*10px\s*;?$/', $expected);

		$result = $this->Html->style(array('display'=> 'none', 'margin'=>'10px'), false);
		$lines = explode("\n", $result);
		$this->assertPattern('/^\s*display\s*:\s*none\s*;\s*$/', $lines[0]);
		$this->assertPattern('/^\s*margin\s*:\s*10px\s*;?$/', $lines[1]);
	}
/**
 * testCssLink method
 * 
 * @access public
 * @return void
 */
	function testCssLink() {
		$result = $this->Html->css('screen');
		$expected = array(
			'link' => array('rel' => 'stylesheet', 'type' => 'text/css', 'href' => 'preg:/.*css\/screen\.css/')
		);
		$this->assertTags($result, $expected);

		$result = $this->Html->css('screen.css');
		$this->assertTags($result, $expected);

		$result = $this->Html->css('screen.css?1234');
		$expected['link']['href'] = 'preg:/.*css\/screen\.css\?1234/';
		$this->assertTags($result, $expected);

		$result = $this->Html->css('http://whatever.com/screen.css?1234');
		$expected['link']['href'] = 'preg:/http:\/\/.*\/screen\.css\?1234/';
		$this->assertTags($result, $expected);

		Configure::write('Asset.timestamp', true);
		$result = $this->Html->css('cake.generic');
		$expected['link']['href'] = 'preg:/.*css\/cake\.generic\.css\?[0-9]+/';
		$this->assertTags($result, $expected);

		$debug = Configure::read('debug');
		Configure::write('debug', 0);
		$result = $this->Html->css('cake.generic');
		$expected['link']['href'] = 'preg:/.*css\/cake\.generic\.css/';
		$this->assertTags($result, $expected);

		Configure::write('Asset.timestamp', 'force');
		$result = $this->Html->css('cake.generic');
		$expected['link']['href'] = 'preg:/.*css\/cake\.generic\.css\?[0-9]+/';
		$this->assertTags($result, $expected);

		Configure::write('Asset.timestamp', false);
		Configure::write('debug', $debug);

		Configure::write('Asset.filter.css', 'css.php');
		$result = $this->Html->css('cake.generic');
		$expected['link']['href'] = 'preg:/.*ccss\/cake\.generic\.css/';
		$this->assertTags($result, $expected);
		Configure::write('Asset.filter.css', false);

		$result = explode("\n", trim($this->Html->css(array('cake.generic', 'vendor.generic'))));
		$expected['link']['href'] = 'preg:/.*css\/cake\.generic\.css/';
		$this->assertTags($result[0], $expected);
		$expected['link']['href'] = 'preg:/.*css\/vendor\.generic\.css/';
		$this->assertTags($result[1], $expected);
		$this->assertEqual(count($result), 2);
	}
/**
 * testCharsetTag method
 * 
 * @access public
 * @return void
 */
	function testCharsetTag() {
		Configure::write('App.encoding', null);
		$result = $this->Html->charset();
		$this->assertTags($result, array('meta' => array('http-equiv' => 'Content-Type', 'content' => 'text/html; charset=utf-8')));

		Configure::write('App.encoding', 'ISO-8859-1');
		$result = $this->Html->charset();
		$this->assertTags($result, array('meta' => array('http-equiv' => 'Content-Type', 'content' => 'text/html; charset=iso-8859-1')));

		$result = $this->Html->charset('UTF-7');
		$this->assertTags($result, array('meta' => array('http-equiv' => 'Content-Type', 'content' => 'text/html; charset=UTF-7')));
	}
/**
 * testBreadcrumb method
 * 
 * @access public
 * @return void
 */
	function testBreadcrumb() {
		$this->Html->addCrumb('First', '#first');
		$this->Html->addCrumb('Second', '#second');
		$this->Html->addCrumb('Third', '#third');

		$result = $this->Html->getCrumbs();
		$expected = array(
			array('a' => array('href' => '#first')),
			'First',
			'/a',
			'&raquo;',
			array('a' => array('href' => '#second')),
			'Second',
			'/a',
			'&raquo;',
			array('a' => array('href' => '#third')),
			'Third',
			'/a',
		);
		$this->assertTags($result, $expected);

		$result = $this->Html->getCrumbs(' &gt; ');
		$expected = array(
			array('a' => array('href' => '#first')),
			'First',
			'/a',
			' &gt; ',
			array('a' => array('href' => '#second')),
			'Second',
			'/a',
			' &gt; ',
			array('a' => array('href' => '#third')),
			'Third',
			'/a',
		);
		$this->assertTags($result, $expected);

		$this->assertPattern('/^<a[^<>]+>First<\/a> &gt; <a[^<>]+>Second<\/a> &gt; <a[^<>]+>Third<\/a>$/', $result);
		$this->assertPattern('/<a\s+href=["\']+\#first["\']+[^<>]*>First<\/a>/', $result);
		$this->assertPattern('/<a\s+href=["\']+\#second["\']+[^<>]*>Second<\/a>/', $result);
		$this->assertPattern('/<a\s+href=["\']+\#third["\']+[^<>]*>Third<\/a>/', $result);
		$this->assertNoPattern('/<a[^<>]+[^href]=[^<>]*>/', $result);

		$this->Html->addCrumb('Fourth', null);

		$result = $this->Html->getCrumbs();
		$expected = array(
			array('a' => array('href' => '#first')),
			'First',
			'/a',
			'&raquo;',
			array('a' => array('href' => '#second')),
			'Second',
			'/a',
			'&raquo;',
			array('a' => array('href' => '#third')),
			'Third',
			'/a',
			'&raquo;',
			'Fourth'
		);
		$this->assertTags($result, $expected);
	}
/**
 * testNestedList method
 * 
 * @access public
 * @return void
 */
	function testNestedList() {
		$list = array(
			'Item 1',
			'Item 2' => array(
				'Item 2.1'
			),
			'Item 3',
			'Item 4' => array(
				'Item 4.1',
				'Item 4.2',
				'Item 4.3' => array(
					'Item 4.3.1',
					'Item 4.3.2'
				)
			),
			'Item 5' => array(
				'Item 5.1',
				'Item 5.2'
			)
		);

		$result = $this->Html->nestedList($list);
		$expected = array(
			'<ul',
			'<li', 'Item 1', '/li',
			'<li', 'Item 2',
			'<ul', '<li', 'Item 2.1', '/li', '/ul',
			'/li',
			'<li', 'Item 3', '/li',
			'<li', 'Item 4',
			'<ul',
			'<li', 'Item 4.1', '/li',
			'<li', 'Item 4.2', '/li',
			'<li', 'Item 4.3',
			'<ul',
			'<li', 'Item 4.3.1', '/li',
			'<li', 'Item 4.3.2', '/li',
			'/ul',
			'/li',
			'/ul',
			'/li',
			'<li', 'Item 5',
			'<ul',
			'<li', 'Item 5.1', '/li',
			'<li', 'Item 5.2', '/li',
			'/ul',
			'/li',
			'/ul'
		);
		$this->assertTags($result, $expected);

		$result = $this->Html->nestedList($list, null);
		$expected = array(
			'<ul',
			'<li', 'Item 1', '/li',
			'<li', 'Item 2',
			'<ul', '<li', 'Item 2.1', '/li', '/ul',
			'/li',
			'<li', 'Item 3', '/li',
			'<li', 'Item 4',
			'<ul',
			'<li', 'Item 4.1', '/li',
			'<li', 'Item 4.2', '/li',
			'<li', 'Item 4.3',
			'<ul',
			'<li', 'Item 4.3.1', '/li',
			'<li', 'Item 4.3.2', '/li',
			'/ul',
			'/li',
			'/ul',
			'/li',
			'<li', 'Item 5',
			'<ul',
			'<li', 'Item 5.1', '/li',
			'<li', 'Item 5.2', '/li',
			'/ul',
			'/li',
			'/ul'
		);
		$this->assertTags($result, $expected);

		$result = $this->Html->nestedList($list, array(), array(), 'ol');
		$expected = array(
			'<ol',
			'<li', 'Item 1', '/li',
			'<li', 'Item 2',
			'<ol', '<li', 'Item 2.1', '/li', '/ol',
			'/li',
			'<li', 'Item 3', '/li',
			'<li', 'Item 4',
			'<ol',
			'<li', 'Item 4.1', '/li',
			'<li', 'Item 4.2', '/li',
			'<li', 'Item 4.3',
			'<ol',
			'<li', 'Item 4.3.1', '/li',
			'<li', 'Item 4.3.2', '/li',
			'/ol',
			'/li',
			'/ol',
			'/li',
			'<li', 'Item 5',
			'<ol',
			'<li', 'Item 5.1', '/li',
			'<li', 'Item 5.2', '/li',
			'/ol',
			'/li',
			'/ol'
		);
		$this->assertTags($result, $expected);

		$result = $this->Html->nestedList($list, 'ol');
		$expected = array(
			'<ol',
			'<li', 'Item 1', '/li',
			'<li', 'Item 2',
			'<ol', '<li', 'Item 2.1', '/li', '/ol',
			'/li',
			'<li', 'Item 3', '/li',
			'<li', 'Item 4',
			'<ol',
			'<li', 'Item 4.1', '/li',
			'<li', 'Item 4.2', '/li',
			'<li', 'Item 4.3',
			'<ol',
			'<li', 'Item 4.3.1', '/li',
			'<li', 'Item 4.3.2', '/li',
			'/ol',
			'/li',
			'/ol',
			'/li',
			'<li', 'Item 5',
			'<ol',
			'<li', 'Item 5.1', '/li',
			'<li', 'Item 5.2', '/li',
			'/ol',
			'/li',
			'/ol'
		);
		$this->assertTags($result, $expected);

		$result = $this->Html->nestedList($list, array('class'=>'list'));
		$expected = array(
			array('ul' => array('class' => 'list')),
			'<li', 'Item 1', '/li',
			'<li', 'Item 2',
			array('ul' => array('class' => 'list')), '<li', 'Item 2.1', '/li', '/ul',
			'/li',
			'<li', 'Item 3', '/li',
			'<li', 'Item 4',
			array('ul' => array('class' => 'list')),
			'<li', 'Item 4.1', '/li',
			'<li', 'Item 4.2', '/li',
			'<li', 'Item 4.3',
			array('ul' => array('class' => 'list')),
			'<li', 'Item 4.3.1', '/li',
			'<li', 'Item 4.3.2', '/li',
			'/ul',
			'/li',
			'/ul',
			'/li',
			'<li', 'Item 5',
			array('ul' => array('class' => 'list')),
			'<li', 'Item 5.1', '/li',
			'<li', 'Item 5.2', '/li',
			'/ul',
			'/li',
			'/ul'
		);
		$this->assertTags($result, $expected);

		$result = $this->Html->nestedList($list, array(), array('class' => 'item'));
		$expected = array(
			'<ul',
			array('li' => array('class' => 'item')), 'Item 1', '/li',
			array('li' => array('class' => 'item')), 'Item 2',
			'<ul', array('li' => array('class' => 'item')), 'Item 2.1', '/li', '/ul',
			'/li',
			array('li' => array('class' => 'item')), 'Item 3', '/li',
			array('li' => array('class' => 'item')), 'Item 4',
			'<ul',
			array('li' => array('class' => 'item')), 'Item 4.1', '/li',
			array('li' => array('class' => 'item')), 'Item 4.2', '/li',
			array('li' => array('class' => 'item')), 'Item 4.3',
			'<ul',
			array('li' => array('class' => 'item')), 'Item 4.3.1', '/li',
			array('li' => array('class' => 'item')), 'Item 4.3.2', '/li',
			'/ul',
			'/li',
			'/ul',
			'/li',
			array('li' => array('class' => 'item')), 'Item 5',
			'<ul',
			array('li' => array('class' => 'item')), 'Item 5.1', '/li',
			array('li' => array('class' => 'item')), 'Item 5.2', '/li',
			'/ul',
			'/li',
			'/ul'
		);
		$this->assertTags($result, $expected);

		$result = $this->Html->nestedList($list, array(), array('even' => 'even', 'odd' => 'odd'));
		$expected = array(
			'<ul',
			array('li' => array('class' => 'odd')), 'Item 1', '/li',
			array('li' => array('class' => 'even')), 'Item 2',
			'<ul', array('li' => array('class' => 'odd')), 'Item 2.1', '/li', '/ul',
			'/li',
			array('li' => array('class' => 'odd')), 'Item 3', '/li',
			array('li' => array('class' => 'even')), 'Item 4',
			'<ul',
			array('li' => array('class' => 'odd')), 'Item 4.1', '/li',
			array('li' => array('class' => 'even')), 'Item 4.2', '/li',
			array('li' => array('class' => 'odd')), 'Item 4.3',
			'<ul',
			array('li' => array('class' => 'odd')), 'Item 4.3.1', '/li',
			array('li' => array('class' => 'even')), 'Item 4.3.2', '/li',
			'/ul',
			'/li',
			'/ul',
			'/li',
			array('li' => array('class' => 'odd')), 'Item 5',
			'<ul',
			array('li' => array('class' => 'odd')), 'Item 5.1', '/li',
			array('li' => array('class' => 'even')), 'Item 5.2', '/li',
			'/ul',
			'/li',
			'/ul'
		);
		$this->assertTags($result, $expected);

		$result = $this->Html->nestedList($list, array('class'=>'list'), array('class' => 'item'));
		$expected = array(
			array('ul' => array('class' => 'list')),
			array('li' => array('class' => 'item')), 'Item 1', '/li',
			array('li' => array('class' => 'item')), 'Item 2',
			array('ul' => array('class' => 'list')), array('li' => array('class' => 'item')), 'Item 2.1', '/li', '/ul',
			'/li',
			array('li' => array('class' => 'item')), 'Item 3', '/li',
			array('li' => array('class' => 'item')), 'Item 4',
			array('ul' => array('class' => 'list')),
			array('li' => array('class' => 'item')), 'Item 4.1', '/li',
			array('li' => array('class' => 'item')), 'Item 4.2', '/li',
			array('li' => array('class' => 'item')), 'Item 4.3',
			array('ul' => array('class' => 'list')),
			array('li' => array('class' => 'item')), 'Item 4.3.1', '/li',
			array('li' => array('class' => 'item')), 'Item 4.3.2', '/li',
			'/ul',
			'/li',
			'/ul',
			'/li',
			array('li' => array('class' => 'item')), 'Item 5',
			array('ul' => array('class' => 'list')),
			array('li' => array('class' => 'item')), 'Item 5.1', '/li',
			array('li' => array('class' => 'item')), 'Item 5.2', '/li',
			'/ul',
			'/li',
			'/ul'
		);
		$this->assertTags($result, $expected);
	}
/**
 * testMeta method
 * 
 * @access public
 * @return void
 */
	function testMeta() {
		$result = $this->Html->meta('this is an rss feed', array('controller' => 'posts', 'ext' => 'rss'));
		$this->assertTags($result, array('link' => array('href' => 'preg:/.*\/posts\.rss/', 'type' => 'application/rss+xml', 'rel' => 'alternate', 'title' => 'this is an rss feed')));

		$result = $this->Html->meta('rss', array('controller' => 'posts', 'ext' => 'rss'), array('title' => 'this is an rss feed'));
		$this->assertTags($result, array('link' => array('href' => 'preg:/.*\/posts\.rss/', 'type' => 'application/rss+xml', 'rel' => 'alternate', 'title' => 'this is an rss feed')));

		$result = $this->Html->meta('atom', array('controller' => 'posts', 'ext' => 'xml'));
		$this->assertTags($result, array('link' => array('href' => 'preg:/.*\/posts\.xml/', 'type' => 'application/atom+xml', 'title' => 'atom')));
		
		$result = $this->Html->meta('non-existing');
		$this->assertTags($result, array('<meta'));

		$result = $this->Html->meta('non-existing', '/posts.xpp');
		$this->assertTags($result, array('link' => array('href' => 'preg:/.*\/posts\.xpp/', 'type' => 'application/rss+xml', 'rel' => 'alternate', 'title' => 'non-existing')));

		$result = $this->Html->meta('non-existing', '/posts.xpp', array('type' => 'atom'));
		$this->assertTags($result, array('link' => array('href' => 'preg:/.*\/posts\.xpp/', 'type' => 'application/atom+xml', 'title' => 'non-existing')));

		$result = $this->Html->meta('atom', array('controller' => 'posts', 'ext' => 'xml'), array('link' => '/articles.rss'));
		$this->assertTags($result, array('link' => array('href' => 'preg:/.*\/articles\.rss/', 'type' => 'application/atom+xml', 'title' => 'atom')));

		$result = $this->Html->meta(array('link' => 'favicon.ico', 'rel' => 'icon'));
		$expected = array(
			'link' => array('href' => 'preg:/.*favicon\.ico/', 'rel' => 'icon'),
			array('link' => array('href' => 'preg:/.*favicon\.ico/', 'rel' => 'shortcut icon'))
		);
		$this->assertTags($result, $expected);

		$result = $this->Html->meta('icon', 'favicon.ico');
		$expected = array(
			'link' => array('href' => 'preg:/.*favicon\.ico/', 'type' => 'image/x-icon', 'rel' => 'icon'),
			array('link' => array('href' => 'preg:/.*favicon\.ico/', 'type' => 'image/x-icon', 'rel' => 'shortcut icon'))
		);
		$this->assertTags($result, $expected);

		$result = $this->Html->meta('keywords', 'these, are, some, meta, keywords');
		$this->assertTags($result, array('meta' => array('name' => 'keywords', 'content' => 'these, are, some, meta, keywords')));

		$result = $this->Html->meta('description', 'this is the meta description');
		$this->assertTags($result, array('meta' => array('name' => 'description', 'content' => 'this is the meta description')));

		$result = $this->Html->meta(array('name' => 'ROBOTS', 'content' => 'ALL'));
		$this->assertTags($result, array('meta' => array('name' => 'ROBOTS', 'content' => 'ALL')));

		$this->assertNull($this->Html->meta(array('name' => 'ROBOTS', 'content' => 'ALL'), null, array(), false));
		$view =& ClassRegistry::getObject('view');
		$result = $view->__scripts[0];
		$this->assertTags($result, array('meta' => array('name' => 'ROBOTS', 'content' => 'ALL')));
	}
/**
 * testTableHeaders method
 * 
 * @access public
 * @return void
 */
	function testTableHeaders() {
		$result = $this->Html->tableHeaders(array('ID', 'Name', 'Date'));
		$expected = array('<tr', '<th', 'ID', '/th', '<th', 'Name', '/th', '<th', 'Date', '/th', '/tr');
		$this->assertTags($result, $expected);
	}
/**
 * testTableCells method
 * 
 * @access public
 * @return void
 */
	function testTableCells() {
		$tr = array(
			'td content 1',
			array('td content 2', array("width" => "100px")),
			array('td content 3', "width=100px")
		);
		$result = $this->Html->tableCells($tr);
		$expected = array(
			'<tr',
			'<td', 'td content 1', '/td',
			array('td' => array('width' => '100px')), 'td content 2', '/td',
			array('td' => array('width' => 'preg:/100px/')), 'td content 3', '/td',
			'/tr'
		);
		$this->assertTags($result, $expected);

		$tr = array('td content 1', 'td content 2', 'td content 3');
		$result = $this->Html->tableCells($tr, null, null, true);
		$expected = array(
			'<tr',
			array('td' => array('class' => 'column-1')), 'td content 1', '/td',
			array('td' => array('class' => 'column-2')), 'td content 2', '/td',
			array('td' => array('class' => 'column-3')), 'td content 3', '/td',
			'/tr'
		);
		$this->assertTags($result, $expected);

		$tr = array('td content 1', 'td content 2', 'td content 3');
		$result = $this->Html->tableCells($tr, true);
		$expected = array(
			'<tr',
			array('td' => array('class' => 'column-1')), 'td content 1', '/td',
			array('td' => array('class' => 'column-2')), 'td content 2', '/td',
			array('td' => array('class' => 'column-3')), 'td content 3', '/td',
			'/tr'
		);
		$this->assertTags($result, $expected);
		
		$tr = array(
			array('td content 1', 'td content 2', 'td content 3'),
			array('td content 1', 'td content 2', 'td content 3'),
			array('td content 1', 'td content 2', 'td content 3')
		);
		$result = $this->Html->tableCells($tr, array('class' => 'odd'), array('class' => 'even'));
		$expected = "<tr class=\"even\"><td>td content 1</td> <td>td content 2</td> <td>td content 3</td></tr>\n<tr class=\"odd\"><td>td content 1</td> <td>td content 2</td> <td>td content 3</td></tr>\n<tr class=\"even\"><td>td content 1</td> <td>td content 2</td> <td>td content 3</td></tr>";
		$this->assertEqual($result, $expected);
		
		$tr = array(
			array('td content 1', 'td content 2', 'td content 3'),
			array('td content 1', 'td content 2', 'td content 3'),
			array('td content 1', 'td content 2', 'td content 3'),
			array('td content 1', 'td content 2', 'td content 3')
		);
		$result = $this->Html->tableCells($tr, array('class' => 'odd'), array('class' => 'even'));
		$expected = "<tr class=\"odd\"><td>td content 1</td> <td>td content 2</td> <td>td content 3</td></tr>\n<tr class=\"even\"><td>td content 1</td> <td>td content 2</td> <td>td content 3</td></tr>\n<tr class=\"odd\"><td>td content 1</td> <td>td content 2</td> <td>td content 3</td></tr>\n<tr class=\"even\"><td>td content 1</td> <td>td content 2</td> <td>td content 3</td></tr>";
		$this->assertEqual($result, $expected);
		
		$tr = array(
			array('td content 1', 'td content 2', 'td content 3'),
			array('td content 1', 'td content 2', 'td content 3'),
			array('td content 1', 'td content 2', 'td content 3')
		);
		$this->Html->tableCells($tr, array('class' => 'odd'), array('class' => 'even'));
		$result = $this->Html->tableCells($tr, array('class' => 'odd'), array('class' => 'even'), false, false);
		$expected = "<tr class=\"odd\"><td>td content 1</td> <td>td content 2</td> <td>td content 3</td></tr>\n<tr class=\"even\"><td>td content 1</td> <td>td content 2</td> <td>td content 3</td></tr>\n<tr class=\"odd\"><td>td content 1</td> <td>td content 2</td> <td>td content 3</td></tr>";
		$this->assertEqual($result, $expected);
	}
/**
 * testTag method
 * 
 * @access public
 * @return void
 */
	function testTag() {
		$result = $this->Html->tag('div');
		$this->assertTags($result, '<div');

		$result = $this->Html->tag('div', 'text');
		$this->assertTags($result, '<div', 'text', '/div');

		$result = $this->Html->tag('div', '<text>', array('class' => 'class-name'), true);
		$this->assertTags($result, array('div' => array('class' => 'class-name'), '&lt;text&gt;', '/div'));

		$result = $this->Html->tag('div', '<text>', 'class-name', true);
		$this->assertTags($result, array('div' => array('class' => 'class-name'), '&lt;text&gt;', '/div'));
	}
/**
 * testDiv method
 * 
 * @access public
 * @return void
 */
	function testDiv() {
		$result = $this->Html->div('class-name');
		$this->assertTags($result, array('div' => array('class' => 'class-name')));

		$result = $this->Html->div('class-name', 'text');
		$this->assertTags($result, array('div' => array('class' => 'class-name'), 'text', '/div'));

		$result = $this->Html->div('class-name', '<text>', array(), true);
		$this->assertTags($result, array('div' => array('class' => 'class-name'), '&lt;text&gt;', '/div'));
	}

/**
 * testPara method
 * 
 * @access public
 * @return void
 */
	function testPara() {
		$result = $this->Html->para('class-name', '');
		$this->assertTags($result, array('p' => array('class' => 'class-name')));

		$result = $this->Html->para('class-name', 'text');
		$this->assertTags($result, array('p' => array('class' => 'class-name'), 'text', '/p'));

		$result = $this->Html->para('class-name', '<text>', array(), true);
		$this->assertTags($result, array('p' => array('class' => 'class-name'), '&lt;text&gt;', '/p'));
	}
/**
 * tearDown method
 * 
 * @access public
 * @return void
 */
	function tearDown() {
		unset($this->Html);
	}
}
?>