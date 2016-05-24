<?php


class SMCFormData
{
    /** Timeout for connecting to SMC */
    const TIMEOUT = 10;

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

    }

}