<?php
/**
 * Class ListeningPartyFormPage
 *
 * Render the form page for registering for a Generation Listen Listening Party
 *
 */
class ListeningPartyFormPage extends Page
{
    static $description = "Listening Party Form";

    private static $db = array(
        'BannerText' => 'HTMLText',
        'BannerGraphic' => 'HTMLText'
    );
    public function getCMSFields() {
        $fields = parent::getCMSFields();
        $fields->addFieldToTab('Root.Main', new HTMLEditorField('BannerText', 'Banner Text '));
        $fields->addFieldToTab('Root.Main', new HTMLEditorField('BannerGraphic', 'Banner Graphic'));
        return $fields;
    }
}


class ListeningPartyFormPage_Controller extends Page_Controller
{
    /* Define the custom forms */
    public static $allowed_actions = array(
        'ListeningPartyForm'
    );

    /**
     * Create the form for a user to submit their listening party info
     * @return Form
     */
    public function ListeningPartyForm()
    {
        $fields = new FieldList(
            TextField::create('firstName', 'First Name'),
            TextField::create('lastName', 'Last Name'),
            TextField::create('email', 'Email'),
            TextField::create('zipCode', 'Zip code'),
            TextField::create('hostingDate', 'I plan to host my party on'),
            TextField::create('twitter', 'Twitter'),
            TextField::create('instagram', 'Instagram'),
            TextField::create('memberStation', 'Local NPR member station'));

        $actions = new FieldList(
            new FormAction('doSubmit', 'Submit')
        );

        $form = new Form($this, 'ListeningPartyForm', $fields, $actions, null);

        return $form;
    }


    /**
     * Submit the form
     *
     * @param $data
     * @param Form $form
     * @return mixed
     */
    public function doEntrySubmit($data, Form $form)
    {
        // contact info
        $firstName = $data['firstName'];
        $lastName = $data['$lastName'];
        $email = $data['email'];
        $zipCode = $data['zipCode'];
        $hostingDate = $data['hostingDate'];
        $twitter = $data['twitter'];
        $instagram = $data['instagram'];
        $memberStation = $data['memberStation'];

        return $this->redirect(Director::baseURL() . 'success');
    }
}
