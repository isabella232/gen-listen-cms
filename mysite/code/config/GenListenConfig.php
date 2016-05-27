<?php

class GenListenConfig extends DataExtension {

    private static $db = array(
        'NewsletterSubmitUrl'   => 'Text',
        'NPRAPIHost'            => 'Text',
        'BusAPIKey'             => 'Text',
        'SMCClientId'           => 'Text',
        'SMCClientSecret'       => 'Text',
        'SMCDataExtension'      => 'Text',
        'DefaultDate'           => 'Text'
    );

    public function updateCMSFields(FieldList $fields) {
        $fields->addFieldToTab("Root.Main",
            new TextField("NewsletterSubmitUrl", "Newsletter Signup POST URL")
        );
        $fields->addFieldToTab("Root.Main",
            new TextField("NPRAPIHost", "NPR API Host")
        );
        $fields->addFieldToTab("Root.Main",
            new TextField("BusAPIKey", "BUS API Key")
        );
        $fields->addFieldToTab("Root.Main",
            new TextField("SMCClientId", "SMC Client ID")
        );
        $fields->addFieldToTab("Root.Main",
            new TextField("SMCClientSecret", "SMC Client Secret")
        );
        $fields->addFieldToTab("Root.Main",
            new TextField("SMCDataExtension", "SMC Data Extension")
        );
        $fields->addFieldToTab("Root.Main",
            new TextField("DefaultDate", "Default Date")
        );
    }
}