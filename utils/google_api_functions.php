<?php
/*

 About:
 Author: Raul Perusquia <rulovico@gmail.com>
 Created: 09-07-2019 13:35:12 MYT, Kuala Lumpur, Malaysia

 Copyright (c) 2019, Inikoo

 Version 2.0
*/


/**
 * Initializes an Analytics Reporting API V4 service object.
 *
 * @return An authorized Analytics Reporting API V4 service object.
 */
function initializeAnalytics() {

    // Use the developers console and download your service account
    // credentials in JSON format. Place them in this directory or
    // change the key file location if necessary.
    $KEY_FILE_LOCATION = 'keyring/google_api_key.json';

    // Create and configure a new client object.
    $client = new Google_Client();
    $client->setApplicationName("Aurora Analytics");
    $client->setAuthConfig($KEY_FILE_LOCATION);
    $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
    $analytics = new Google_Service_AnalyticsReporting($client);

    return $analytics;
}

function initializeSearch() {

    $KEY_FILE_LOCATION = 'keyring/google_api_key.json';


    $client = new Google_Client();
    $client->setApplicationName("Aurora Search Console");
    $client->setAuthConfig($KEY_FILE_LOCATION);
    $client->setScopes(['https://www.googleapis.com/auth/webmasters.readonly']);
    $webmastersService = new Google_Service_Webmasters($client);

    return $webmastersService;
}

function get_google_analytics_report($analytics, $google_analytics_view_id, $account_code,$website_key, $date_interval, $device) {

    include 'conf/google_analytics_metrics.php';

    $date_range = new Google_Service_AnalyticsReporting_DateRange();
    $date_range->setStartDate($date_interval['From']);
    $date_range->setEndDate($date_interval['To']);


    $metrics_data = get_google_analytics_metrics_data();

    $metrics = array();


    foreach ($metrics_data as $_metric_data) {
        $metric = new Google_Service_AnalyticsReporting_Metric();
        $metric->setExpression($_metric_data[0]);
        $metric->setAlias($_metric_data[1]);

        $metrics[] = $metric;
    }


    $website = new Google_Service_AnalyticsReporting_Dimension();
    $website->setName("ga:dimension2");
    $webpage_key = new Google_Service_AnalyticsReporting_Dimension();
    $webpage_key->setName("ga:dimension1");

    $segmentDimensions = new Google_Service_AnalyticsReporting_Dimension();
    $segmentDimensions->setName("ga:segment");

    $dimensionFilter = new Google_Service_AnalyticsReporting_SegmentDimensionFilter();
    $dimensionFilter->setDimensionName("ga:dimension2");
    $dimensionFilter->setOperator("EXACT");
    $dimensionFilter->setExpressions(array($account_code.'.'.$website_key));



    print $account_code.'.'.$website_key."\n";

    $segmentFilterClause = new Google_Service_AnalyticsReporting_SegmentFilterClause();
    $segmentFilterClause->setDimensionFilter($dimensionFilter);

    $orFiltersForSegment = new Google_Service_AnalyticsReporting_OrFiltersForSegment();
    $orFiltersForSegment->setSegmentFilterClauses(array($segmentFilterClause));


    if ($device == "all") {

        $simpleSegment = new Google_Service_AnalyticsReporting_SimpleSegment();
        $simpleSegment->setOrFiltersForSegment(array($orFiltersForSegment));

    } else {
        $dimensionDeviceFilter = new Google_Service_AnalyticsReporting_SegmentDimensionFilter();
        $dimensionDeviceFilter->setDimensionName("ga:deviceCategory");
        $dimensionDeviceFilter->setOperator("EXACT");
        $dimensionDeviceFilter->setExpressions(array($device));

        $segmentDeviceFilterClause = new Google_Service_AnalyticsReporting_SegmentFilterClause();
        $segmentDeviceFilterClause->setDimensionFilter($dimensionDeviceFilter);

        $orFiltersForDeviceSegment = new Google_Service_AnalyticsReporting_OrFiltersForSegment();
        $orFiltersForDeviceSegment->setSegmentFilterClauses(array($segmentDeviceFilterClause));

        $simpleSegment = new Google_Service_AnalyticsReporting_SimpleSegment();
        $simpleSegment->setOrFiltersForSegment(
            array(
                $orFiltersForSegment,
                $orFiltersForDeviceSegment
            )
        );


    }

    $segmentFilter = new Google_Service_AnalyticsReporting_SegmentFilter();
    $segmentFilter->setSimpleSegment($simpleSegment);

    $segmentDefinition = new Google_Service_AnalyticsReporting_SegmentDefinition();
    $segmentDefinition->setSegmentFilters(array($segmentFilter));

    $dynamicSegment = new Google_Service_AnalyticsReporting_DynamicSegment();
    $dynamicSegment->setSessionSegment($segmentDefinition);
    $dynamicSegment->setName("website");

    $segment = new Google_Service_AnalyticsReporting_Segment();
    $segment->setDynamicSegment($dynamicSegment);


    // Create the ReportRequest object.
    $request = new Google_Service_AnalyticsReporting_ReportRequest();
    $request->setViewId($google_analytics_view_id);
    $request->setDateRanges($date_range);
    $request->setDimensions(
        array(
            $website,
            $webpage_key,
            $segmentDimensions
        )
    );
    $request->setSegments(array($segment));

    $request->setMetrics($metrics);

    $request->setPageSize(10000);


    $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
    $body->setReportRequests(array($request));

    $report = array();

    $data = $analytics->reports->batchGet($body);


   // print_r($data);

    $report[] = $data;

    $cnt = 0;
    while ($data->reports[0]->nextPageToken > 0 ) {
        // There are more rows for this report.
        $body->reportRequests[0]->setPageToken($data->reports[0]->nextPageToken);
        $data     = $analytics->reports->batchGet($body);
        $report[] = $data;
        $cnt++;
    }

    return $report;


}

/**
 * Queries the Analytics Reporting API V4.
 *
 * @param service An authorized Analytics Reporting API V4 service object.
 *
 * @return The Analytics Reporting API V4 response.
 */
function getReport_old($analytics, $google_analytics_view_id, $website, $date_interval, $device) {

    include 'conf/google_analytics_metrics.php';

    $date_range = new Google_Service_AnalyticsReporting_DateRange();
    $date_range->setStartDate($date_interval['From']);
    $date_range->setEndDate($date_interval['To']);


    $metrics_data = get_google_analytics_metrics_data();

    $metrics = array();


    foreach ($metrics_data as $_metric_data) {
        $metric = new Google_Service_AnalyticsReporting_Metric();
        $metric->setExpression($_metric_data[0]);
        $metric->setAlias($_metric_data[1]);

        $metrics[] = $metric;
    }


    $hostname = new Google_Service_AnalyticsReporting_Dimension();
    $hostname->setName("ga:hostname");
    $pagePath = new Google_Service_AnalyticsReporting_Dimension();
    $pagePath->setName("ga:pagePath");

    $segmentDimensions = new Google_Service_AnalyticsReporting_Dimension();
    $segmentDimensions->setName("ga:segment");

    $dimensionFilter = new Google_Service_AnalyticsReporting_SegmentDimensionFilter();
    $dimensionFilter->setDimensionName("ga:hostname");
    $dimensionFilter->setOperator("EXACT");
    $dimensionFilter->setExpressions(array($website));


    $segmentFilterClause = new Google_Service_AnalyticsReporting_SegmentFilterClause();
    $segmentFilterClause->setDimensionFilter($dimensionFilter);

    $orFiltersForSegment = new Google_Service_AnalyticsReporting_OrFiltersForSegment();
    $orFiltersForSegment->setSegmentFilterClauses(array($segmentFilterClause));


    if ($device == "all") {

        $simpleSegment = new Google_Service_AnalyticsReporting_SimpleSegment();
        $simpleSegment->setOrFiltersForSegment(array($orFiltersForSegment));

    } else {
        $dimensionDeviceFilter = new Google_Service_AnalyticsReporting_SegmentDimensionFilter();
        $dimensionDeviceFilter->setDimensionName("ga:deviceCategory");
        $dimensionDeviceFilter->setOperator("EXACT");
        $dimensionDeviceFilter->setExpressions(array($device));

        $segmentDeviceFilterClause = new Google_Service_AnalyticsReporting_SegmentFilterClause();
        $segmentDeviceFilterClause->setDimensionFilter($dimensionDeviceFilter);

        $orFiltersForDeviceSegment = new Google_Service_AnalyticsReporting_OrFiltersForSegment();
        $orFiltersForDeviceSegment->setSegmentFilterClauses(array($segmentDeviceFilterClause));

        $simpleSegment = new Google_Service_AnalyticsReporting_SimpleSegment();
        $simpleSegment->setOrFiltersForSegment(
            array(
                $orFiltersForSegment,
                $orFiltersForDeviceSegment
            )
        );


    }

    $segmentFilter = new Google_Service_AnalyticsReporting_SegmentFilter();
    $segmentFilter->setSimpleSegment($simpleSegment);

    $segmentDefinition = new Google_Service_AnalyticsReporting_SegmentDefinition();
    $segmentDefinition->setSegmentFilters(array($segmentFilter));

    $dynamicSegment = new Google_Service_AnalyticsReporting_DynamicSegment();
    $dynamicSegment->setSessionSegment($segmentDefinition);
    $dynamicSegment->setName("website");

    $segment = new Google_Service_AnalyticsReporting_Segment();
    $segment->setDynamicSegment($dynamicSegment);


    // Create the ReportRequest object.
    $request = new Google_Service_AnalyticsReporting_ReportRequest();
    $request->setViewId($google_analytics_view_id);
    $request->setDateRanges($date_range);
    $request->setDimensions(
        array(
            $hostname,
            $pagePath,
            $segmentDimensions
        )
    );
    $request->setSegments(array($segment));

    $request->setMetrics($metrics);

    $request->setPageSize(10000);


    $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
    $body->setReportRequests(array($request));

    $report = array();

    $data = $analytics->reports->batchGet($body);

    $report[] = $data;

    $cnt = 0;
    while ($data->reports[0]->nextPageToken > 0 ) {
        // There are more rows for this report.
        $body->reportRequests[0]->setPageToken($data->reports[0]->nextPageToken);
        $data     = $analytics->reports->batchGet($body);
        $report[] = $data;
        $cnt++;
    }

    return $report;


}


/**
 * Parses and prints the Analytics Reporting API V4 response.
 *
 * @param An Analytics Reporting API V4 response.
 */
function printResults($reports) {
    for ($reportIndex = 0; $reportIndex < count($reports); $reportIndex++) {
        $report           = $reports[$reportIndex];
        $header           = $report->getColumnHeader();
        $dimensionHeaders = $header->getDimensions();

        if ($dimensionHeaders == '') {
            $dimensionHeaders = array();
        }


        $metricHeaders = $header->getMetricHeader()->getMetricHeaderEntries();
        $rows          = $report->getData()->getRows();

        for ($rowIndex = 0; $rowIndex < count($rows); $rowIndex++) {
            $row        = $rows[$rowIndex];
            $dimensions = $row->getDimensions();
            $metrics    = $row->getMetrics();
            for ($i = 0; $i < count($dimensionHeaders) && $i < count($dimensions); $i++) {
                print($dimensionHeaders[$i].": ".$dimensions[$i]." ** \n");
            }

            for ($j = 0; $j < count($metrics); $j++) {
                $values = $metrics[$j]->getValues();
                for ($k = 0; $k < count($values); $k++) {
                    $entry = $metricHeaders[$k];
                    print($entry->getName().": ".$values[$k]."\n");
                }
            }
        }
    }
}