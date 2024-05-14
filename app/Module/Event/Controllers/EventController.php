<?php

namespace App\Module\Event\Controllers;

use Illuminate\Http\Request;
use App\Module\User\Services\UserService;
use App\VenueModule\Rules\BookedVenueCheck;

class EventController
{
    public $user;
    public function __construct(private UserService $userService)
    {
        if (auth()->user()) {
            $this->user = auth()->user();
        }
    }
    public function createEvent(Request $request)
    {
        $request->validate([
            'event_name' => 'required',
            'description' => 'required',
            'date' => [
                'required',
                'after_or_equal:' . now()->addDays(10)->format('Y-m-d'),
            ],
            'time' => 'required|date_format:H:i',
            'venue_id' => ['required', 'exists:venues,id', new BookedVenueCheck()], //check not done yet
        ]);

        $eventData = $request->aall();

        $this->user->event->create($eventData);

        return successResponse('Your Event has been created');
    }
}
