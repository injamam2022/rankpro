<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class IciciGateway
{
    public function configured(): bool
    {
        return $this->merchantId() !== ''
            && $this->aggregatorId() !== ''
            && $this->secret() !== ''
            && $this->saleUrl() !== '';
    }

    public function newTxnNo(): string
    {
        return 'RP'.now('Asia/Kolkata')->format('ymdHis').str_pad((string) random_int(0, 99), 2, '0', STR_PAD_LEFT);
    }

    /**
     * Open a standard (redirect) sale and return the bank page URL.
     */
    public function startSale(string $merchantTxnNo, float $amount, $user): string
    {
        if (!$this->configured()) {
            throw new RuntimeException('ICICI payment gateway is not configured.');
        }

        $payload = [
            'aggregatorID' => $this->aggregatorId(),
            'amount' => number_format($amount, 2, '.', ''),
            'currencyCode' => '356',
            'customerEmailID' => $this->email($user),
            'customerMobileNo' => $this->mobile($user),
            'customerName' => $this->name($user),
            'merchantId' => $this->merchantId(),
            'merchantTxnNo' => $merchantTxnNo,
            'payType' => '0',
            'returnURL' => $this->returnUrl(),
            'transactionType' => 'SALE',
            'txnDate' => now('Asia/Kolkata')->format('YmdHis'),
        ];
        $payload['secureHash'] = $this->hash($payload);

        $body = $this->post($this->saleUrl(), $payload);
        $code = (string) ($body['responseCode'] ?? '');
        if ($code !== 'R1000') {
            Log::warning('ICICI initiateSale rejected', [
                'merchantTxnNo' => $merchantTxnNo,
                'responseCode' => $code,
                'respDescription' => $body['respDescription'] ?? $body['responseDescription'] ?? null,
            ]);
            $message = $body['respDescription'] ?? $body['responseDescription'] ?? null;
            throw new RuntimeException($message ? 'ICICI: '.$message : 'ICICI could not start this payment ('.$code.').');
        }

        return $this->redirectUrl($body);
    }

    /**
     * Confirm a sale with the Status Check API. This is the source of truth.
     */
    public function status(string $originalTxnNo): array
    {
        $payload = [
            'aggregatorID' => $this->aggregatorId(),
            'merchantId' => $this->merchantId(),
            'merchantTxnNo' => $originalTxnNo,
            'originalTxnNo' => $originalTxnNo,
            'transactionType' => 'STATUS',
        ];
        $payload['secureHash'] = $this->hash($payload);

        return $this->post($this->commandUrl(), $payload);
    }

    public function isPaid(array $status): bool
    {
        $txnStatus = strtoupper((string) ($status['txnStatus'] ?? ''));
        $txnCode = (string) ($status['txnResponseCode'] ?? '');

        return $txnStatus === 'SUC' && in_array($txnCode, ['0000', '000'], true);
    }

    public function isFailed(array $status): bool
    {
        $txnStatus = strtoupper((string) ($status['txnStatus'] ?? ''));

        return in_array($txnStatus, ['REJ', 'FAIL', 'FAILED', 'FAL', 'DEC'], true);
    }

    public function hash(array $fields): string
    {
        $text = $this->hashText($fields);

        return hash_hmac('sha256', $text, $this->secret());
    }

    public function hashMatches(array $fields): bool
    {
        $given = strtolower((string) ($fields['secureHash'] ?? ''));
        if ($given === '') {
            return false;
        }

        return hash_equals($this->hash($fields), $given);
    }

    private function hashText(array $fields): string
    {
        $copy = $fields;
        unset($copy['secureHash']);
        ksort($copy, SORT_STRING);

        $text = '';
        foreach ($copy as $value) {
            if ($value === null) {
                continue;
            }
            if (is_array($value)) {
                $text .= json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                continue;
            }
            if (is_bool($value)) {
                $text .= $value ? 'true' : 'false';
                continue;
            }
            $text .= (string) $value;
        }

        return $text;
    }

    private function redirectUrl(array $body): string
    {
        $uri = (string) ($body['redirectURI'] ?? '');
        $ctx = (string) ($body['tranCtx'] ?? '');
        $host = strtolower((string) parse_url($uri, PHP_URL_HOST));
        $allowed = $host !== '' && (
            $host === 'icicibank.com' || str_ends_with($host, '.icicibank.com')
            || $host === 'icici.bank.in' || str_ends_with($host, '.icici.bank.in')
        );
        if (!$allowed || $ctx === '') {
            throw new RuntimeException('ICICI did not return a payment page.');
        }

        $join = str_contains($uri, '?') ? '&' : '?';

        return $uri.$join.'tranCtx='.rawurlencode($ctx);
    }

    private function post(string $url, array $payload): array
    {
        try {
            $response = Http::timeout(40)
                ->acceptJson()
                ->asJson()
                ->post($url, $payload);
        } catch (\Throwable $e) {
            Log::warning('ICICI request failed', ['url' => $url, 'error' => $e->getMessage()]);
            throw new RuntimeException('Could not reach ICICI Bank. Please try again.');
        }

        $body = $response->json();
        if (!is_array($body)) {
            Log::warning('ICICI non-JSON response', ['url' => $url, 'status' => $response->status()]);
            throw new RuntimeException('ICICI returned an unexpected response.');
        }

        return $body;
    }

    private function returnUrl(): string
    {
        $url = route('icici.return');
        if (strlen($url) > 128) {
            throw new RuntimeException('Payment return URL is longer than ICICI allows (128 characters). Shorten APP_URL.');
        }

        return $url;
    }

    private function name($user): string
    {
        $name = trim((string) (($user->first_name ?? '').' '.($user->last_name ?? '')));
        $name = preg_replace('/[^A-Za-z0-9 ]/', '', $name) ?: 'RankPro Student';

        return substr(trim($name), 0, 40);
    }

    private function email($user): string
    {
        $email = strtolower(trim((string) ($user->email_id ?? $user->email ?? '')));
        if (filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($email) <= 48) {
            return $email;
        }

        return 'student'.(int) ($user->id ?? 0).'@rankpro.in';
    }

    private function mobile($user): string
    {
        $digits = preg_replace('/\D/', '', (string) ($user->mobile_number ?? ''));
        if (strlen($digits) === 10) {
            $digits = '91'.$digits;
        }
        if (strlen($digits) < 10 || strlen($digits) > 15) {
            throw new RuntimeException('Add a valid mobile number on your profile before paying.');
        }

        return $digits;
    }

    private function merchantId(): string
    {
        return (string) config('services.icici.merchant_id');
    }

    private function aggregatorId(): string
    {
        return (string) config('services.icici.aggregator_id');
    }

    private function secret(): string
    {
        return (string) config('services.icici.secret');
    }

    private function saleUrl(): string
    {
        return (string) config('services.icici.sale_url');
    }

    private function commandUrl(): string
    {
        return (string) config('services.icici.command_url');
    }
}
