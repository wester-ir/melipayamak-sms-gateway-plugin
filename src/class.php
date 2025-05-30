<?php

use App\Models\Order;
use App\Services\Core\SMS\SMSGatewayInterface;
use Illuminate\Support\Facades\Http;

class WK_MELIPAYAMAK_SMS_GATEWAY implements SMSGatewayInterface
{
    public const NAME = 'Meli Payamak';
    private const REGULAR_ENDPOINT = 'https://rest.payamak-panel.com/api/SendSMS/SendSMS';
    private const PATTERN_ENDPOINT = 'https://rest.payamak-panel.com/api/SendSMS/BaseServiceNumber';

    /**
     * The username.
     * 
     * @var string
     */
    private string $username;

    /**
     * The password.
    *
    * @var string
    */
    private string $password;

    /**
     * The dedicated number.
     * 
     * @var string
     */
    private string $number;

    /**
     * The verification pattern id.
     * 
     * @var string
     */
    private string $verificationPatternId;

    /**
     * The order paid pattern id.
     * 
     * @var string
     */
    private string $orderPaidPatternId;

    /**
     * The order fulfilled pattern id.
     * 
     * @var string
     */
    private string $orderFulfilledPatternId;

    /**
     * The arguments for the order paid pattern.
     * 
     * @var string
     */
    private string $orderPaidPatternArgs;

    /**
     * The arguments for the order fulfilled pattern.
     * 
     * @var string
     */
    private string $orderFulfilledPatternArgs;

    /**
     * Create a new instance.
     */
    public function __construct()
    {
        $options = pluginRepository()->doAction('plugin[melipayamak-sms-gateway]__settings__options')->keyBy('name')
                    ->map(fn ($option) => $option->value);

        $this->username = $options->get('username');
        $this->password = $options->get('password');
        $this->number = $options->get('number');
        $this->verificationPatternId = (int) $options->get('verification_pattern_id');
        $this->orderPaidPatternId = (int) $options->get('order_paid_pattern_id');
        $this->orderFulfilledPatternId = (int) $options->get('order_fulfilled_pattern_id');
        $this->orderPaidPatternArgs = $options->get('order_paid_pattern_args');
        $this->orderFulfilledPatternArgs = $options->get('order_fulfilled_pattern_args');
    }

    /**
     * Make a http request.
     *
     * @param  string  $endpoint
     * @param  array  $data
     * @param  string  $to
     * @return array|null
     */
    private function send(string $endpoint, array $data, string $to): ?array
    {
        // Prepare the data for the SMS request.
        $data = [
            'username' => $this->username,
            'password' => $this->password,
            ...$data,
            'to' => $to,
        ];

        try {
            $response = Http::post($endpoint, $data);
        } catch (\Exception) {
            return null;
        }

        return $response->json();
    }

    /**
     * Send a regular message.
     *
     * @param  string  $text
     * @param  string  $to
     * @return array|null
     */
    public function sendMessage(string $text, string $to): ?array
    {
        return $this->send(self::REGULAR_ENDPOINT, [
            'text' => $text,
            'from' => $this->number,
        ], $to);
    }

    /**
     * Send a message using a pattern.
     *
     * @param  string  $args
     * @param  int  $patternId
     * @param  string  $to
     * @return array|null
     */
    public function sendPattern(string $args, int $patternId, string $to): ?array
    {
        return $this->send(self::PATTERN_ENDPOINT, [
            'text' => $args,
            'bodyId' => $patternId,
        ], $to);
    }

    /**
     * Send the verification code.
     *
     * @param  int  $code
     * @param  string  $number
     * @return array|null
     */
    public function sendVerificationCode(int $code, string $number): ?array
    {
        $patternId = $this->verificationPatternId;

        return $this->sendPattern($code, $patternId, $number);
    }

    /**
     * Send a "order paid" message.
     * 
     * @param  \App\Models\Order  $order
     * @return array|null
     */
    public function sendOrderPaidMessage(Order $order): ?array
    {
        $patternId = $this->orderPaidPatternId;
        $patternArgs = $this->orderPaidPatternArgs;

        if (! $patternId || ! $patternArgs) {
            return null;
        }
        
        $args = $this->replacePatternArgs($order, $patternArgs);
        $to = $order->user->number;

        return $this->sendPattern($args, $patternId, $to);
    }

    /**
     * Send a "order fulfilled" message.
     * 
     * @param  \App\Models\Order  $order
     * @return array|null
     */
    public function sendOrderFulfilledMessage(Order $order): ?array
    {
        $patternId = $this->orderFulfilledPatternId;
        $patternArgs = $this->orderFulfilledPatternArgs;

        if (! $patternId || ! $patternArgs) {
            return null;
        }

        $args = $this->replacePatternArgs($order, $patternArgs);
        $to = $order->user->number;

        return $this->sendPattern($args, $patternId, $to);
    }

    /**
     * Replace pattern arguments.
     * 
     * @param  \App\Models\Order  $order
     * @param  string  $args
     * @return string
     */
    private function replacePatternArgs(Order $order, string $args): string
    {
        return preg_replace_callback('/{(.*?)}/', function ($matches) use ($order) {
            $key = $matches[1];
            $fallback = null;

            if (str_contains($key, '|')) {
                [$key, $fallback] = explode('|', $key);
            }

            return data_get($order, $key, $fallback);
        }, $args);
    }
}
