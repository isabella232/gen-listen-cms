<?php


class NPRUser
{
    /** Timeout for connecting to NPR */
    const TIMEOUT = 10;

    /** @var $email String */
    private $email;

    /** @var int $orgId */
    private $orgId;

    // response
    /** @var int $userId */
    private $userId;

    function __construct($email, $orgId = 0)
    {
        $this->email = $email;
        $this->orgId = intval($orgId) ? intval($orgId) : 0;
    }


    /**
     * @return string
     */
    private function getNewsletterPostUrl()
    {
        return SiteConfig::current_site_config()->NewsletterSubmitUrl;
    }


    /**
     * @see http://confluence.npr.org/display/TECH/External+Newsletter+Form+Submit
     */
    function submit()
    {
        /*
        email:"example@example.com"
        orgId:0
        newsletterTitle:"[NEWSLETTER]"
        isAuthenticated:false
        */

        $postData = [
            'email' => $this->email,
            'orgId' => $this->orgId,
            'isAuthenticated' => 'false'
        ];

        try
        {

            $fetch = new RestfulService($this->getNewsletterPostUrl());
            $response = $fetch->request("", 'POST', http_build_query($postData), array('Content-Type: application/x-www-form-urlencoded'), array(CURLOPT_CONNECTTIMEOUT => self::TIMEOUT, CURLOPT_TIMEOUT => self::TIMEOUT ));

            if ($response->getStatusCode() == 200)
            {
                return $this->parseResponse($response->getBody());
            }
            else
            {
                throw new Exception("Failure submitting NPR request: " . $this->getNewsletterPostUrl() . ":" . print_r($postData, true) . " -- " . $response->getStatusCode() . ' - ' . $response->getBody());
            }
        }
        catch (Exception $e)
        {
            throw new Exception("Failure submitting NPR request: " . $this->getNewsletterPostUrl() . ": " . $e);
        }
    }

    protected function parseResponse($response)
    {
        $responseObject = json_decode($response);

        // we need the userId
        $this->userId = intval($responseObject->userId);


    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

}