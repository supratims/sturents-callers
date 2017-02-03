<?php

class Report {
  function getReport($analytics) {

    $VIEW_ID = "8339461";

    // Create the DateRange object.
    $dateRange = new Google_Service_AnalyticsReporting_DateRange();
    $dateRange->setStartDate("30daysAgo");
    $dateRange->setEndDate("today");

    // Create the Metrics object.
    $sessions = new Google_Service_AnalyticsReporting_Metric();
    $sessions->setExpression("ga:users");
    $sessions->setAlias("users");

    // Create the ReportRequest object.
    $request = new Google_Service_AnalyticsReporting_ReportRequest();
    $request->setViewId($VIEW_ID);
    $request->setDateRanges($dateRange);
    $request->setMetrics(array($sessions));

    $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
    $body->setReportRequests( array( $request) );
    return $analytics->reports->batchGet( $body );
  }

  function printResults($reports) {
    for ( $reportIndex = 0; $reportIndex < count( $reports ); $reportIndex++ ) {
      $report = $reports[ $reportIndex ];
      $header = $report->getColumnHeader();
      $dimensionHeaders = $header->getDimensions();
      $metricHeaders = $header->getMetricHeader()->getMetricHeaderEntries();
      $rows = $report->getData()->getRows();

      for ( $rowIndex = 0; $rowIndex < count($rows); $rowIndex++) {
        $row = $rows[ $rowIndex ];
        $dimensions = $row->getDimensions();
        $metrics = $row->getMetrics();
        for ($i = 0; $i < count($dimensionHeaders) && $i < count($dimensions); $i++) {
          print($dimensionHeaders[$i] . ": " . $dimensions[$i] . "\n");
        }

        for ($j = 0; $j < count( $metricHeaders ) && $j < count( $metrics ); $j++) {
          $entry = $metricHeaders[$j];
          $values = $metrics[$j];
          print("Metric type: " . $entry->getType() . "\n" );
          for ( $valueIndex = 0; $valueIndex < count( $values->getValues() ); $valueIndex++ ) {
            $value = $values->getValues()[ $valueIndex ];
            print($entry->getName() . ": " . $value . "\n");
          }
        }
      }
    }
  }
}
