<?php

class EcgController extends Controller {
    
    public function example() {
        return View::make('ecg.example')->with('title', Lang::get('locale.example_title'));
    }
    
    public function getExampleData() {
        $ecg = new Ecg();
        return Response::json($ecg->getExampleData(Input::get('step'), Input::get('range')));
    }
    
    public function getPlot() {
        $ecg = new Ecg();
        return Response::json($ecg->getPlotData(Input::all()));
    }
    
    public function graph($user_id, $range) {
        $range = explode('_', $range);
        return View::make('graph.index')->with('title', Lang::get('locale.common_title') . Auth::user()->first_name . ' ' . Auth::user()->last_name)
               ->with('start', $range[0])->with('end', $range[1])->with('user_id', $user_id);
    }
}