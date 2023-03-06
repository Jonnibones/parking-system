<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Verify\V2\Service;

use Twilio\Deserialize;
use Twilio\Exceptions\TwilioException;
use Twilio\InstanceResource;
use Twilio\Values;
use Twilio\Version;

/**
 * @property string $sid
 * @property string $serviceSid
 * @property string $accountSid
 * @property string $to
 * @property string $channel
 * @property string $status
 * @property bool $valid
 * @property array $lookup
 * @property string $amount
 * @property string $payee
 * @property array[] $sendCodeAttempts
 * @property \DateTime $dateCreated
 * @property \DateTime $dateUpdated
 * @property array $sna
 * @property string $url
 */
class VerificationInstance extends InstanceResource {
    /**
     * Initialize the VerificationInstance
     *
     * @param Version $version Version that contains the resource
     * @param mixed[] $payload The response payload
     * @param string $serviceSid The SID of the Service that the resource is
     *                           associated with
     * @param string $sid The unique string that identifies the resource
     */
    public function __construct(Version $version, array $payload, string $serviceSid, string $sid = null) {
        parent::__construct($version);

        // Marshaled Properties
        $this->properties = [
            'sid' => Values::array_get($payload, 'sid'),
            'serviceSid' => Values::array_get($payload, 'service_sid'),
            'accountSid' => Values::array_get($payload, 'account_sid'),
            'to' => Values::array_get($payload, 'to'),
            'channel' => Values::array_get($payload, 'channel'),
            'status' => Values::array_get($payload, 'status'),
            'valid' => Values::array_get($payload, 'valid'),
            'lookup' => Values::array_get($payload, 'lookup'),
            'amount' => Values::array_get($payload, 'amount'),
            'payee' => Values::array_get($payload, 'payee'),
            'sendCodeAttempts' => Values::array_get($payload, 'send_code_attempts'),
            'dateCreated' => Deserialize::dateTime(Values::array_get($payload, 'date_created')),
            'dateUpdated' => Deserialize::dateTime(Values::array_get($payload, 'date_updated')),
            'sna' => Values::array_get($payload, 'sna'),
            'url' => Values::array_get($payload, 'url'),
        ];

        $this->solution = ['serviceSid' => $serviceSid, 'sid' => $sid ?: $this->properties['sid'], ];
    }

    /**
     * Generate an instance context for the instance, the context is capable of
     * performing various actions.  All instance actions are proxied to the context
     *
     * @return VerificationContext Context for this VerificationInstance
     */
    protected function proxy(): VerificationContext {
        if (!$this->context) {
            $this->context = new VerificationContext(
                $this->version,
                $this->solution['serviceSid'],
                $this->solution['sid']
            );
        }

        return $this->context;
    }

    /**
     * Update the VerificationInstance
     *
     * @param string $status The new status of the resource
     * @return VerificationInstance Updated VerificationInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function update(string $status): VerificationInstance {
        return $this->proxy()->update($status);
    }

    /**
     * Fetch the VerificationInstance
     *
     * @return VerificationInstance Fetched VerificationInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch(): VerificationInstance {
        return $this->proxy()->fetch();
    }

    /**
     * Magic getter to access properties
     *
     * @param string $name Property to access
     * @return mixed The requested property
     * @throws TwilioException For unknown properties
     */
    public function __get(string $name) {
        if (\array_key_exists($name, $this->properties)) {
            return $this->properties[$name];
        }

        if (\property_exists($this, '_' . $name)) {
            $method = 'get' . \ucfirst($name);
            return $this->$method();
        }

        throw new TwilioException('Unknown property: ' . $name);
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
        return '[Twilio.Verify.V2.VerificationInstance ' . \implode(' ', $context) . ']';
    }
}