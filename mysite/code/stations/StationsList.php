<?php

/**
 * Class StationsList
 *
 * Performs requests to the Stations API and returns data
 */
class StationsList
{
    // Chosen as an arbitrarily high number to return all stations
    const LARGE_STATION_NUMBER = 5000;
    const STATIONS_QUERY = '/v2/stations/search/{query}';
    const DEFAULT_USER_AGENT = 'Curl/PHP';

    static $CALL_LETTER_CACHE = [];

    public function queryStations($query)
    {
        $default = [];
        $url = self::STATIONS_QUERY;

        $url = str_replace('{query}', $query, $url);
        $results = $this->sendBusRequest($url);

        if (!empty($results))
        {
            return $results;
        }
        return $default;
    }

    /**
     * Query BUS with a station id to get call letters back
     *
     * Caches the results, so we don't do extra queries
     *
     * @param $stationId
     * @return null|string
     */
    public function queryForCallLetters($stationId)
    {
        if (array_key_exists($stationId, self::$CALL_LETTER_CACHE))
        {
            return self::$CALL_LETTER_CACHE[$stationId];
        }

        $url = '/v2/stations/org/' . $stationId;
        $results = $this->sendBusRequest($url);

        if (!empty($results))
        {

            $callLetters = $results[0]->call;
            self::$CALL_LETTER_CACHE[$stationId] = $callLetters;
            return $callLetters;
        }
        else
        {
            return null;
        }

    }

    /**
     * Make the actual request to the BUS API, via curl
     *
     * @param string $url
     * @return json|null
     */
    public function sendBusRequest($url)
    {
        $url = SiteConfig::current_site_config()->NPRAPIHost . $url;

        $fetch = new RestfulService(
            $url
        );

        $fetch->setQueryString(array(
            'size'      => self::LARGE_STATION_NUMBER,
            'apiKey'    => SiteConfig::current_site_config()->BusAPIKey
        ));

        // perform the query
        $conn = $fetch->request();

        // parse the XML body
        return json_decode($conn->getBody());
    }
}
