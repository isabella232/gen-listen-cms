<?php
/**
 * Class BasicContentPage
 *
 * Render a generic content page
 *
 */
class BasicContentPage extends Page
{
    static $description = "Basic Content Page";

    private static $db = array(

    );
    public function getCMSFields() {
        $fields = parent::getCMSFields();
        return $fields;
    }
}


class BasicContentPage_Controller extends Page_Controller
{

}
