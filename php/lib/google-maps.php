<?php
/**
 * function to get Lat and Long by address
 *
 * @param string $address profile address
 * @throws \InvalidArgumentException if $address is not a string or insecure
 *
 **/
function getLatLongByAddress ($organizationStreet, $organizationCity, $organizationState, $organizationZip) : object {
	if(empty($organizationStreet) === true || (empty($organizationCity) === true) || (empty($organizationState) === true) || (empty($organizationZip) === true)) {
		throw(new \InvalidArgumentException("address content is empty or insecure"));
	}

	$organizationCity = filter_var($organizationCity, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$organizationState = filter_var($organizationState, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$organizationStreet = filter_var($organizationStreet, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$organizationZip = filter_var($organizationZip, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

	/*
	 * Builds the URL and request to the Google Maps API
	 */
	$url = 'https://maps.googleapis.com/maps/api/geocode/json?address=$organizationStreet+$organizationCity+$orgnizationState+$organizationZip&key=AIzaSyCu77ktLC6OfKCexHk-uQvpJdxL6gq3dd4';

		/*
		 * Creates a Guzzle client to make the Google Maps request
		 */
		$client = new \GuzzleHttp\Client();

		/*
		 * Send a GET request to the Google Maps API and get the body of the response
		 */
		$geocodeResponse = $client->get($url)->getBody();

		/*
		 * JSON decodes the response
		 */
		$geocodeData = json_decode($geocodeResponse);

		/*
		 * Initializes the response for the GeoCode Location
		 */
		$coordinates['lat'] = null;
		$coordinates['lng'] = null;

		/*
		 * Extract the latitude and longitude if the response is not empty
		 */
		if(!empty($geocodeData) && $geocodeData->status != 'ZERO_RESULTS' && isset($geocodeData->results) && isset($geocodeData->results[0])) {
			$coordinates['lat'] = $geocodeData->results[0]->geometry->location->lat;
			$coordinates['lng'] = $geocodeData->results[0]->geometry->location->lng;

		}

		/*
		 * Return the found coordinates
		 */
		return $coordinates;
}