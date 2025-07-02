<?php

namespace ProjectSaturnStudios\EventSourcing\Concerns\Controllers;

use Illuminate\Http\JsonResponse;
use RDMIntegrations\Users\Projections\AdminProjection;
use ProjectSaturnStudios\EventSourcing\Aggregates\AlternativeAggregateRoot;

trait NoteManagementMethods
{
    public function get_notes(string $uuid): JsonResponse
    {
        $results = [];

        $notes = $this->aggregate_root::retrieve($uuid)->getNotes();

        if(!empty($notes))
        {
            $results = $notes;
        }

        return response()->json($results);
    }

    public function add_note(string $uuid): JsonResponse
    {
        $results = [
            'success' => false,
            'message' => 'Could not add note.',
        ];

        try {
            /** @var AlternativeAggregateRoot $aggy */
            $this->aggregate_root::retrieve($uuid)
                ->setNote(request()->get('note'), [
                    'created_by' => auth('admin')->user()->id,
                    'created_name' => auth('admin')->user()->name,
                    'created_at' => now()->toDateTimeString()
                ])->persist();

            /*user_activity('admin-user-log')
                ->event('note-left')
                ->performedOn($recipient = $this->perform_projection::where($this->uuid_key, '=', $uuid)->first())
                ->causedBy($causer = request()->user())
                ->withProperties([
                    'title' => 'Note created',
                    'icon'  => 'far fa-note',
                    "desc" => "{$causer->name} left a note for {$recipient->name}",
                    'date' => now()->timestamp,
                ])
                ->log('A note was created');
            */

            $results = [
                'success' => true,
                'message' => 'Note added successfully.',
            ];
        }
        catch (\Exception $e) {}


        return response()->json($results);
    }
}
