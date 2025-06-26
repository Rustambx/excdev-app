<?php

namespace App\Jobs;

use App\Models\Balance;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

class OperationJob implements ShouldQueue
{
    use Queueable;

    protected string $login;
    protected float $amount;
    protected string $type;
    protected string $description;

    /**
     * Create a new job instance.
     */
    public function __construct(string $login, float $amount, string $type, string $description)
    {
        $this->login = $login;
        $this->amount = $amount;
        $this->type = $type;
        $this->description = $description;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        DB::beginTransaction();

        try {
            $user = User::where('login', $this->login)->firstOrFail();

            $balance = Balance::firstOrCreate(
                ['user_id' => $user->id],
                ['amount' => 0]
            );

            if ($this->type === 'debit' && $this->amount > $balance->amount) {
                throw new \Exception('Недостаточно средств');
            }

            if ($this->type === 'credit') {
                $balance->amount += $this->amount;
            } else {
                $balance->amount -= $this->amount;
            }

            $balance->save();

            Transaction::create([
                'amount' => $this->amount,
                'type' => $this->type,
                'description' => $this->description,
                'user_id' => $user->id,
            ]);

            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
