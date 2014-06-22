<?php

class Ecg extends Eloquent {
    
    public function getExampleData($step, $range) {
        $start = substr(DB::table('ecg_example')->min('timestamp'), 0, -3);
        $from = $start + (($step-1) * $range);
        $to = $from + $range;
        
        $data = DB::table('ecg_example')->select('*')->whereBetween('timestamp', array($from . '000', $to . '000'))->get();
        $result = array();
        foreach($data as $key => $val) {
            $volts = explode('*', $val->values);
            $time = $val->timestamp;
            foreach($volts as $volt) {
                $time += 4;
                if($volt != 0) {
                    array_push($result, array(intval(substr($time, -8)), intval($volt)));
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
    
    public function getPlotData($args) {
        $start = $args['start'];
        $from = $start + (($args['step'] - 1) * $args['range']);
        $to = $from + $args['range'];
        
        $data = DB::table('tp_' . $args['user_id'] . '_ekg')->select('*')->whereBetween('timestamp', array($from . '000', $to . '000'))->get();
        $result = array();
        foreach($data as $key => $val) {
            $volts = explode('*', $val->values);
            $time = $val->timestamp;
            foreach($volts as $volt) {
                $time += 4;
                if($volt != 0) {
                    array_push($result, array(intval(substr($time, -8)), intval($volt)));
                }
        
            }
        }
        return $result;
    }
    
    /**
     * Get calendar set 
     * @param array $args - [year, month]
     * @return array
     */
    public function getCalendar($args) {
        $month = $args['month'];
        $year = $args['year'];
        
        /* first element is array of day names */
        $days = array(
            array(
                0 => array('n' => Lang::get('names.monday')),
                1 => array('n' => Lang::get('names.tuesday')),
                2 => array('n' => Lang::get('names.wednesday')),
                3 => array('n' => Lang::get('names.thursday')),
                4 => array('n' => Lang::get('names.friday')),
                5 => array('n' => Lang::get('names.saturday')),
                6 => array('n' => Lang::get('names.sunday'))
            )
        );
        
        $start = $year . '-' . $month . '-01 00:00:00'; /* first day of the month */
        $end = date('Y-m-t', strtotime($start)) . ' 00:00:00'; /* last day of the month */
        
        /* get all graphs of this month */
        $graphs = DB::table('graphs')->select('*')->where('start', '>', $start)->where('end', '<', $end)->get();
        
        $weeks = $this->numWeeks($month, $year); /* get number of weeks in this month */
        for($i = 1; $i < $weeks + 1; $i++) {
            $weekDays = $this->days($month, $year, $i); /* get current week days numbers */
            
            /* inserting information if there was a graph in this day */
            foreach ($weekDays as $dayIndex => $dayNumber) {
                $num = 0;
                foreach ($graphs as $graph) {
                    if(intval($dayNumber['n']) == intval(date('j', strtotime($graph->start)))) {
                        $weekDays[$dayIndex][$num]['start'] = date('d.m.Y G:i:s', strtotime($graph->start));
                        $weekDays[$dayIndex][$num]['end'] = date('d.m.Y G:i:s', strtotime($graph->end));
                        $weekDays[$dayIndex][$num]['startDay'] = date('j', strtotime($graph->start));
                        $weekDays[$dayIndex][$num]['endDay'] = date('j', strtotime($graph->end));
                        $num++;
                    }
                }
            }
            array_push($days, $weekDays);
        }
        return $days;
    }
    
    public function getYears($userId) {
        $firstGraph = DB::table('graphs')->min('start');
        $firstYear = date('Y', strtotime($firstGraph));
        $currentYear = date('Y');
        $years = array();
        if($currentYear != $firstYear) {
            for($i = $firstYear; $i <= $currentYear; $i++) {
                array_push($years, $i);
            }
        } else {
            array_push($years, $currentYear);
        }
        return $years;
    }
    
    private function numWeeks($month, $year) {
        $num_weeks = 4;
        $first_day = $this->firstDay($month, $year);
        if($first_day != 1) $num_weeks++;
    
        $widows = $first_day - 1;
        $fw_days = 7 - $widows;
        if($fw_days == 7) $fw_days = 0;
    
        $numdays = date("t",mktime(2, 0, 0, (int)$month, 1, $year));
        if(($numdays - $fw_days) > 28) $num_weeks++;
        return $num_weeks;
    }
    
    private function firstDay($month, $year) {
        $first_day = date("w", mktime(2, 0, 0, (int)$month, 1, $year));
        if($first_day == 0) $first_day = 7; # convert Sunday
        return $first_day;
    }
    
    private function days($month, $year, $week, $num_weeks=0) {
        $days = array();
        if($num_weeks == 0) $num_weeks = $this->numWeeks($month, $year);
        $first_day = $this->firstDay($month, $year);
        $widows = $first_day - 1;
        $fw_days= 7 - $widows;
    
        if($week == 1) {
            for($i = 0; $i < $widows; $i++) $days[]['n'] = 0;
            for($i = 1; $i <= $fw_days; $i++) $days[]['n'] = $i;
            return $days;
        }
    
        if($week != $num_weeks) {
            $first = $fw_days + (($week - 2) * 7);
            for($i = $first + 1; $i <= $first + 7; $i++) $days[]['n'] = $i;
            return $days;
        }
    
        $numdays = date("t",mktime(2, 0, 0, (int)$month, 1, $year));
        $orphans = $numdays - $fw_days - (($num_weeks - 2) * 7);
        $empty = 7 - $orphans;
        for($i = ($numdays - $orphans) + 1; $i <= $numdays; $i++) $days[]['n'] = $i;
        for($i = 0; $i < $empty; $i++) $days[]['n'] = 0;
        return $days;
    }
}