<?php

require_once __DIR__ . '/AbstractReports.php';

class UsersByCountry extends AbstractReports {

	public function getReport($analytics){

		$VIEW_ID = "8339461";

		// Create the DateRange object.
		$dateRange = new Google_Service_AnalyticsReporting_DateRange();
		$dateRange->setStartDate("7daysAgo");
		$dateRange->setEndDate("today");

		// Create the Metrics object.
		$sessions = new Google_Service_AnalyticsReporting_Metric();
		$sessions->setExpression("ga:users");
		$sessions->setAlias("users");

		// Create the Dimensions object.
		$dimension_city = new Google_Service_AnalyticsReporting_Dimension();
		$dimension_city->setName("ga:country");

		// Create the ReportRequest object.
		$request = new Google_Service_AnalyticsReporting_ReportRequest();
		$request->setViewId($VIEW_ID);
		$request->setDateRanges($dateRange);
		$request->setMetrics([$sessions]);
		$request->setDimensions([$dimension_city]);

		$body = new Google_Service_AnalyticsReporting_GetReportsRequest();
		$body->setReportRequests([$request]);

		$response = $analytics->reports->batchGet($body);

		return $this->convert($response);
	}

}
