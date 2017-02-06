<?php

require_once __DIR__ . '/AbstractReports.php';

class EventsByCountry extends AbstractReports {

	public function getReport($analytics){

		$VIEW_ID = "8339461";

		// Create the DateRange object.
		$dateRange = new Google_Service_AnalyticsReporting_DateRange();
		$dateRange->setStartDate("7daysAgo");
		$dateRange->setEndDate("today");

		// Create the Metrics object.
		$metric = new Google_Service_AnalyticsReporting_Metric();
		$metric->setExpression("ga:totalEvents");
		$metric->setAlias("events");

		// Create the Dimensions object.
		$dimension = new Google_Service_AnalyticsReporting_Dimension();
		$dimension->setName("ga:eventLabel");

		// Another dimension to slice by action
		$dimension_action = new Google_Service_AnalyticsReporting_Dimension();
		$dimension_action->setName("ga:eventAction");

		// Add another dimension by country
		$dimension_country = new Google_Service_AnalyticsReporting_Dimension();
		$dimension_country->setName("ga:city");

		//apply a Dimension filter clause and filter only the eventAction with value 'call'

		// Create Dimension Filter.
		$dimension_filter = new Google_Service_AnalyticsReporting_DimensionFilter();
		$dimension_filter->setDimensionName("ga:eventAction");
		$dimension_filter->setOperator("EXACT");
		$dimension_filter->setExpressions(["call"]);

		$dimension_filter_clause = new Google_Service_AnalyticsReporting_DimensionFilterClause();
		$dimension_filter_clause->setFilters($dimension_filter);

		// Create the ReportRequest object.
		$request = new Google_Service_AnalyticsReporting_ReportRequest();
		$request->setViewId($VIEW_ID);
		$request->setDateRanges($dateRange);
		$request->setMetrics([$metric]);
		$request->setDimensions([$dimension, $dimension_country]);
		$request->setDimensionFilterClauses([$dimension_filter_clause]);

		$body = new Google_Service_AnalyticsReporting_GetReportsRequest();
		$body->setReportRequests([$request]);

		$response = $analytics->reports->batchGet($body);

		return $this->convert($response);
	}

}
