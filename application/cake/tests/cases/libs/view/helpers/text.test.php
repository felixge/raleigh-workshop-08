<?php
/* SVN FILE: $Id: text.test.php 7348 2008-07-21 02:40:58Z mark_story $ */
/**
 * Short description for file.
 *
 * Long description for file
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) Tests <https://trac.cakephp.org/wiki/Developement/TestSuite>
 * Copyright 2005-2008, Cake Software Foundation, Inc.
 *								1785 E. Sahara Avenue, Suite 490-204
 *								Las Vegas, Nevada 89104
 *
 *  Licensed under The Open Group Test Suite License
 *  Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright		Copyright 2005-2008, Cake Software Foundation, Inc.
 * @link				https://trac.cakephp.org/wiki/Developement/TestSuite CakePHP(tm) Tests
 * @package			cake.tests
 * @subpackage		cake.tests.cases.libs.view.helpers
 * @since			CakePHP(tm) v 1.2.0.4206
 * @version			$Revision: 7348 $
 * @modifiedby		$LastChangedBy: mark_story $
 * @lastmodified	$Date: 2008-07-21 04:40:58 +0200 (Mo, 21 Jul 2008) $
 * @license			http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
App::import('Helper', 'Text');

/**
 * Short description for class.
 *
 * @package		cake.tests
 * @subpackage	cake.tests.cases.libs.view.helpers
 */
class TextTest extends CakeTestCase {
/**
 * helper property
 * 
 * @var mixed null
 * @access public
 */
	var $helper = null;
/**
 * setUp method
 * 
 * @access public
 * @return void
 */
	function setUp() {
		$this->Text = new TextHelper();
	}
/**
 * testTruncate method
 * 
 * @access public
 * @return void
 */
	function testTruncate() {
		if (!isset($this->method)) {
			$this->method = 'truncate';
		}
		$m = $this->method;
		$text1 = 'The quick brown fox jumps over the lazy dog';
		$text2 = 'Heiz&ouml;lr&uuml;cksto&szlig;abd&auml;mpfung';
		$text3 = '<b>&copy; 2005-2007, Cake Software Foundation, Inc.</b><br />written by Alexander Wegener';
		$text4 = '<img src="mypic.jpg"> This image tag is not XHTML conform!<br><hr/><b>But the following image tag should be conform <img src="mypic.jpg" alt="Me, myself and I" /></b><br />Great, or?';
		$text5 = '0<b>1<i>2<span class="myclass">3</span>4<u>5</u>6</i>7</b>8<b>9</b>0';
        $text6 = "<p><strong>Extra dates have been announced for this year's tour.</strong></p><p>Tickets for the new shows in</p>";

		$this->assertIdentical($this->Text->{$m}($text1, 15), 'The quick br...');
		$this->assertIdentical($this->Text->{$m}($text1, 15, '...', false), 'The quick...');
		$this->assertIdentical($this->Text->{$m}($text1, 100), 'The quick brown fox jumps over the lazy dog');
		$this->assertIdentical($this->Text->{$m}($text2, 10, '...'), 'Heiz&ou...');
		$this->assertIdentical($this->Text->{$m}($text2, 10, '...', false), '...');
		$this->assertIdentical($this->Text->{$m}($text3, 20), '<b>&copy; 2005-20...');
		$this->assertIdentical($this->Text->{$m}($text4, 15), '<img src="my...');
		$this->assertIdentical($this->Text->{$m}($text5, 6, ''), '0<b>1<');
		$this->assertIdentical($this->Text->{$m}($text1, 15, array('ending' => '...', 'exact' => true, 'considerHtml' => true)), 'The quick br...');
		$this->assertIdentical($this->Text->{$m}($text1, 15, '...', true, true), 'The quick br...');
		$this->assertIdentical($this->Text->{$m}($text1, 15, '...', false, true), 'The quick...');
		$this->assertIdentical($this->Text->{$m}($text2, 10, '...', true, true), 'Heiz&ouml;lr...');
		$this->assertIdentical($this->Text->{$m}($text2, 10, '...', false, true), '...');
		$this->assertIdentical($this->Text->{$m}($text3, 20, '...', true, true), '<b>&copy; 2005-2007, Cake...</b>');
		$this->assertIdentical($this->Text->{$m}($text4, 15, '...', true, true), '<img src="mypic.jpg"> This image ...');
		$this->assertIdentical($this->Text->{$m}($text4, 45, '...', true, true), '<img src="mypic.jpg"> This image tag is not XHTML conform!<br><hr/><b>But t...</b>');
		$this->assertIdentical($this->Text->{$m}($text4, 90, '...', true, true), '<img src="mypic.jpg"> This image tag is not XHTML conform!<br><hr/><b>But the following image tag should be conform <img src="mypic.jpg" alt="Me, myself and I" /></b><br />Grea...');
		$this->assertIdentical($this->Text->{$m}($text5, 6, '', true, true), '0<b>1<i>2<span class="myclass">3</span>4<u>5</u></i></b>');
		$this->assertIdentical($this->Text->{$m}($text5, 20, '', true, true), $text5);
		$this->assertIdentical($this->Text->{$m}($text6, 57, '...', false, true), "<p><strong>Extra dates have been announced for this year's...</strong></p>");

		if ($this->method == 'truncate') {
			$this->method = 'trim';
			$this->testTruncate();
		}
	}
/**
 * testHighlight method
 * 
 * @access public
 * @return void
 */
	function testHighlight() {
		$text = 'This is a test text';
		$phrases = array('This', 'text');
		$result = $this->Text->highlight($text, $phrases, '<b>\1</b>');
		$expected = '<b>This</b> is a test <b>text</b>';
		$this->assertEqual($expected, $result);

		$text = 'This is a test text';
		$phrases = null;
		$result = $this->Text->highlight($text, $phrases, '<b>\1</b>');
		$this->assertEqual($result, $text);

		$text = 'Ich saß in einem Café am Übergang';
		$expected = 'Ich <b>saß</b> in einem <b>Café</b> am <b>Übergang</b>';
		$phrases = array('saß', 'café', 'übergang');
		$result = $this->Text->highlight($text, $phrases, '<b>\1</b>');
		$this->assertEqual($result, $expected);
	}
/**
 * testHighlightConsiderHtml method
 * 
 * @access public
 * @return void
 */
	function testHighlightConsiderHtml() {
		$text1 = '<p>strongbow isn&rsquo;t real cider</p>';
		$text2 = '<p>strongbow <strong>isn&rsquo;t</strong> real cider</p>';
		$text3 = '<img src="what-a-strong-mouse.png" alt="What a strong mouse!" />';

		$this->assertEqual($this->Text->highlight($text1, 'strong', '<b>\1</b>', true), '<p><b>strong</b>bow isn&rsquo;t real cider</p>');
		$this->assertEqual($this->Text->highlight($text2, 'strong', '<b>\1</b>', true), '<p><b>strong</b>bow <strong>isn&rsquo;t</strong> real cider</p>');
		$this->assertEqual($this->Text->highlight($text3, 'strong', '<b>\1</b>', true), $text3);
	}
/**
 * testStripLinks method
 * 
 * @access public
 * @return void
 */
	function testStripLinks() {
		$text = 'This is a test text';
		$expected = 'This is a test text';
		$result = $this->Text->stripLinks($text);
		$this->assertEqual($expected, $result);

		$text = 'This is a <a href="#">test</a> text';
		$expected = 'This is a test text';
		$result = $this->Text->stripLinks($text);
		$this->assertEqual($expected, $result);

		$text = 'This <strong>is</strong> a <a href="#">test</a> <a href="#">text</a>';
		$expected = 'This <strong>is</strong> a test text';
		$result = $this->Text->stripLinks($text);
		$this->assertEqual($expected, $result);

		$text = 'This <strong>is</strong> a <a href="#">test</a> and <abbr>some</abbr> other <a href="#">text</a>';
		$expected = 'This <strong>is</strong> a test and <abbr>some</abbr> other text';
		$result = $this->Text->stripLinks($text);
		$this->assertEqual($expected, $result);
	}
/**
 * testAutoLink method
 * 
 * @access public
 * @return void
 */
	function testAutoLink() {
		$text = 'This is a test text';
		$expected = 'This is a test text';
		$result = $this->Text->autoLink($text);
		$this->assertEqual($expected, $result);

		$text = 'Text with a partial www.cakephp.org URL and test@cakephp.org email address';
		$result = $this->Text->autoLink($text);
		$expected = 'Text with a partial <a href="http://www.cakephp.org">www.cakephp.org</a> URL and <a href="mailto:test@cakephp\.org">test@cakephp\.org</a> email address';
		$this->assertPattern('#^' . $expected . '$#', $result);
	}
/**
 * testAutoLinkUrls method
 * 
 * @access public
 * @return void
 */
	function testAutoLinkUrls() {
		$text = 'This is a test text';
		$expected = 'This is a test text';
		$result = $this->Text->autoLinkUrls($text);
		$this->assertEqual($expected, $result);

		$text = 'This is a test that includes (www.cakephp.org)';
		$expected = 'This is a test that includes (<a href="http://www.cakephp.org">www.cakephp.org</a>)';
		$result = $this->Text->autoLinkUrls($text);
		$this->assertEqual($expected, $result);

		$text = 'Text with a partial www.cakephp.org URL';
		$expected = 'Text with a partial <a href="http://www.cakephp.org"\s*>www.cakephp.org</a> URL';
		$result = $this->Text->autoLinkUrls($text);
		$this->assertPattern('#^' . $expected . '$#', $result);

		$text = 'Text with a partial www.cakephp.org URL';
		$expected = 'Text with a partial <a href="http://www.cakephp.org" \s*class="link">www.cakephp.org</a> URL';
		$result = $this->Text->autoLinkUrls($text, array('class' => 'link'));
		$this->assertPattern('#^' . $expected . '$#', $result);

		$text = 'Text with a partial WWW.cakephp.org URL';
		$expected = 'Text with a partial <a href="http://www.cakephp.org"\s*>WWW.cakephp.org</a> URL';
		$result = $this->Text->autoLinkUrls($text);
		$this->assertPattern('#^' . $expected . '$#', $result);

		$text = 'Text with a partial WWW.cakephp.org &copy; URL';
		$expected = 'Text with a partial <a href="http://www.cakephp.org"\s*>WWW.cakephp.org</a> &copy; URL';
		$result = $this->Text->autoLinkUrls($text, array('escape' => false));
		$this->assertPattern('#^' . $expected . '$#', $result);

	}
/**
 * testAutoLinkEmails method
 * 
 * @access public
 * @return void
 */
	function testAutoLinkEmails() {
		$text = 'This is a test text';
		$expected = 'This is a test text';
		$result = $this->Text->autoLinkUrls($text);
		$this->assertEqual($expected, $result);

		$text = 'Text with email@example.com address';
		$expected = 'Text with <a href="mailto:email@example.com"\s*>email@example.com</a> address';
		$result = $this->Text->autoLinkEmails($text);
		$this->assertPattern('#^' . $expected . '$#', $result);

		$text = 'Text with email@example.com address';
		$expected = 'Text with <a href="mailto:email@example.com" \s*class="link">email@example.com</a> address';
		$result = $this->Text->autoLinkEmails($text, array('class' => 'link'));
		$this->assertPattern('#^' . $expected . '$#', $result);
	}
/**
 * testHighlightCaseInsensitivity method
 * 
 * @access public
 * @return void
 */
	function testHighlightCaseInsensitivity() {
		$text = 'This is a Test text';
		$expected = 'This is a <b>Test</b> text';

		$result = $this->Text->highlight($text, 'test', '<b>\1</b>');
		$this->assertEqual($expected, $result);

		$result = $this->Text->highlight($text, array('test'), '<b>\1</b>');
		$this->assertEqual($expected, $result);
	}
/**
 * testExcerpt method
 * 
 * @access public
 * @return void
 */
	function testExcerpt() {
		$text = 'This is a phrase with test text to play with';

		$expected = '...with test text...';
		$result = $this->Text->excerpt($text, 'test', 9, '...');
		$this->assertEqual($expected, $result);

		$expected = 'This is a...';
		$result = $this->Text->excerpt($text, 'not_found', 9, '...');
		$this->assertEqual($expected, $result);

		$expected = 'This is a phras...';
		$result = $this->Text->excerpt($text, null, 9, '...');
		$this->assertEqual($expected, $result);

		$expected = $text;
		$result = $this->Text->excerpt($text, null, 200, '...');
		$this->assertEqual($expected, $result);

		$expected = '...phrase...';
		$result = $this->Text->excerpt($text, 'phrase', 2, '...');
		$this->assertEqual($expected, $result);
	}
/**
 * testExcerptCaseInsensitivity method
 * 
 * @access public
 * @return void
 */
	function testExcerptCaseInsensitivity() {
		$text = 'This is a phrase with test text to play with';

		$expected = '...with test text...';
		$result = $this->Text->excerpt($text, 'TEST', 9, '...');
		$this->assertEqual($expected, $result);

		$expected = 'This is a...';
		$result = $this->Text->excerpt($text, 'NOT_FOUND', 9, '...');
		$this->assertEqual($expected, $result);
	}
/**
 * testListGeneration method
 * 
 * @access public
 * @return void
 */
	function testListGeneration() {
		$result = $this->Text->toList(array('Larry', 'Curly', 'Moe'));
		$this->assertEqual($result, 'Larry, Curly and Moe');

		$result = $this->Text->toList(array('Dusty', 'Lucky', 'Ned'), 'y');
		$this->assertEqual($result, 'Dusty, Lucky y Ned');
	}
/**
 * tearDown method
 * 
 * @access public
 * @return void
 */
	function tearDown() {
		unset($this->Text);
	}
}

?>