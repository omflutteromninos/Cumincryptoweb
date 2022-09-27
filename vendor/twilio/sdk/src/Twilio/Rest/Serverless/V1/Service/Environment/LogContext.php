<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Serverless\V1\Service\Environment;

use Twilio\Exceptions\TwilioException;
use Twilio\InstanceContext;
use Twilio\Values;
use Twilio\Version;

/**
 * PLEASE NOTE that this class contains beta products that are subject to change. Use them with caution.
 */
class LogContext extends InstanceContext {
    /**
     * Initialize the LogContext
     *
     * @param Version $version Version that contains the resource
     * @param string $serviceSid The SID of the Service to fetch the Log resource
     *                           from
     * @param string $environmentSid The SID of the environment with the Log
     *                               resource to fetch
     * @param string $sid The SID that identifies the Log resource to fetch
     */
    public function __construct(Version $version, $serviceSid, $environmentSid, $sid) {
        parent::__construct($version);

        // Path Solution
        $this->solution = ['serviceSid' => $serviceSid, 'environmentSid' => $environmentSid, 'sid' => $sid, ];

        $this->uri = '/Services/' . \rawurlencode($serviceSid) . '/Environments/' . \rawurlencode($environmentSid) . '/Logs/' . \rawurlencode($sid) . '';
    }

    /**
     * Fetch the LogInstance
     *
     * @return LogInstance Fetched LogInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch(): LogInstance {
        $payload = $this->version->fetch('GET', $this->uri);

        return new LogInstance(
            $this->version,
            $payload,
            $this->solution['serviceSid'],
            $this->solution['environmentSid'],
            $this->solution['sid']
        );
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString(): string {
        $context = [];
        foreach ($this->solution as $key => $value) {
            $context[] = "$key=$value";
        }
        return '[Twilio.Serverless.V1.LogContext ' . \implode(' ', $context) . ']';
    }
}