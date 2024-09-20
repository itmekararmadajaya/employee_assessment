<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\PasswordNotHash;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if(!empty($data['password'])){
            $user = User::where('email', $data['email'])->first();
            
            PasswordNotHash::updateOrCreate([
                'user_id' => $user->id
            ], [
                'password' => $data['password']
            ]);

            $data['password'] = Hash::make($data['password']);
        }
    
        return $data;
    }
}
