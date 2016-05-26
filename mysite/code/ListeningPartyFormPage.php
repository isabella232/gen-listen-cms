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
        'SuccessUrl' => 'Text',
        'PrimaryInformationalText' => 'HTMLText'
    );
    public function getCMSFields() {
        $fields = parent::getCMSFields();
        $fields->removeFieldFromTab("Root.Main","Content");
        $fields->addFieldToTab('Root.Main', new TextField('SuccessUrl', 'Name of success page to redirect to'));
        $fields->addFieldToTab('Root.Main', new HTMLEditorField('PrimaryInformationalText', 'Primary Informational Text'));
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
            FieldGroup::create(
                TextField::create('firstName', 'First Name <span class="required">*</span>'),
                TextField::create('lastName', 'Last Name <span class="required">*</span>'),
                TextField::create('email', 'Email <span class="required">*</span>'),
                TextField::create('zipCode', 'Zip code'),
                DateField::create('hostingDate', 'When do you plan to host your party? <span class="required">*</span>')->setValue(SiteConfig::current_site_config()->DefaultDate)->setConfig('showcalendar', true)->setConfig('dateformat', 'MMMM dd, YYYY')->setConfig('min', '2016-06-16'),
                LiteralField::create("HeaderSocial", "<div>Where can we find you on the internet?</div>"),
                $this->createTextWithPrefillField("twitter", "Twitter", null, "@"),
                $this->createTextWithPrefillField("instagram", "Instagram", null, "@"),
                $this->createStationPickerField("Station name, location, or zip code")
            )
        );

        $actions = new FieldList(
            new FormAction('doSubmit', 'Submit')
        );

        /** @see https://github.com/sheadawson/silverstripe-zenvalidator */
        /** @var ZenValidator $validator */
        $validator = ZenValidator::create(null, true, false);

        $validator->addRequiredFields(array(
            'firstName'  => 'Please enter your first name.',
            'lastName'  => 'Please enter your last name.',
            'email'  => 'Please enter your email address.',
            'hostingDate'  => 'Please enter your hosting date."'
        ));

        $validator->setConstraint('email', Constraint_type::create('email'));
        $validator->setConstraint('zipCode', Constraint_regex::create("/^\d{5}$/")->setMessage('Please enter your 5-digit zip code, if you live in the United States.'));

        $form = new Form($this, 'ListeningPartyForm', $fields, $actions, $validator);

        // in case of error redirect, populate the form again
        if(is_array(Session::get('ListeningPartyForm')))
        {
            $form->loadDataFrom(Session::get("ListeningPartyForm"));

            // clear the data, so we don't get into a permafilled form
            Session::clear("ListeningPartyForm");
        }

        return $form;
    }


    /**
     * Submit the form
     *
     * @param $data
     * @param Form $form
     * @return mixed
     */
    public function doSubmit($data, Form $form)
    {
        // save the populated fields to the session, in case of error
        Session::set("ListeningPartyForm", $data);

        // contact info
        $firstName = $data['firstName'];
        $lastName = $data['lastName'];
        $email = $data['email'];
        $zipCode = $data['zipCode'];
        $hostingDate = $data['hostingDate'];
        $twitter = $data['twitter'];
        $instagram = $data['instagram'];
        $stationId = $data['PrimaryStation'] ? $data['PrimaryStation'] : 0;


        try
        {
            $user = new NPRUser($email, intval($stationId));
            $user->submit();

            $smcData = new SMCFormData($user->getUserId(), SiteConfig::current_site_config()->PartyName, $firstName, $lastName, $email, $zipCode, $hostingDate, $twitter, $instagram, $stationId);
            $smcData->submit();

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


        return $this->redirect(Director::baseURL() . $this->SuccessUrl);
    }


    private function createStationPickerField($placeholder = null)
    {
        $field = new TextField('PrimaryStationPicker', 'Search for your favorite NPR Member station');

        if (!empty($placeholder)) {
            $field->setAttribute('placeholder', $placeholder);
        }

        $field->setSmallFieldHolderTemplate("TextFieldWithStationFinder_holder_small");
        return $field;
    }

    private function createTextWithPrefillField($name, $title, $description = null, $leftText = null)
    {
        $field = new TextField($name, $title);
        if (!empty($description)) {
            $field->setDescription($description);
        }

        if (!empty($leftText)) {
            $field->setLeftTitle($leftText);
        }

        $field->setSmallFieldHolderTemplate("TextFieldWithPrefill_holder_small");
        return $field;
    }

}
