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
                TextField::create('zipCode', 'Zip code <span class="required">*</span>'),
                DateField::create('hostingDate', 'I plan to host my party on <span class="required">*</span>')->setValue('June 16, 2016')->setConfig('showcalendar', true)->setConfig('dateformat', 'MMMM dd, YYYY')->setConfig('min', '2016-06-16'),
                TextField::create('twitter', 'Twitter'),
                TextField::create('instagram', 'Instagram'),
                $this->createStationPickerField("Search station name, location, or zip code.")
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
            'zipCode'  => 'Please enter your 5-digit zip code.',
            'hostingDate'  => 'Please enter your hosting date."'
        ));

        $validator->setConstraint('email', Constraint_type::create('email'));
        $validator->setConstraint('zipCode', Constraint_regex::create("/^\d{5}$/")->setMessage('Please enter your 5-digit zip code.'));

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
        // contact info
        $firstName = $data['firstName'];
        $lastName = $data['$lastName'];
        $email = $data['email'];
        $zipCode = $data['zipCode'];
        $hostingDate = $data['hostingDate'];
        $twitter = $data['twitter'];
        $instagram = $data['instagram'];
        $stationId = $data['PrimaryStation'];


        try
        {
            $user = new NPRUser($email, intval($stationId));
            $user->submit();

            $smcData = new SMCFormData($user->getUserId(), $firstName, $lastName, $email, $zipCode, $hostingDate, $twitter, $instagram, $stationId);
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


        return $this->redirect(Director::baseURL() . 'success');
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
}
