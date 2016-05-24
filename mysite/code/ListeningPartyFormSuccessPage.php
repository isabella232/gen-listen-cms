<?php
/**
 * Class ListeningPartyFormSuccessPage
 *
 * Render the success page for registering for a Generation Listen Listening Party
 *
 */
class ListeningPartyFormSuccessPage extends Page
{
    static $description = "Listening Party Form Success";

    private static $db = array(
        'SuccessPageHeader' => 'Text',
        'SuccessPageText' => 'HTMLText',
        'SuccessPageButtonText' => 'Text'
    );
    public function getCMSFields() {
        $fields = parent::getCMSFields();
        $fields->removeFieldFromTab("Root.Main","Content");
        $fields->addFieldToTab('Root.Main', new TextField('SuccessPageHeader', 'Success Page Header'));
        $fields->addFieldToTab('Root.Main', new HTMLEditorField('SuccessPageText', 'Success Page Text'));
        $fields->addFieldToTab('Root.Main', new TextField('SuccessPageButtonText', 'Success Page Button Text'));
        return $fields;
    }
}


class ListeningPartyFormSuccessPage_Controller extends Page_Controller
{

}
