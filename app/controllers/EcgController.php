<?php
/**
 * ECG controller to make most functions
 * with ECG data coords
 * @author Iliya Bubenschikov <mephis.oct@gmail.com>
 * @version 1.0
 */
class EcgController extends Controller {
    
    /**
     * Show ECG example page
     * @author Iliya Bubenschikov <mephis.oct@gmail.com>
     * @return compiled View
     */
    public function example() {
        return View::make('ecg.example')->with('title', Lang::get('locale.example_title'));
    }
    
    /**
     * Returns JSON data for example page
     * @author Iliya Bubenschikov <mephis.oct@gmail.com>
     * @return array - json format
     */
    public function getExampleData() {
        $ecg = new Ecg(); /* Model initialize */
        return Response::json($ecg->getExampleData(Input::get('step'), Input::get('range')));
    }
    
    /**
     * Returns JSON data for graph page
     * @author Iliya Bubenschikov <mephis.oct@gmail.com>
     * @see Input::all() for research args
     * @return array - json format
     */
    public function getPlot() {
        $ecg = new Ecg(); /* Model initialize */
        return Response::json($ecg->getPlotData(Input::all()));
    }
    
    /**
     * Show graph page with a lot of data
     * @author Iliya Bubenschikov <mephis.oct@gmail.com>
     * @param int $user_id
     * @param array $range - unix timestamps
     * @return compiled View
     */
    public function graph($user_id, $range) {
        /* TODO make check if it's ID of current user or his doctor */
        $range = explode('_', $range);
        $date['month'] = date('m', $range[0]);
        $date['year'] = date('Y', $range[0]);
        $ecg = new Ecg(); /* Model initialize */
        $years = $ecg->getYears($user_id);
        return View::make('graph.index')->with('title', Lang::get('locale.common_title') . Auth::user()->first_name . ' ' . Auth::user()->last_name)
               ->with('start', $range[0])->with('end', $range[1])->with('user_id', $user_id)->with('date', $date)->with('years', $years);
    }
    
    /**
     * Returns JSON data for calendar
     * @author Iliya Bubenschikov <mephis.oct@gmail.com>
     * @return array - json format
     */
    public function getCalendar() {
        $ecg = new Ecg();
        return Response::json($ecg->getCalendar(Input::all()));
    }
}