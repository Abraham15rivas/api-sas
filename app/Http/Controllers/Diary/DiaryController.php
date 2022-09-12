<?php

namespace App\Http\Controllers\Diary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use App\Models\{
    Diary
};

class DiaryController extends Controller
{
    use ApiResponser;

    protected $dateNow;
    protected $startOfWeek;
    protected $endtOfWeek;

    protected $validatorRules = [
        'datetime'      => 'required|date',
        'activity'      => 'required|string',
        'objective'     => 'required|string',
        'description'   => 'required|string',
        'state'         => 'required|string',
        'municipality'  => 'required|string',
        'place'         => 'required|string',
        'executed'      => 'required|boolean',
        'wingspan'      => 'required|in:Ministerial,Vicepresidencia Sectorial,Vicepresidencia Ejecutiva,Presidencial,No aplica',
        'observation'   => 'nullable|string'
    ];

    private function countOfActivitiesOfTheWeek() {
        $result = (object) array();

        try {
            $this->dateNow      = Carbon::now();
            $this->startOfWeek  = $this->dateNow->startOfWeek()->format('Y-m-d H:i:s');
            $this->endtOfWeek   = $this->dateNow->endOfWeek()->format('Y-m-d H:i:s');

            $planned = Diary::
                whereBetween(
                    DB::raw("created_at AT TIME ZONE 'UTC' AT TIME ZONE 'America/Caracas'"), [$this->startOfWeek, $this->endtOfWeek]
                )
                ->where('executed', false)
                ->get();

            $executed = Diary::whereBetween(
                    DB::raw("created_at AT TIME ZONE 'UTC' AT TIME ZONE 'America/Caracas'"), [$this->startOfWeek, $this->endtOfWeek]
                )
                ->where('executed', true)
                ->get();

            if ($planned->count() >= 5) {
                $result->planned['limit'] = true;
            }

            if ($executed->count() >= 5) {
                $result->executed['limit'] = true;
            }
        } catch (\Exception $e) {
            $this->reportError($e);
            return  response()->json($this->error("Ha ocurrido un error en el servidor", 500, $e));
        }

        return $result;
    }

    public function index() {
        try {
            $diaries = Diary::select(
                'datetime',
                'activity',
                'objective',
                'description',
                'state',
                'municipality',
                'place',
                'executed',
                'wingspan',
                'observation',
                'user_id',
                'institution_id'
            )
            ->get();
        } catch (\Exception $e) {
            $this->reportError($e);
            return  response()->json($this->error("Ha ocurrido un error en el servidor", 500, $e));
        }

        return $this->success($diaries, 'List diaries', 200);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), $this->validatorRules);

        if ($validator->fails()) {
            return response()->json(["success"=>false, $validator->errors()]);
        }

        $response = $this->countOfActivitiesOfTheWeek();

        if (isset($response->planned) && $response->planned['limit']) {
            if (isset($response->executed) && $response->executed['limit']) {
                return $this->error('Superó el límite de actividades de la semana', 200, $response);
            }
        }

        if ($request->executed == false && isset($response->planned) && $response->planned['limit']) {
            return $this->error('Superó el límite de actividades planificadas por semana', 200, $response);
        }

        if ($request->executed == true && isset($response->executed) && $response->executed['limit']) {
            return $this->error('Superó el límite de actividades ejecutadas por semana', 200, $response);
        }

        DB::beginTransaction();

        try {
            $diary                  = new Diary();
            $diary->datetime        = $request->datetime;
            $diary->activity        = $request->activity;
            $diary->objective       = $request->objective;
            $diary->description     = $request->description;
            $diary->state           = $request->state;
            $diary->municipality    = $request->municipality;
            $diary->place           = $request->place;
            $diary->executed        = $request->executed;
            $diary->observation     = $request->observation;
            $diary->wingspan        = $request->wingspan;
            $diary->user_id         = $request->user()->id;
            $diary->institution_id  = $request->user()->institution_id;
            $diary->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $this->reportError($e);
            return  response()->json($this->error("Ha ocurrido un error en el servidor", 500, $e));
        }

        return $this->success($diary,"Se ha guardado correctamente el registro de actividad");
    }

    public function show($id) {
        try {
            $diary = Diary::select(
                'id',
                'datetime',
                'activity',
                'objective',
                'description',
                'state',
                'municipality',
                'place',
                'executed',
                'wingspan',
                'observation',
                'user_id',
                'institution_id'
            )
            ->find($id);
        } catch (\Exception $e) {
            $this->reportError($e);
            return $this->error("Ha ocurrido un error en el servidor", 500, $e);
        }

        return $this->success($diary, 'diary', 200);
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), $this->validatorRules);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        DB::beginTransaction();

        try {
            $diary                  = Diary::findOrFail($id);
            $diary->datetime        = $request->datetime;
            $diary->activity        = $request->activity;
            $diary->objective       = $request->objective;
            $diary->description     = $request->description;
            $diary->state           = $request->state;
            $diary->municipality    = $request->municipality;
            $diary->place           = $request->place;
            $diary->executed        = $request->executed;
            $diary->observation     = $request->observation;
            $diary->wingspan        = $request->wingspan;
            $diary->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $this->reportError($e);
            return response()->json($this->error("Ha ocurrido un error en el servidor", 500, $e));
        }

        return response()->json('done');
    }

    public function destroy(Diary $diary) {
        try {
            if ($diary) {
                $diary->delete();
            }
        } catch (\Exception $e) {
            $this->reportError($e);
            return response()->json($this->error("Ha ocurrido un error en el servidor", 500, $e));
        }

        return $this->success([], 'done', 200);
    }

    public function exportReportExcel(Request $request) {
        return 'hola';
    }
}
