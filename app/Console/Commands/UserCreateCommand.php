<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

class UserCreateCommand extends Command
{
    protected $signature = 'user:create';

    protected $description = 'Creates a user';

    public function handle(): void
    {
        $name = text('Name', required: true, validate: ['name' => ['string', 'max:255']]);
        $email = text('E-Mail', required: true, validate: ['email' => ['string', 'email', 'max:255', 'unique:users']]);
        $password = password('Passwort', required: true, validate: ['password' => ['required', 'string', Password::default()]]);

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        \Laravel\Prompts\info("User $name wurde erstellt.");
    }
}
