<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class UserCommand extends Command
{
    protected $signature = 'make:user
        {--login= : Логин пользователя}
        {--name= : Имя пользователя}
        {--email= : Email пользователя}
        {--password= : Пароль пользователя}';

    protected $description = 'Создание пользователя';

    public function handle()
    {
        $login = $this->option('login');
        $name = $this->option('name');
        $email = $this->option('email');
        $password = $this->option('password');

        if (!$login || !$name || !$email || !$password) {
            $this->error('Нужны все параметры для создание пользователя: --login, --name, --email, --password');
        }

        if (User::where('email', $email)->exists()) {
            $this->error("Пользователь  с email {$email} уже существует.");
        }

        if (User::where('login', $login)->exists()) {
            $this->error("Пользователь  с login {$login} уже существует.");
        }

        User::create([
            'name' => $name,
            'login' => $login,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $this->info("Пользователь с логином {$login} успешно создан.");
        return 0;
    }
}
