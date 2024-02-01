<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Ip_messaging
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */


namespace Twilio\Rest\IpMessaging\V2\Service\User;

use Twilio\Exceptions\TwilioException;
use Twilio\Version;
use Twilio\InstanceContext;


class UserBindingContext extends InstanceContext
    {
    /**
     * Initialize the UserBindingContext
     *
     * @param Version $version Version that contains the resource
     * @param string $serviceSid 
     * @param string $userSid 
     * @param string $sid 
     */
    public function __construct(
        Version $version,
        $serviceSid,
        $userSid,
        $sid
    ) {
        parent::__construct($version);

        // Path Solution
        $this->solution = [
        'serviceSid' =>
            $serviceSid,
        'userSid' =>
            $userSid,
        'sid' =>
            $sid,
        ];

        $this->uri = '/Services/' . \rawurlencode($serviceSid)
        .'/Users/' . \rawurlencode($userSid)
        .'/Bindings/' . \rawurlencode($sid)
        .'';
    }

    /**
     * Delete the UserBindingInstance
     *
     * @return bool True if delete succeeds, false otherwise
     * @throws TwilioException When an HTTP error occurs.
     */
    public function delete(): bool
    {

        return $this->version->delete('DELETE', $this->uri);
    }


    /**
     * Fetch the UserBindingInstance
     *
     * @return UserBindingInstance Fetched UserBindingInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch(): UserBindingInstance
    {

        $payload = $this->version->fetch('GET', $this->uri);

        return new UserBindingInstance(
            $this->version,
            $payload,
            $this->solution['serviceSid'],
            $this->solution['userSid'],
            $this->solution['sid']
        );
    }


    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString(): string
    {
        $context = [];
        foreach ($this->solution as $key => $value) {
            $context[] = "$key=$value";
        }
        return '[Twilio.IpMessaging.V2.UserBindingContext ' . \implode(' ', $context) . ']';
    }
}
