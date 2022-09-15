<?php

namespace App\Http\Controllers\Diary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Traits\ApiResponser;
use App\Exports\DiaryExport;
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
    protected $user;

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

            $planned = Diary::select('id')
                ->whereBetween(
                    DB::raw("created_at AT TIME ZONE 'UTC' AT TIME ZONE 'America/Caracas'"), [$this->startOfWeek, $this->endtOfWeek]
                )
                ->where('executed', false)
                ->where('user_id',  $this->user->id)
                ->get();

            $executed = Diary::select('id')
                ->whereBetween(
                    DB::raw("created_at AT TIME ZONE 'UTC' AT TIME ZONE 'America/Caracas'"), [$this->startOfWeek, $this->endtOfWeek]
                )
                ->where('executed', true)
                ->where('user_id',  $this->user->id)
                ->get();

            $result->planned['limit']   = $planned->count() >= 5;
            $result->executed['limit']  = $executed->count() >= 5;
        } catch (\Exception $e) {
            $this->reportError($e);
            return  response()->json($this->error("Ha ocurrido un error en el servidor", 500, $e));
        }

        return $result;
    }

    public function index(Request $request) {
        try {
            $query = Diary::select(
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
                'created_at',
                'user_id',
                'institution_id'
            )
            ->with('user');

            if (isset($request["beginDate"]) && isset($request["endDate"])) {
                $query->whereBetween('created_at', [$request["beginDate"], $request["endDate"]]);
            }

            if (isset($request["range"]) && $request["range"] != "Todas") {
                $query->where('wingspan', $request["range"]);
            }

            if (isset($request["sector"]) && $request["sector"] != 0) {
                $query->where('institution_id', $request["sector"]);
            }

            if (isset($request["type"]) && $request["type"] != 2) {
                $query->where('executed', $request["type"]);
            }

            $diaries = $query->get();


            return $this->success($diaries, 'List diaries', 200);
        } catch (\Exception $e) {
            $this->reportError($e);
            return  response()->json($this->error("Ha ocurrido un error en el servidor", 500, $e));
        }

    }

    public function indexByUser(Request $request) {
        try {
            $selfDiaries = $request
                ->user()
                ->diaries()
                ->select(
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

        return $this->success($selfDiaries, 'List diaries user', 200);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), $this->validatorRules);

        if ($validator->fails()) {
            return response()->json(["success"=>false, $validator->errors()]);
        }

        $this->user = $request->user();

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
            $diary->user_id         = $this->user->id;
            $diary->institution_id  = $this->user->institution_id;
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

        return $this->success([], 'Eliminado correctamente.', 200);
    }

    public function exportReportExcel(Request $request) {
        try {
            $allData    = (array) [];
            $planned    = (array) [];
            $executed   = (array) [];

            $headers = (array) [
                'NRO.',
                'FECHA',
                'ACTIVIDAD',
                'OBJETIVO',
                'DESCRIPCION',
                'ESTADO',
                'MUNICIPIO',
                'LUGAR',
                'HORA',
                'ENVERGADURA',
                'OBSERVACIONES'
            ];
    
            if ($request->has('start') && $request->has('end')) {
                $start  = Carbon::parse($request->start)->format('Y-m-d H:i:s');
                $end    = Carbon::parse($request->end)->format('Y-m-d H:i:s');
            } else {
                $this->dateNow = Carbon::now();

                $start  = $this->dateNow->startOfWeek()->format('Y-m-d H:i:s');
                $end    = $this->dateNow->endOfWeek()->format('Y-m-d H:i:s');
            }

            $diaries = Diary::select(
                'id',
                DB::raw('date(datetime) as "date"'),
                'activity',
                'objective',
                'description',
                'state',
                'municipality',
                'place',
                DB::raw("to_char(datetime, 'HH12:MI:SS') as time"),
                'wingspan',
                'observation',
                'executed'
            )
            ->whereBetween(
                DB::raw("created_at AT TIME ZONE 'UTC' AT TIME ZONE 'America/Caracas'"), [$start, $end]
            )
            ->get();

            $executed = $diaries->where('executed', true)
                ->values()
                ->toArray();

            $planned = $diaries->where('executed', false)
                ->values()
                ->toArray();

            array_push($allData, $executed);
            array_push($allData, $planned);
        } catch (\Exception $e) {
            $this->reportError($e);
            return response()->json($this->error("Ha ocurrido un error en el servidor", 500, $e));
        }

        $titleReport = "Agenda VICEPRESIDENCIA SOCIAL_DVIAC";
        $reportExcel = (new DiaryExport($allData, $headers))->download("$titleReport.xlsx");

        return $reportExcel;

        // return response([
        //     "success"=>true,
        //     "data" => 'data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,'.base64_encode($reportExcel)
        // ],200);
    }
}
