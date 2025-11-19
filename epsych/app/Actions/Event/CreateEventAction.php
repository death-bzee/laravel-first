<?php

namespace App\Actions\Event;

use App\Models\Event;
use Illuminate\Support\Facades\Validator;

class CreateEventAction
{
    public static function handle(array $input): Event
    {
        Validator::make($input, [
            'title' => ['required', 'string', 'max:255'],
            'organization_id' => ['required', 'integer'],
            'classroom_id' => ['required', 'integer'],
            'event_status_id' => ['required', 'integer'],
            'event_date' => ['required', 'date'],
        ])->validate();

        return Event::create([
            'title' => $input['title'],
            'organization_id' => $input['organization_id'],
            'classroom_id' => $input['classroom_id'],
            'event_status_id' => $input['event_status_id'],
            'event_date' => $input['event_date'],
        ]);
    }
}
