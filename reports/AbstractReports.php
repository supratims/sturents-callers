<?php
require_once __DIR__ . '/../common.php';

abstract class AbstractReports {

	protected abstract function name();

	public function convert($reports){
		$objects = [];
		for ($reportIndex = 0; $reportIndex<count($reports); $reportIndex++){
			$report = $reports[$reportIndex];
			$header = $report->getColumnHeader();
			$dimensionHeaders = $header->getDimensions();
			$metricHeaders = $header->getMetricHeader()->getMetricHeaderEntries();
			$rows = $report->getData()->getRows();

			for ($rowIndex = 0; $rowIndex<count($rows); $rowIndex++){
				$row = $rows[$rowIndex];
				$dimensions = $row->getDimensions();
				$metrics = $row->getMetrics();
				$object = [];
				for ($i = 0; $i<count($dimensionHeaders) && $i<count($dimensions); $i++){

					//print($dimensionHeaders[$i] . ": " . $dimensions[$i] . "\n");  //prints:  'ga:country: Italy'
					$object[$dimensionHeaders[$i]] = $dimensions[$i];
				}

				for ($j = 0; $j<count($metricHeaders) && $j<count($metrics); $j++){
					$entry = $metricHeaders[$j];
					$values = $metrics[$j];
					// print("Metric type: " . $entry->getType() . "\n");
					for ($valueIndex = 0; $valueIndex<count($values->getValues()); $valueIndex++){
						$value = $values->getValues()[$valueIndex];
						//print($entry->getName() . ": " . $value . "\n"); // prints 'users: 100'
						$object[$entry->getName()] = $value;
					}
				}
				$objects[] = $object;
			}
		}

		return $objects;
	}

	public function printResults(Array $objects, array $dimensions, $metric){

		/*
		var_export($objects);

		return;
		*/

		$table = '<table> <thead><th>Country</th><th>Users</th></thead><tbody>';

		foreach ($objects as $object){
			$row = '<tr>';
			foreach ($dimensions as $dimension){
				$row .= "<td>{$object[$dimension]}</td>";
			}
			$row .= "<td>{$object[$metric]}</td>";
			$row .= "</tr>";

			$table .= $row;
		}

		$table .= '</tbody></table>';

		echo $table;
	}

	public function saveJson($objects){
		$file_name = date("Y-m-d").'-'.$this->name().'.json';

		file_put_contents(JSON_DIR.$file_name, json_encode($objects));
	}
}
