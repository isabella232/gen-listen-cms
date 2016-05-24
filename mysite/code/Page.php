<?php
class Page extends SiteTree {

	private static $db = array(
		'PageClass' => 'Text',
		'ShareUrl'  => 'Text',
		'ShareImageLink' => 'Text',
		'ShareTitle' => 'Text',
		'ShareText' => 'Text',
		'ShareCreator' => 'Text'
	);

	private static $has_one = array(
	);

	/**
	 * What and how the template variables are managed in the CMS
	 * @return mixed
	 */
	public function getCMSFields() {
	    $fields = parent::getCMSFields();
	    $fields->addFieldToTab('Root.Main', new TextField('PageClass', 'Page CSS Class'));
	    $fields->addFieldToTab('Root.Main', new TextField('ShareUrl', 'Share Url'));
	    $fields->addFieldToTab('Root.Main', new TextField('ShareImageLink', 'Share Image Link'));
	    $fields->addFieldToTab('Root.Main', new TextField('ShareTitle', 'Share Title'));
	    $fields->addFieldToTab('Root.Main', new TextField('ShareText', 'Share Message'));
	    $fields->addFieldToTab('Root.Main', new TextField('ShareCreator', 'Share Creator'));
	    return $fields;
	}

}
class Page_Controller extends ContentController {

	/**
	 * An array of actions that can be accessed via a request. Each array element should be an action name, and the
	 * permissions or conditions required to allow the user to access it.
	 *
	 * <code>
	 * array (
	 *     'action', // anyone can access this action
	 *     'action' => true, // same as above
	 *     'action' => 'ADMIN', // you must have ADMIN permissions to access this action
	 *     'action' => '->checkAction' // you can only access this action if $this->checkAction() returns true
	 * );
	 * </code>
	 *
	 * @var array
	 */
	private static $allowed_actions = array (
	);

	public function init() {
		parent::init();
		// You can include any CSS or JS required by your project here.
		// See: http://doc.silverstripe.org/framework/en/reference/requirements
		Requirements::javascript("framework/thirdparty/jquery/jquery.js");
	}

}
