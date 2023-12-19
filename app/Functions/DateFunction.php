<?php

namespace App\Functions;

 class DateFunction
{
    //weekdayNumber goes  from 0 to 6 o being sunday and 6 saturday
    public static function getDateForSpecificDayBetweenDates($startDate, $endDate, $weekdayNumber)
    {
        $startDate = strtotime($startDate);
        $endDate = strtotime($endDate);
    
        $dateArr = array();
    
        do
        {
            if(date("w", $startDate) != $weekdayNumber)
            {
                $startDate += (24 * 3600); // add 1 day
            }
        } while(date("w", $startDate) != $weekdayNumber);
    
    
        while($startDate <= $endDate)
        {
            $dateArr[] = date('l, d-m-Y', $startDate);
            $startDate += (7 * 24 * 3600); // add 7 days
        }
    
        return($dateArr); 
    }
    
    // $year   = date("Y");
    
    // $dateArr = getDateForSpecificDayBetweenDates($year.'-01-01', $year.'-12-31', 0);
       
}
