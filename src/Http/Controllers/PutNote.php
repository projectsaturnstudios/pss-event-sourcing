<?php

namespace ProjectSaturnStudios\EventSourcing\Actions\InternalAPI;

use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;

abstract class PutNote
{
    use AsController;

    public static string $route_prefix;
    public string $id_rule;
    public string $aggregate_root;

    public function rules() : array
    {
        return [
            'id'   => 'required|string|'.$this->id_rule,
            'note' => 'required|string',
        ];
    }

    public function asController(ActionRequest $request) : array|false
    {
        return $this->handle($request->validated());
    }

    public function handle(array $fields) : array|false
    {
        $results = false;

        if(!empty($fields))
        {
            $this->aggregate_root::retrieve($fields['id'])
                ->setNote($fields['note'], [
                    'created_by' => auth()->user()->id,
                    'created_name' => auth()->user()->username,
                    'created_at' => now()->toDateTimeString()
                ])
                ->persist();

            $results = $fields;
        }

        return $results;

    }

    public function jsonResponse(array|false $result) : Response
    {
        $results = ['success' => false, 'reason' => 'Nothing to Update'];
        $code = 500;

        if($result)
        {
            if(array_key_exists('success', $result))
            {
                $results = $result;
            }
            else
            {
                $code = 200;
                $results['success'] = true;
                $results['record'] = $result;
                unset($results['reason']);
            }
        }

        return response($results, $code);
    }

    public function htmlResponse(array $result) : Redirector
    {
        //die('No.');
        return redirect('/');
    }
}
