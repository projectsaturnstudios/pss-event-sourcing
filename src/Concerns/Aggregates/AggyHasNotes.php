<?php

namespace ProjectSaturnStudios\EventSourcing\Concerns\Aggregates;

use ProjectSaturnStudios\EventSourcing\Events\NoteSet;

trait AggyHasNotes
{
    protected array $notes = [];

    protected function applyNoteSet(NoteSet $event) : void
    {
        $this->notes[] = [
            'note' => $event->note,
            'username' => $event->metaData()['created_name'],
            'date' => date('Y-m-d H:i:s', strtotime($event->metaData()['created_at']))
        ];
    }

    public function setNote(string $note, array $meta_data = []) : static
    {
        $event = new NoteSet($note);
        $event->setMetaData($meta_data);
        $this->recordThat($event);

        return $this;
    }

    public function getNotes() : array
    {
        $results = [];

        $notes = collect($this->notes)->sortByDesc('date')->toArray();

        foreach($notes as $note)
        {
            $note['date'] = date('M d, Y h:iA', strtotime($note['date']));
            $results[] = $note;
        }

        return $results;
    }
}
