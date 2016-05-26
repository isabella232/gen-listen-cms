<?php

class SMCFormData
{
    /** Timeout for connecting to SMC */
    const TIMEOUT = 10;

    const ET_REST_API_URL                           = "https://www.exacttargetapis.com";
    const ET_REST_DATA_EXT_ENDPOINT_FORMAT          = "/hub/v1/dataevents/key:%s/rowset";
    const ET_FRAMEWORK_WSDL                         = "https://webservice.exacttarget.com/etframework.wsdl";
    const ET_FRAMEWORK_WSDL_LOCAL                   = "/ET_WSDL.xml";
    const ET_REST_API_REQUEST_TOKEN_URL             = "https://auth.exacttargetapis.com/v1/requestToken";

    /** @var int $NPRUserId */
    private $NPRUserId;

    /** @var string $firstName */
    private $firstName;

    /** @var string $lastName */
    private $lastName;

    /** @var string $email */
    private $email;

    /** @var string $zipCode */
    private $zipCode;

    /** @var string $hostingDate */
    private $hostingDate;

    /** @var string $twitter */
    private $twitter;

    /** @var string $instagram */
    private $instagram;

    /** @var int $stationId */
    private $stationId;


    function __construct($NPRUserId, $firstName, $lastName, $email, $zipCode, $hostingDate, $twitter, $instagram, $stationId)
    {
        $this->NPRUserId = $NPRUserId;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->zipCode = $zipCode;
        $this->hostingDate = $hostingDate;
        $this->twitter = $twitter;
        $this->instagram = $instagram;
        $this->stationId = $stationId;
    }

    function submit()
    {
        try
        {
            $client = new ET_Client(false, false, [
                    "clientid" => SiteConfig::current_site_config()->SMCClientId,
                    "clientsecret" => SiteConfig::current_site_config()->SMCClientSecret,
                    "defaultwsdl" => self::ET_FRAMEWORK_WSDL,
                    "xmlloc" => __DIR__  . self::ET_FRAMEWORK_WSDL_LOCAL
                ]
            );

            $apiEndpoint = sprintf(self::ET_REST_DATA_EXT_ENDPOINT_FORMAT, SiteConfig::current_site_config()->SMCDataExtension);
            $apiUrl = self::ET_REST_API_URL . $apiEndpoint;

            $accessToken = $client->accessToken;

            if (empty($accessToken))
            {
                $accessToken = $this->requestAccessToken();
            }


            $data = $this->mapUpsertRow();

            $dataExtColumns = new ET_DataExtension_Column();
            $dataExtColumns->authStub = $client;
            $dataExtColumns->props = ["Name", "FieldType", "IsPrimaryKey"];
            $dataExtColumns->filter = ["Property" => "CustomerKey", "SimpleOperator" => "equals", "Value" => SiteConfig::current_site_config()->SMCDataExtension];

            $response = $dataExtColumns->get();

            $colFields = [];

            if (!empty($response) && is_array($response->results))
            {
                $colFields = $response->results;
            }

            $fields = $this->getColumnFieldNames($colFields);

            if (count($fields) === 0)
            {
                throw new \Exception('Unable to create new user. Cannot describe "' . SiteConfig::current_site_config()->SMCDataExtension . '" data extension column properties. ' . print_r($response, true));
            }

            // Assuming that the $data array adheres to "snake-cased" conventions,
            // map the property values to their respective fields in ET
            $props = $this->mapDataPropertyValuesToColumns($data, $fields); // ['<ColumnName>' => '<Value>', ...]

            $dataExtRow = new ET_DataExtension_Row();
            $dataExtRow->authStub = $client;
            $dataExtRow->CustomerKey = SiteConfig::current_site_config()->SMCDataExtension;
            $dataExtRow->props = $props;
            $response = $dataExtRow->post();
        }
        catch (Exception $e)
        {
            throw new Exception("Failure submitting NPR request: " . $e);
        }
    }

    /**
     * Requests/refreshes an access token from ET
     * @return Array<k, v>
     */
    public function requestAccessToken()
    {
        try
        {
            $fetch = new RestfulService(self::ET_REST_API_REQUEST_TOKEN_URL);
            $response = $fetch->request(
                "",
                'POST',
                [
                    "clientId" => SiteConfig::current_site_config()->SMCClientId,
                    "clientSecret" => SiteConfig::current_site_config()->SMCClientSecret
                ],
                [],
                [
                    CURLOPT_CONNECTTIMEOUT => self::TIMEOUT,
                    CURLOPT_TIMEOUT => self::TIMEOUT
                ]
            );

            if ($response->getStatusCode() == 200)
            {
                $json = json_decode($response->getBody());
                return $json->accessToken;
            }
            else
            {
                throw new Exception("Failure submitting SMC request: " . self::ET_REST_API_REQUEST_TOKEN_URL . ":" . print_r($response, true) . " -- " . $response->getStatusCode() . ' - ' . $response->getBody());
            }
        }
        catch(Exception $ex)
        {
            throw new Exception("Failure submitting SMC request: " . self::ET_REST_API_REQUEST_TOKEN_URL . ": " . print_r($ex, true));
        }
    }

    /**
     * Maps the data for delivery
     * @param $data
     * @return array
     */
    public function mapUpsertRow()
    {
        return [
                "user_id" => $this->NPRUserId ? $this->NPRUserId : "",
                "first_name" => $this->firstName ? $this->firstName : "",
                "last_name" => $this->lastName ? $this->lastName : "",
                "email" => $this->email ? $this->email : "",
                "zip" => $this->zipCode ? $this->zipCode : "",
                "party_date" => $this->hostingDate ? $this->hostingDate : "",
                "twitter_id" => $this->twitter ? $this->twitter : "",
                "instagram_id" => $this->instagram ? $this->instagram : ""
        ];
    }

    /**
     * Post method
     * @param $url
     * @param $additionalHeaders array<string>
     * @param $data array
     * @return curl_exec
     */
    public function post($url, $additionalHeaders, $data)
    {
        $headers = [];

        if(count($additionalHeaders) > 0)
        {
            for($c=0; $c < count($additionalHeaders); $c++)
            {
                $item = $additionalHeaders[$c];

                $headers[] = $item;
            }
        }

        $curl = curl_init($url);
        curl_setopt( $curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt( $curl, CURLOPT_POST, true );

        curl_setopt( $curl, CURLOPT_POSTFIELDS,  $data);

        curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT ,0);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }

    /**
     * Gets the column name from each field
     * @param $fields A collection of objects
     * @return array
     */
    public function getColumnFieldNames($fields)
    {
        $names = [];

        for ($a=0; $a<count($fields); $a++)
        {
            $field = $fields[$a];

            if (property_exists($field, 'Name')){
                $names[] = $field->Name;
            }
        }

        return $names;
    }

    /**
     * Maps the properties to a column/field in the data extension
     * @param $data
     * @param $fields
     * @return array
     */
    private function mapDataPropertyValuesToColumns($data, $fields)
    {
        $props = [];

        foreach ($fields as $field)
        {
            if (array_key_exists($field, $data))
            {
                $props[$field] = $data[$field];
            }
        }
        return $props;
    }

}