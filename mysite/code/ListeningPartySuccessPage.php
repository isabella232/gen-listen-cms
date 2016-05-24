<?php
/**
 * Class ListeningPartyFormPage
 *
 * Render the success page for registering for a Generation Listen Listening Party
 *
 */
class ListeningPartySuccessPage extends Page
{
    static $description = "Listening Party Form Success";

    private static $db = array(
        'SuccessPageHeader' => 'Text',
        'SuccessPageText' => 'HTMLText'
    );
    public function getCMSFields() {
        $fields = parent::getCMSFields();
        $fields->addFieldToTab('Root.Main', new TextField('SuccessPageHeader', 'Success Page Header'));
        $fields->addFieldToTab('Root.Main', new HTMLEditorField('SuccessPageText', 'Success Page Text'));
        return $fields;
    }
}


class ListeningPartySuccessPage_Controller extends Page_Controller
{

}
