<?php

class GenListenConfig extends DataExtension {

    private static $db = array(
        'NewsletterSubmitUrl'   => 'Text',
        'NPRAPIHost'            => 'Text',
        'BusAPIKey'             => 'Text'
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
    }
}