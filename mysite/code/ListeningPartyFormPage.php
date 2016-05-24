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
            $this->createStationPickerField("Search station name, location, or zip code.")
        );

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
        $stationId = $data['stationId'];


        try
        {
            $user = new NPRUser($email, intval($stationId));
            $user->submit();
        }
        catch (Exception $e)
        {
            error_log("Exception: $e");
            if (!empty($errorMsg))
            {
                $form->addErrorMessage("Error", $errorMsg, "bad");
            }
            else
            {
                $form->addErrorMessage("Error", "Hm. Looks like things are busy at the moment.", "bad", $escapeHtml = false);
            }
            return $this->redirectBack();
        }

        return $this->redirect(Director::baseURL() . 'success');
    }

    private function createStationPickerField($placeholder = null)
    {
        $field = new TextField('PrimaryStationPicker', 'Search for your favorite NPR Member station');

        if (!empty($placeholder)) {
            $field->setAttribute('placeholder', $placeholder);
        }
        return $field;
    }
}
