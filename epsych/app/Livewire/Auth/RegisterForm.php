<?php

namespace App\Livewire\Auth;

use App\Enums\RoleEnum;
use App\Models\Classroom;
use App\Models\Concerns\District;
use App\Models\Concerns\Region;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Laravel\Jetstream\Jetstream;
use Livewire\Attributes\Computed;
use Livewire\Component;

class RegisterForm extends Component
{
    public $name;
    public $surname;
    public $patronymic;
    public $email;
    public $password;
    public $password_confirmation;
    public array $roles = [];
    public ?string $role_selected = null;
    public array $organizations = [];
    public ?int $organization_selected_id = null;
    public array $regions = [];
    public ?int $region_selected_id = null;
    public array $districts = [];
    public ?int $district_selected_id = null;

    public $terms;

    public array $classrooms = [];
    public ?int $classroom_selected_id = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'surname' => 'required|string|max:255',
        'patronymic' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'terms' => 'accepted|required',
        'role_selected' => 'required',
    ];

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName, $this->rules());
    }

    public function rules()
    {
        $rules = $this->rules;

        $rules['password'] = [
            'required',
            'string',
            Password::min(8) // Минимум 12 символов
                ->letters() // Буквы обязательны
                ->mixedCase() // Должны быть буквы верхнего и нижнего регистра
                ->numbers() // Должны быть цифры
                ->symbols() // Должны быть спецсимволы
                ->uncompromised(), // Проверка на утечки паролей
            'confirmed',
        ];

        if (collect(RoleEnum::requiresOrganization())->map(fn($role) => $role->value)->contains($this->role_selected)) {
            $rules['organization_selected_id'] = 'required';
        }

        if ($this->role_selected === RoleEnum::CorrectionalServiceRegion->value) {
            $rules['region_selected_id'] = 'required';
        }

        if ($this->role_selected === RoleEnum::CorrectionalServiceDistrict->value) {
            $rules['district_selected_id'] = 'required';
        }

        return $rules;
    }

    public function mount(): void
    {
        if (!Jetstream::hasTermsAndPrivacyPolicyFeature()) {
            unset($this->rules['terms']);
        }

        $this->setOptions();
    }

    public function setOptions(): void
    {
        $this->roles = $this->getRolesOptions();
    }


    #[Computed]
    public static function getRolesOptions(): array
    {
        return collect(RoleEnum::cases())->mapWithKeys(function ($case) {
            return [$case->value => $case->label()];
        })->toArray();
    }

    public function updatedRoleSelected(): void
    {
        $this->regions = [];
        $this->districts = [];
        $this->organizations = [];
        $this->classrooms = [];

        $this->reset('region_selected_id', 'district_selected_id', 'organization_selected_id', 'classroom_selected_id');

        // Если роль связана с организацией
        if (RoleEnum::requiresOrganizationContains($this->role_selected)) {
            $this->organizations = Organization::all()
                ->mapWithKeys(fn($organization) => [
                    $organization->id => "{$organization->bin} {$organization->title}"
                ])
                ->toArray();

            // Если роль связана с классом
            if (RoleEnum::requiresClasroomContains($this->role_selected)) {
                $this->classrooms = Classroom::all()
                    ->mapWithKeys(fn($classroom) => [
                        $classroom->id => "{$classroom->grade}{$classroom->letter}"
                    ])
                    ->toArray();
            }
        }

        if ($this->role_selected === RoleEnum::CorrectionalServiceDistrict->value) {
            $this->regions = Region::all()->pluck('title', 'id')->toArray();
        }

        if ($this->role_selected === RoleEnum::CorrectionalServiceRegion->value) {
            $this->regions = Region::all()->pluck('title', 'id')->toArray();
        }
    }

    public function updatedRegionSelectedId($value): void
    {
        if ($this->role_selected !== RoleEnum::CorrectionalServiceRegion->value) {
            $this->districts = District::query()->where('region_id', $value)->get()->pluck('title', 'id')->toArray();
        }
    }

    public function register(): void
    {
        $this->validate($this->rules());

        $user = User::query()->create([
            'name' => $this->name,
            'surname' => $this->surname,
            'patronymic' => $this->patronymic,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'organization_id' => $this->organization_selected_id ?: null,
            'region_id' => $this->region_selected_id ?: null,
            'district_id' => $this->district_selected_id ?: null,
            'password_changed_at' => now()
        ]);

        // Назначение роли пользователю
        $user->assignRole($this->role_selected);

        //Сохраняем связь с класса с пользователем
        if (RoleEnum::requiresClasroomContains($this->role_selected) && $this->classroom_selected_id) {
            $user->classrooms()->attach($this->classroom_selected_id, [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        $this->redirect(route('social-passport-school'), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.auth.register-form');
    }
}
