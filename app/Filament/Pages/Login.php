<?php

namespace Filament\Pages;

use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Http\Responses\Auth\LoginResponse as DefaultLoginResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Login as BaseAuth;
use Filament\Forms\Form;

class Login extends BaseAuth
{
    public function mount(): void
    {
        // Mengisi username dan password dengan nilai default
        $this->data['login'] = 'admin1@email.com'; // Ganti dengan username default jika perlu
        $this->data['password'] = '123'; // Ganti dengan password default jika perlu
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getLoginFormComponent(), // Ganti dengan username
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
            ])
            ->statePath('data');
    }

    protected function getLoginFormComponent(): Component
    {
        return TextInput::make('login') // Ini untuk username
            ->label('Email') // Ganti label menjadi Username
            ->required()
            ->autocomplete()
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1]);
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        // Hanya menggunakan username
        return [
            'email' => $data['login'], // Mengambil username dari input
            'password' => $data['password'],
        ];
    }

    // public function authenticate(): ?LoginResponse
    // {
    //     $this->validate();

    //     // Menggunakan guard 'admin' untuk autentikasi
    //     if (! Auth::guard('admin')->attempt($this->getCredentialsFromFormData($this->data))) {
    //         throw ValidationException::withMessages([
    //             'login' => 'Username atau password salah.',
    //         ]);
    //     }

    //     // Jika autentikasi berhasil, lakukan redirect
    //     return app(DefaultLoginResponse::class)->redirect('/admin');
    // }
}

