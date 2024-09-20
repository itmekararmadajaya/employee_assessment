<?php

namespace App\Filament\Pages;

use App\Models\PasswordNotHash;
use App\Models\Role;
use App\Models\User;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserCreate extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.user-create';

    public $name,
    $nik,
    $email,
    $password,
    $roles = [];

    public static function shouldRegisterNavigation(): bool {
        return false;
    }

    public function mount(){
        if(!Auth::user()->hasRole(['admin', 'superadmin'])){
            abort(403, 'Not Authorized');
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                    Section::make()->schema([
                        TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('nik')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('email')
                        ->email()
                        ->required()
                        ->maxLength(255),
                    TextInput::make('password')
                        ->required()
                        ->maxLength(255),
                    Select::make('roles')->multiple()->options(Role::get()->pluck('name', 'id'))->preload(),
                    ])->columns(2)
            ]);
    }

    public function create(){
        $this->validate([
            'name' => 'required|string|max:255',
            'nik' => 'required|string|max:20|unique:users,nik',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'roles' => 'required|array',
        ]);

        $user = User::create([
            'name' => $this->name,
            'nik' => $this->nik,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        if($user){
            /**
             * Assign Role
             */
            foreach($this->roles as $roleId){
                $role = Role::find($roleId);
                if($role){
                    $user->assignRole($role);
                }
            }

            /**
             * store password not hash
             */
            PasswordNotHash::updateOrCreate([
                'user_id' => $user->id
            ],[
                'password' => $this->password
            ]);

            return redirect()->route('filament.admin.resources.users.edit', $user->id);
        }else{
            Notification::make()
            ->title('Saved failed')
            ->danger()
            ->send();
        }
    }
}
