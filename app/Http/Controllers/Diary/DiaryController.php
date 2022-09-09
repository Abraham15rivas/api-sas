<?php

namespace App\Http\Controllers\Diary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Traits\ApiResponser;
use App\Models\{
    Diary
};

class DiaryController extends Controller
{
    use ApiResponser;

    protected $validatorRules = [
        'datetime'      => 'required|date',
        'activities'    => 'required|string',
        'objectives'    => 'required|string',
        'description'   => 'required|string',
        'state'         => 'required|string',
        'municipality'  => 'required|string',
        'place'         => 'required|string',
        'wingspan'      => 'required|in:Ministerial,Vicepresidencia Sectorial,Vicepresidencia Ejecutiva,Presidencial',
        'observation'   => 'required|string'
    ];

    public function index() {
        try {
            $diaries = Diary::select(
                'datetime',
                'activities',
                'objectives',
                'description',
                'state',
                'municipality',
                'place',
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
            return response()->json($validator->errors());
        }

        DB::beginTransaction();

        try {
            $diary                  = new Diary();
            $diary->datetime        = $request->datetime;
            $diary->activities      = $request->activities;
            $diary->objectives      = $request->objectives;
            $diary->description     = $request->description;
            $diary->state           = $request->state;
            $diary->municipality    = $request->municipality;
            $diary->place           = $request->place;
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

        return response()->json('done');
    }

    public function show($id) {
        try {
            $diary = Diary::select(
                'id',
                'datetime',
                'activities',
                'objectives',
                'description',
                'state',
                'municipality',
                'place',
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
            $diary->activities      = $request->activities;
            $diary->objectives      = $request->objectives;
            $diary->description     = $request->description;
            $diary->state           = $request->state;
            $diary->municipality    = $request->municipality;
            $diary->place           = $request->place;
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
}
