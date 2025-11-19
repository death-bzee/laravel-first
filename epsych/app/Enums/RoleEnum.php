<?php

namespace App\Enums;

enum RoleEnum: string
{
    case Psychologist = 'psychologist';
    case SocialPedagogue = 'social_pedagogue';
    case HomeroomTeacher = 'homeroom_teacher';
    case StudentAffairsManager = 'student_affairs_manager';
    case CareerAdvisor = 'career_advisor';
    case CorrectionalServiceDistrict = 'correctional_service_district';
    case CorrectionalServiceRegion = 'correctional_service_region';

    /**
     * Возвращает роли, требующие обязательного organization_selected_id.
     *
     * @return array
     */
    public static function requiresOrganization(): array
    {
        return [
            self::Psychologist,
            self::SocialPedagogue,
            self::HomeroomTeacher,
            self::StudentAffairsManager,
            self::CareerAdvisor,
        ];
    }

    public static function requiresBullyingCase(): array
    {
        return [
            self::StudentAffairsManager,
            self::CorrectionalServiceDistrict,
            self::CorrectionalServiceRegion,
        ];
    }

    public static function requiresClasroom(): array
    {
        return [
            self::HomeroomTeacher,
        ];
    }

    public static function requiresOrganizationContains(string $role): bool
    {
        return in_array($role, array_map(fn($role) => $role->value, self::requiresOrganization()), true);
    }

    public static function requiresClasroomContains(string $role): bool
    {
        return in_array($role, array_map(fn($role) => $role->value, self::requiresClasroom()), true);
    }

    /**
     * Возвращает локализованное описание для кейсов enum.
     *
     * @return string
     */
    public function label(): string
    {
        return match($this) {
            self::Psychologist => __("Психолог"),
            self::SocialPedagogue => __("Социальный педагог"),
            self::HomeroomTeacher => __("Классный руководитель"),
            self::StudentAffairsManager => __("Заместитель директора по воспитательной работе"),
            self::CareerAdvisor => __("Профориентатор"),
            self::CorrectionalServiceDistrict => __("Коррекционная служба (район)"),
            self::CorrectionalServiceRegion => __("Коррекционная служба (область)"),
        };
    }
}
