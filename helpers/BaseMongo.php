<?php

namespace sammaye\mongoyii2\helpers;

class BaseMongo
{
	/**
	 * Calculates the great-circle distance between two points, with
	 * the Vincenty formula.
	 * @param float $latitudeFrom Latitude of start point in [deg decimal]
	 * @param float $longitudeFrom Longitude of start point in [deg decimal]
	 * @param float $latitudeTo Latitude of target point in [deg decimal]
	 * @param float $longitudeTo Longitude of target point in [deg decimal]
	 * @param float $earthRadius Mean earth radius in [m]
	 * @return float Distance between points in [m] (same as earthRadius)
	 */
	public static function vincentyGreatCircleDistance(
			$latitudeFrom,
			$longitudeFrom,
			$latitudeTo,
			$longitudeTo,
			$earthRadius = 6371000
	){
		// convert from degrees to radians
		$latFrom = deg2rad($latitudeFrom);
		$lonFrom = deg2rad($longitudeFrom);
		$latTo = deg2rad($latitudeTo);
		$lonTo = deg2rad($longitudeTo);
	
		$lonDelta = $lonTo - $lonFrom;
		$a = pow(cos($latTo) * sin($lonDelta), 2) +
		pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
		$b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);
	
		$angle = atan2(sqrt($a), $b);
		return $angle * $earthRadius;
	}
	
	public static function date(\MongoDate $mongoDate)
	{
		return mktime(
			0,
			0,
			0,
			date('m', $mongoDate->sec),
			date('d', $mongoDate->sec),
			date('Y', $mongoDate->sec)
		);
	}
	
	public static function ago($datefrom,$dateto=-1)
	{
		// Defaults and assume if 0 is passed in that
		// its an error rather than the epoch
	
		if($datefrom instanceof \MongoDate){
			$datefrom = $datefrom->sec;
		}
		if($dateto instanceof \MongoDate){
			$dateto = $dateto->sec;
		}
	
		if($datefrom == 0) {
			return "A long time ago";
		}
		
		if($dateto == -1) {
			$dateto = time();
		}
	
		// Make the entered date into Unix timestamp from MySQL datetime field
		$datefrom = $datefrom;
	
		// Calculate the difference in seconds betweeen
		// the two timestamps
		$difference = $dateto - $datefrom;
	
		// Based on the interval, determine the
		// number of units between the two dates
		// From this point on, you would be hard
		// pushed telling the difference between
		// this function and DateDiff. If the $datediff
		// returned is 1, be sure to return the singular
		// of the unit, e.g. 'day' rather 'days'
	
		switch(true){
			// If difference is less than 60 seconds,
			// seconds is a good interval of choice
			case(strtotime('-1 min', $dateto) < $datefrom):
				$datediff = $difference;
				$res = ($datediff==1) ? $datediff.' second ago' : $datediff.' seconds ago';
				break;
				// If difference is between 60 seconds and
				// 60 minutes, minutes is a good interval
			case(strtotime('-1 hour', $dateto) < $datefrom):
				$datediff = floor($difference / 60);
				$res = ($datediff==1) ? $datediff.' minute ago' : $datediff.' minutes ago';
				break;
				// If difference is between 1 hour and 24 hours
				// hours is a good interval
			case(strtotime('-1 day', $dateto) < $datefrom):
				$datediff = floor($difference / 60 / 60);
				$res = ($datediff==1) ? $datediff.' hour ago' : $datediff.' hours ago';
				break;
				// If difference is between 1 day and 7 days
				// days is a good interval
			case(strtotime('-1 week', $dateto) < $datefrom):
				$day_difference = 1;
				while (strtotime('-'.$day_difference.' day', $dateto) >= $datefrom){
					$day_difference++;
				}
	
				$datediff = $day_difference;
				$res = ($datediff==1) ? 'yesterday' : $datediff.' days ago';
				break;
				// If difference is between 1 week and 30 days
				// weeks is a good interval
			case(strtotime('-1 month', $dateto) < $datefrom):
				$week_difference = 1;
				while (strtotime('-'.$week_difference.' week', $dateto) >= $datefrom){
					$week_difference++;
				}
	
				$datediff = $week_difference;
				$res = ($datediff==1) ? 'last week' : $datediff.' weeks ago';
				break;
				// If difference is between 30 days and 365 days
				// months is a good interval, again, the same thing
				// applies, if the 29th February happens to exist
				// between your 2 dates, the function will return
				// the 'incorrect' value for a day
			case(strtotime('-1 year', $dateto) < $datefrom):
				$months_difference = 1;
				while (strtotime('-'.$months_difference.' month', $dateto) >= $datefrom){
					$months_difference++;
				}
	
				$datediff = $months_difference;
				$res = ($datediff==1) ? $datediff.' month ago' : $datediff.' months ago';
	
				break;
				// If difference is greater than or equal to 365
				// days, return year. This will be incorrect if
				// for example, you call the function on the 28th April
				// 2008 passing in 29th April 2007. It will return
				// 1 year ago when in actual fact (yawn!) not quite
				// a year has gone by
			case(strtotime('-1 year', $dateto) >= $datefrom):
				$year_difference = 1;
				while (strtotime('-'.$year_difference.' year', $dateto) >= $datefrom){
					$year_difference++;
				}
	
				$datediff = $year_difference;
				$res = ($datediff==1) ? $datediff.' year ago' : $datediff.' years ago';
				break;
	
		}
		return $res;
	}
}