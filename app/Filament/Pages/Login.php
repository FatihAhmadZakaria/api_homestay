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
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getLoginFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
            ])
            ->statePath('data');
    }
    protected function getLoginFormComponent(): Component
    {
        return TextInput::make('login')
            ->label('Email')
            ->required()
            ->autocomplete()
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1]);
    }
    protected function getCredentialsFromFormData(array $data): array
    {
        // Hanya menggunakan username
        return [
            'email' => $data['login'],
            'password' => $data['password'],
        ];
    }
}

