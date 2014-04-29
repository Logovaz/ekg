<?php

class Ecg extends Eloquent {
    
    public function getExampleData($step) {
        $from = $step * 20 - 20;
        $to = $step * 20;
        
        $data = DB::table('ecg_example')->select('*')->whereBetween('id', array($from, $to))->get();
        $result = array();
        foreach($data as $key => $val) {
            $volts = explode('*', $val->values);
            $time = $val->timestamp;
            foreach($volts as $volt) {
                $time += 4;
                if($volt != 0) {
                    array_push($result, array(intval(substr($time, -7)), intval($volt)));
                }
        
            }
        }
        return $result;
    }
    
    public function getGraphs($userId) {
        try {
            $list = DB::table('graphs')->where('user_id', '=', $userId)->get();
            if(empty($list)) {
                return false;
            }
            return $list;
        } catch (Exception $e) {
            return false;
        }
    }
    
}