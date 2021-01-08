<?php
/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */

/**
 * The "negativeKeywordLists" collection of methods.
 * Typical usage is:
 *  <code>
 *   $displayvideoService = new Google_Service_DisplayVideo(...);
 *   $negativeKeywordLists = $displayvideoService->negativeKeywordLists;
 *  </code>
 */
class Google_Service_DisplayVideo_Resource_AdvertisersNegativeKeywordLists extends Google_Service_Resource
{
  /**
   * Gets a negative keyword list given an advertiser ID and a negative keyword
   * list ID. (negativeKeywordLists.get)
   *
   * @param string $advertiserId Required. The ID of the DV360 advertiser to which
   * the fetched negative keyword list belongs.
   * @param string $negativeKeywordListId Required. The ID of the negative keyword
   * list to fetch.
   * @param array $optParams Optional parameters.
   * @return Google_Service_DisplayVideo_NegativeKeywordList
   */
  public function get($advertiserId, $negativeKeywordListId, $optParams = array())
  {
    $params = array('advertiserId' => $advertiserId, 'negativeKeywordListId' => $negativeKeywordListId);
    $params = array_merge($params, $optParams);
    return $this->call('get', array($params), "Google_Service_DisplayVideo_NegativeKeywordList");
  }
  /**
   * Lists negative keyword lists based on a given advertiser id.
   * (negativeKeywordLists.listAdvertisersNegativeKeywordLists)
   *
   * @param string $advertiserId Required. The ID of the DV360 advertiser to which
   * the fetched negative keyword lists belong.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string pageToken A token identifying a page of results the server
   * should return.
   *
   * Typically, this is the value of next_page_token returned from the previous
   * call to `ListNegativeKeywordLists` method. If not specified, the first page
   * of results will be returned.
   * @opt_param int pageSize Requested page size. Must be between `1` and `100`.
   * Defaults to `100` if not set. Returns error code `INVALID_ARGUMENT` if an
   * invalid value is specified.
   * @return Google_Service_DisplayVideo_ListNegativeKeywordListsResponse
   */
  public function listAdvertisersNegativeKeywordLists($advertiserId, $optParams = array())
  {
    $params = array('advertiserId' => $advertiserId);
    $params = array_merge($params, $optParams);
    return $this->call('list', array($params), "Google_Service_DisplayVideo_ListNegativeKeywordListsResponse");
  }
}
