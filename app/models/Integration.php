<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Integration extends Eloquent {

	protected $guarded = array('id','create_at', "updated_at");

	public static function retrieveData($name, $type, $values) {
		$integrationPath = app_path() . '/config/integrations/' . $name . '.json';
		if (file_exists($integrationPath)) {
			$integrationJSON = file_get_contents($integrationPath);
			$integrationWrapper = json_decode($integrationJSON);
			if ($integrationWrapper != NULL) {
				$integration = $integrationWrapper->integration;

				// Start building the URL
				$url = $integration->url . '/' . $integration->version . '/';

				// Get the global parameters
				$params = array();
				foreach ($integration->global_parameters as $global_name => $global_parameter) {
					if ($global_parameter == 'ARRAY_DEFINED')
						if (isset($values[$global_name])) {
							$global_parameter = $values[$global_name];
						}
						else {
							echo('ERROR: Missing required global parameter value \'' . $global_name . '\'');
							return false;
						}
					if ($global_parameter != '')
						$params[$global_name] = $global_parameter;
				} 

				// Consume the endpoint definitions
				$typecheck = 0;
				foreach ($integration->endpoints as $endpoint) {
					if ($endpoint->type == $type) {
						$typecheck++;
						if ($typecheck == 1) {
							$url .= $endpoint->path . '?';
							foreach ($endpoint->parameters as $name => $parameter) {
								if ($parameter == 'ARRAY_DEFINED')
									if (isset($values[$name])) {
										$parameter = $values[$name];
									}
									else {
										echo('ERROR: Missing required parameter value \'' . $name . '\'');
										return false;
									}
								if ($parameter != '')
									$params[$name] = $parameter;
							} 
						}
					}
				}
				if ($typecheck < 1) {
					echo('ERROR: An integration of type \'' . $type . '\'was not found');
					return false;
				}
				elseif ($typecheck > 1) {
					echo('WARNING: Multiple integrations of type \'' . $type . '\'were found; only the first was used');
				}

				// Add the parameters to the URL
				$url .= http_build_query($params);

				// Get the API's response
				if ($response = file_get_contents($url)) {
					$data = json_decode($response, true);
					if ($data != NULL) {
						return $data;
					}
					else {
						echo('ERROR: JSON decode fail on response from ' . $url);
					}
				}
				else {
					echo('ERROR: Failed to return any data at ' . $url);
				}
			}
			else {
				echo('ERROR: JSON decode fail on ' . $integrationPath);
			}
		}
		else {
			echo('ERROR: File not found: ' . $integrationPath);
		}
		return false;
	}

}
