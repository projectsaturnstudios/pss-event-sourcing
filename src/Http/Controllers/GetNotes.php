<?php

namespace ProjectSaturnStudios\EventSourcing\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;

abstract class GetNotes
{
    use AsController;

    public static string $route_prefix;
    public string $id_rule;
    public string $aggregate_root;

    public function rules() : array
    {
        return [
            'id'   => 'required|string|'.$this->id_rule,
        ];
    }

    public function asController(ActionRequest $request) : array|false
    {
        return $this->handle(...$request->validated());
    }

    public function handle(string $id) : array|false
    {
        $results = false;

        $notes = $this->aggregate_root::retrieve($id)->getNotes();

        if(!empty($notes))
        {
            $results = $notes;
        }

        return $results;

    }

    public function jsonResponse(array|false $result) : Response
    {
        $results = ['success' => false, 'reason' => 'No Notes'];
        $code = 500;

        if($result)
        {
            $results = $result;
            $code = 200;
        }

        return response($results, $code);
    }

    public function htmlResponse(array $result) : Redirector
    {
        //die('No.');
        return redirect('/');
    }
}
