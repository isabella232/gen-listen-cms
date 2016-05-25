<?php
class EndpointPage extends Page
{
    static $description = "Backend page for API endpoints";

}
class EndpointPage_Controller extends Page_Controller
{

    private static $allowed_actions = array(
        'stations',
    );

    /**
     * returns a jason-encoded data collection of stations that match the supplied 'q' parameter
     *
     * @return json     station data output from BUS
     */
    public function stations()
    {
        $query = rawurlencode($this->getRequest()->getVar('q'));
        $stations = new StationsList();

        $returnMe = null;
        if (!empty($query))
        {
            $returnMe = $stations->queryStations($query);
        }

        $this->response->addHeader("Content-Type", "application/json");

        return json_encode($returnMe);
    }

}
