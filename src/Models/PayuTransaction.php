<?php

namespace Tzsk\Payu\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Tzsk\Payu\Concerns\Transaction;
use Tzsk\Payu\Gateway\Gateway;
use Tzsk\Payu\Gateway\PayuBiz;
use Tzsk\Payu\Gateway\PayuMoney;
use Tzsk\Payu\Jobs\VerifyTransaction;
use Tzsk\Payu\Models\Casts\Serialized;

/**
 * Class PayuPayments
 *
 * @property string $transaction_id;
 * @property PayuMoney|PayuBiz $gateway
 * @property Transaction $body
 * @property string $destination
 * @property string $hash
 * @property string $status
 * @property Carbon $verified_at
 *
 * @method static Builder verifiable()
 * @method static self locate(string $transaction_id)
 * @method string response(string $key)
 */
class PayuTransaction extends Model
{
    use SoftDeletes;

    const STATUS_PENDING = 'pending';

    const STATUS_FAILED = 'failed';

    const STATUS_SUCCESSFUL = 'successful';

    const STATUS_INVALID = 'invalid';

    protected $fillable = [
        'transaction_id', 'paid_for_id', 'paid_for_type', 'gateway', 'body',
        'destination', 'hash', 'response', 'status', 'verified_at',
    ];

    protected $casts = [
        'gateway' => Serialized::class,
        'body' => Serialized::class,
        'response' => 'array',
    ];

    protected $dates = [
        'verified_at',
    ];

    public function paidFor(): MorphTo
    {
        return $this->morphTo('paid_for');
    }

    public function scopePending(Builder $builder): Builder
    {
        return $builder->where('status', self::STATUS_PENDING);
    }

    public function scopeFailed(Builder $builder): Builder
    {
        return $builder->where('status', self::STATUS_FAILED);
    }

    public function scopeSuccessful(Builder $builder): Builder
    {
        return $builder->where('status', self::STATUS_SUCCESSFUL);
    }

    public function scopeVerifiable(Builder $builder): Builder
    {
        return $builder->whereIn('status', config('payu.verify', []))->whereNull('verified_at');
    }

    public function scopeLocate(Builder $builder, string $id): ?self
    {
        /** @var self $row */
        $row = $builder->where('transaction_id', $id)->firstOrFail();

        return $row;
    }

    public function successful()
    {
        return $this->status == self::STATUS_SUCCESSFUL;
    }

    public function failed()
    {
        return $this->status == self::STATUS_FAILED;
    }

    public function pending()
    {
        return $this->status == self::STATUS_PENDING;
    }

    public function invalid()
    {
        return $this->status == self::STATUS_INVALID;
    }

    public function verified()
    {
        return ! empty($this->verified_at);
    }

    public function shouldVerify()
    {
        $allowedStatus = in_array($this->status, config('payu.verify', []));
        $notChecked = empty($this->verified_at);

        return $allowedStatus && $notChecked;
    }

    public function verify(): ?self
    {
        /** @var Gateway $gateway */
        $gateway = $this->getAttribute('gateway');
        $gateway->verifier()->handle($this);

        return $this->fresh();
    }

    public function verifyAsync()
    {
        VerifyTransaction::dispatch($this);
    }

    public function response($key)
    {
        return data_get($this->getAttribute('response'), $key);
    }
}
