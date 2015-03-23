<?php

namespace sammaye\mongoyii2\helpers;

/**
 * A couple of good functions that I wanted to 
 * share to potentially make your life easier
 */
class BaseMongo
{
	/**
	 * Calculates the great-circle distance between two points, with
	 * the Vincenty formula. This function was so useful I thought I would 
	 * add it here for all you who might use geo indexes
	 * 
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
	
	/**
	 * This is analogous to SQLs own DATE() operator and takes a MongoDate object 
	 * and returns a timestamp of the actual date
	 * @param \MongoDate $mongoDate
	 * @return number
	 */
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
}