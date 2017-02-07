<?php

require_once __DIR__ . '/AbstractReports.php';

class EventsByCountry extends AbstractReports {

	public function getReport($analytics){

		$VIEW_ID = "8339461";

		// Create the DateRange object.
		$dateRange = new Google_Service_AnalyticsReporting_DateRange();
		$dateRange->setStartDate("1daysAgo");
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

		// Add another dimension by city
		$dimension_city = new Google_Service_AnalyticsReporting_Dimension();
		$dimension_city->setName("ga:city");

		// Add another dimension by country
		$dimension_country = new Google_Service_AnalyticsReporting_Dimension();
		$dimension_country->setName("ga:country");

		// Add another dimension by lat
		$dimension_latitude = new Google_Service_AnalyticsReporting_Dimension();
		$dimension_latitude->setName("ga:latitude");

		// Add another dimension by lng
		$dimension_longitude = new Google_Service_AnalyticsReporting_Dimension();
		$dimension_longitude->setName("ga:longitude");

		//apply a Dimension filter clause and filter only the eventAction with value 'call'
		// Create Dimension Filter for matching dimension 'call'
		$dimension_filter_call = new Google_Service_AnalyticsReporting_DimensionFilter();
		$dimension_filter_call->setDimensionName("ga:eventAction");
		$dimension_filter_call->setOperator("EXACT");
		$dimension_filter_call->setExpressions(["call"]);

		// Create Dimension Filter for matching dimension 'enquiry'
		$dimension_filter_enquiry = new Google_Service_AnalyticsReporting_DimensionFilter();
		$dimension_filter_enquiry->setDimensionName("ga:eventAction");
		$dimension_filter_enquiry->setOperator("EXACT");
		$dimension_filter_enquiry->setExpressions(["enquiry"]);

		$dimension_filter_clause = new Google_Service_AnalyticsReporting_DimensionFilterClause();
		$dimension_filter_clause->setFilters([$dimension_filter_call, $dimension_filter_enquiry]);

		// Create the ReportRequest object.
		$request = new Google_Service_AnalyticsReporting_ReportRequest();
		$request->setViewId($VIEW_ID);
		$request->setDateRanges($dateRange);
		$request->setMetrics([$metric]);
		$request->setDimensions([$dimension, $dimension_city, $dimension_country, $dimension_latitude, $dimension_longitude]);
		$request->setDimensionFilterClauses([$dimension_filter_clause]);

		$body = new Google_Service_AnalyticsReporting_GetReportsRequest();
		$body->setReportRequests([$request]);

		$response = $analytics->reports->batchGet($body);

		return $this->convert($response);
	}

}
