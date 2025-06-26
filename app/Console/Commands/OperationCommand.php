<?php

namespace App\Console\Commands;

use App\Jobs\OperationJob;
use Illuminate\Console\Command;

class OperationCommand extends Command
{
    protected $signature = 'balance:operate
        {--login= : Login пользователя}
        {--amount= : Сумма операции (положительное число)}
        {--type= : credit или debit}
        {--description= : Описание}';

    protected $description = 'Провести операцию по балансу пользователя';

    public function handle()
    {
        $login = $this->option('login');
        $amount = (float) $this->option('amount');
        $type = $this->option('type');
        $description = $this->option('description');

        if (!$login || !$amount || !$type || !$description) {
            $this->error('Нужны параметры: --login, --amount, --type, --description');
            return 1;
        }

        if (!in_array($type, ['credit', 'debit'])) {
            $this->error('Тип операции должен быть: credit или debit');
            return 1;
        }

        OperationJob::dispatch($login, $amount, $type, $description);

        $this->info("Операция отправлена в очередь. Login: $login Amount: $amount Type: $type Description: $description");

        return 0;
    }
}
