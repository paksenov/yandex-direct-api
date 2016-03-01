<?php
namespace vedebel\ydapi;

use vedebel\ydapi\lib\Registry;
use vedebel\ydapi\lib\Request;
use vedebel\ydapi\lib\Response;

/* -------------------- Environment initialization -------------------- */

ini_set('default_socket_timeout', '1200');
date_default_timezone_set('Europe/Moscow');
set_time_limit(0);


/* -------------------- Autoloader & Registry initialization -------------------- */

$registry = Registry::getInstance();

$registry->lib_root        = __DIR__.'/lib';
$registry->yd_api_json_url = 'https://api.direct.yandex.ru/live/v4/json/';
// $registry->yd_api_json_url = 'https://api-sandbox.direct.yandex.ru/live/v4/json/'; 

/* -------------------- Yandex Direct API -------------------- */

class YandexDirectAPI
{

    private $authToken   = '';

    /* -------------------- API methods -------------------- */

    public function setAuthToken($token)
    {
        $this->authToken = $token;
    }

    /* ###### Finance ###### */

    public function CreateInvoice($master_token, $operation_num, $login, array $payments)
    {
        $data = [
            'method'        => __FUNCTION__,
            'locale'        => 'en',
            'finance_token' => $this->getFinanceToken($master_token, $operation_num, __FUNCTION__, $login),
            'operation_num' => $operation_num,
            'param'         => [
                'Payments' => $payments
            ]
        ];

        $request  = new Request($data, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function TransferMoney($master_token, $operation_num, $login, array $from_campaigns, array $to_campaigns)
    {
        $params = array(
            'method'        => __FUNCTION__,
            'locale'        => 'en',
            'finance_token' => $this->getFinanceToken($master_token, $operation_num, __FUNCTION__, $login),
            'operation_num' => $operation_num,
            'param'         => array(
                'FromCampaigns' => $from_campaigns,
                'ToCampaigns'   => $to_campaigns
            )
        );

        $request  = new Request($params, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function GetCreditLimits($master_token, $operation_num, $login)
    {
        $params = array(
            'method'        => __FUNCTION__,
            'locale'        => 'en',
            'finance_token' => $this->getFinanceToken($master_token, $operation_num, __FUNCTION__, $login),
            'operation_num' => $operation_num
        );

        $request  = new Request($params, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function PayCampaigns($master_token, $operation_num, $login, array $payments, $contract_id, $pay_method)
    {
        $params = array(
            'method'        => __FUNCTION__,
            'locale'        => 'en',
            'finance_token' => $this->getFinanceToken($master_token, $operation_num, __FUNCTION__, $login),
            'operation_num' => $operation_num,
            'param'         => array(
                'Payments' => $payments,
                'ContractID' => $contract_id,
                'PayMethod'  => $pay_method
            )
        );

        $request  = new Request($params, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    private function getFinanceToken($master_token, $operation_num, $method, $login)
    {
        return hash('sha256', $master_token . $operation_num . $method . $login);
    }


    /* ###### Statistics and Analysis ######*/

    public function GetSummaryStat(array $campaign_ids, $start_date, $end_date, $currency = 'UAH')
    {
        $data = [
            'method' => 'GetSummaryStat',
            'locale' => 'ru',
            'param'  => [
                'CampaignIDS'=> $campaign_ids,
                'StartDate'=> $start_date,
                'EndDate' => $end_date,
                'Currency' => $currency
            ]
        ];
        $request  = new Request($data, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function GetBannersStat($campaignId, $startDate, $endDate, $currency = 'RUB', $includeVat = 'No', $includeDiscount = 'No')
    {
        $data = [
            "method" => "GetBannersStat",
            "param" => [
                "CampaignID" => $campaignId,
                "StartDate" => $startDate,
                "EndDate" => $endDate,
                "GroupByColumns" => [],
                "OrderBy" => [],
                "Currency" => $currency,
                "IncludeVAT" => $includeVat,
                "IncludeDiscount" => $includeDiscount
            ]
        ];

        $request  = new Request($data, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function CreateNewReport($campaign_id, $start_date, $end_date, array $group_by_columns = array(), array $order_by = array(), array $AdditionalMetrikaCounters = array(), $group_by_date = '', $limit = 0, $offset = 0, $page_type  = '', $position_type = '', array $banner = array(), array $geo = array(), array $phrase = array(), array $page_name = array(), array $stat_goals = array())
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'ru',
            'param'  => array(
                'CampaignID'       => $campaign_id,
                'StartDate'        => $start_date,
                'EndDate'          => $end_date,
                'TypeResultReport' => 'xml',
                'Currency'         => 'RUB',
                'CompressReport'   => 1,
            )
        );

        empty($group_by_columns) ?: $params['param']['GroupByColumns'] = $group_by_columns;
        empty($limit)            ?: $params['param']['Limit']          = $limit;
        empty($offset)           ?: $params['param']['Offset']         = $offset;
        empty($group_by_date)    ?: $params['param']['GroupByDate']    = $group_by_date;
        empty($order_by)         ?: $params['param']['OrderBy']        = $order_by;
        //empty($compress_report)  ?: $params['param']['CompressReport'] = $compress_report;

        empty($AdditionalMetrikaCounters) ?: $params['param']['AdditionalMetrikaCounters']        = $AdditionalMetrikaCounters;

        if(!empty($page_type)     ||
            !empty($position_type) ||
            !empty($banner)        ||
            !empty($geo)           ||
            !empty($phrase)        ||
            !empty($page_name)     ||
            !empty($stat_goals))
        {
            $params['param']['Filter'] = array();

            empty($page_type)     ?: $params['param']['Filter']['PageType']     = $page_type;
            empty($position_type) ?: $params['param']['Filter']['PositionType'] = $position_type;
            empty($banner)        ?: $params['param']['Filter']['Banner']       = $banner;
            empty($geo)           ?: $params['param']['Filter']['Geo']          = $geo;
            empty($phrase)        ?: $params['param']['Filter']['Phrase']       = $phrase;
            empty($page_name)     ?: $params['param']['Filter']['PageName']     = $page_name;
            empty($stat_goals)    ?: $params['param']['Filter']['StatGoals']    = $stat_goals;
        }

        $request  = new Request($params, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function DeleteReport($report_id)
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'ru',
            'param'  => $report_id
        );

        $request  = new Request($params, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function GetReportList()
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'ru'
        );

        $request  = new Request($params, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function GetStatGoals($campaign_id)
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'ru',
            'param'  => array(
                'CampaignID' => $campaign_id
            )
        );

        $request  = new Request($params, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function CreateNewWordstatReport(array $phrases, array $geo_id = array())
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'ru',
            'param'  => array(
                'Phrases' => $phrases
            )
        );

        empty($geo_id) ?: $params['param']['GeoID'] = $geo_id;

        $request  = new Request($params, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function DeleteWordstatReport($report_id)
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'ru',
            'param'  => $report_id
        );

        $request  = new Request($params, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function GetKeywordsSuggestion(array $keywords)
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'ru',
            'param'  => array(
                'Keywords' => $keywords
            )
        );

        $request  = new Request($params, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function GetWordstatReport($report_id)
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'ru',
            'param'  => $report_id
        );

        $request  = new Request($params, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function GetWordstatReportList()
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'ru'
        );

        $request  = new Request($params, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function CreateNewForecast(array $categories, array $phrases, array $geo_id = array())
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'ru',
            'param'  => array()
        );

        empty($categories) ?: $params['param']['Categories'] = $categories;
        empty($phrases)    ?: $params['param']['Phrases']    = $phrases;
        empty($geo_id)     ?: $params['param']['GeoID']      = $geo_id;

        $request  = new Request($params, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function DeleteForecastReport($forecast_id)
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'ru',
            'param'  => $forecast_id
        );

        $request  = new Request($params, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function GetForecast($forecast_id)
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'ru',
            'param'  => $forecast_id
        );

        $request  = new Request($params, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function GetForecastList()
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'ru'
        );

        $request  = new Request($params, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }


    /* ###### Campaigns and Ads ###### */

    public function CreateOrUpdateCampaign($params) {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'ru',
            'param'  =>  $params
        );
        $request  = new Request($params, $this->authToken);
        $response = $request->getResponse();
        return $response;
    }

    /* ###### Upload Image ###### */

    public function AdImage($params)
    {
        $data = array(
            'method' => 'AdImage',
            'locale' => 'ru',
            'param'  =>  $params
        );
        $request  = new Request($data, $this->authToken);
        $response = $request->getResponse();
        return $response;
    }

    public function AdImageAssociation($params)
    {
        $data = array(
            'method' => 'AdImageAssociation',
            'locale' => 'ru',
            'param'  =>  $params
        );
        $request  = new Request($data, $this->authToken);
        $response = $request->getResponse();
        return $response;
    }

    /* ###### Campaigns and Ads ###### */
    public function GetBalance(array $campaigns_ids)
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'ru',
            'param'  => $campaigns_ids
        );

        $request  = new Request($params, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function GetCampaignsList($logins = [])
    {
        $data = array(
            'method' => __FUNCTION__,
            'locale' => 'ru',
            'param'  => $logins
        );

        $request  = new Request($data, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function GetCampaignsDetails(array $camps)
    {
        $params = array(
            'method' =>'GetCampaignsParams',
            'locale' => 'ru',
            'param'  => array(
                'CampaignIDS' => $camps
            )
        );

        $request  = new Request($params, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function GetCampaignsListFilter(array $logins, $filter = [])
    {
        $data = [
            'method' => __FUNCTION__,
            'locale' => 'ru',
            'param'  => [
                'Logins' => $logins
            ]
        ];
        if (!empty($filter)) {
            $data['param']['Filter'] = $filter;
        }

        $request  = new Request($data, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function GetCampaignsParams(array $campaigns_ids, $Currency = 'RUB')
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'ru',
            'param'  => array(
                'CampaignIDS' => $campaigns_ids,
                'Currency' => $Currency,
            )
        );

        $request  = new Request($params, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function ArchiveCampaign($campaign_id)
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'ru',
            'param'  => array(
                'CampaignID' => $campaign_id
            )
        );

        $request  = new Request($params, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function DeleteCampaign($campaign_id)
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'ru',
            'param'  => array(
                'CampaignID' => $campaign_id
            )
        );

        $request  = new Request($params, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function ResumeCampaign($campaign_id)
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'ru',
            'param'  => array(
                'CampaignID' => $campaign_id
            )
        );

        $request  = new Request($params, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function StopCampaign($campaign_id)
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'ru',
            'param'  => array(
                'CampaignID' => $campaign_id
            )
        );

        $request  = new Request($params, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function UnArchiveCampaign($campaign_id)
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'ru',
            'param'  => array(
                'CampaignID' => $campaign_id
            )
        );

        $request  = new Request($params, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function CreateOrUpdateBanners($banner_id = 0, $campaign_id, $title, $text, $phrases = [], $href = '', $groupName = false, $groupID = 0, $params = [])
    {
        $data = [
            'method' => __FUNCTION__,
            'locale' => 'ru',
            'param'  => [
                [
                    'BannerID'    => $banner_id,
                    'CampaignID'  => $campaign_id,
                    'Title'       => $title,
                    'Text'        => $text,
                    'Href'        => $href,
                    'Phrases'     => $phrases,
                    'AdGroupID'   => $groupID,
                    'AdGroupName' => $groupName ?: $title
                ]
            ]
        ];

        if (!empty($params)) {
            $data['param'][0] = array_merge($data['param'][0], $params);
        }

        $request  = new Request($data, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }


    public function UpdateBanners4Live(array $BannersData = array())
    {
        $params = array(
            'method' => 'CreateOrUpdateBanners',
            'locale' => 'ru',
            'param'  => $BannersData
        );
        $request  = new Request($params, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function GetBanners(array $bannerIds, $campaignIds = [], $params = [])
    {
        $data = array(
            'method' => __FUNCTION__,
            'locale' => 'ru',
            'param'  => []
        );

        $data['param']['CampaignIDS'] = $campaignIds ?: [];
        $data['param']['BannerIDS']   = $bannerIds ?: [];

        $data['param']['GetPhrases'] = !empty($params['GetPhrases']) ? $params['GetPhrases'] : 'No';
        $data['param']['Currency'] = !empty($params['Currency']) ? $params['Currency'] : 'RUB';

        if (!empty($params['FieldsNames'])) {
            $data['param']['FieldsNames'] = $params['FieldsNames'];
        }
        if (!empty($params['Filter'])) {
            foreach ($params['Filter'] as $filter) {
                $data['param']['Filter'][] = $filter;
            }
        }

        if(!empty($status_phone_moderate)   ||
            !empty($status_banner_moderate)  ||
            !empty($status_phrases_moderate) ||
            !empty($status_activating)       ||
            !empty($status_show)             ||
            !empty($is_active)               ||
            !empty($status_archive))
        {
            $data['param']['Filter'] = [];

            empty($status_phone_moderate)   ?: $data['param']['Filter']['StatusPhoneModerate']   = $status_phone_moderate;
            empty($status_banner_moderate)  ?: $data['param']['Filter']['StatusBannerModerate']  = $status_banner_moderate;
            empty($status_phrases_moderate) ?: $data['param']['Filter']['StatusPhrasesModerate'] = $status_phrases_moderate;
            empty($status_activating)       ?: $data['param']['Filter']['StatusActivating']      = $status_activating;
            empty($status_show)             ?: $data['param']['Filter']['StatusShow']            = $status_show;
            empty($is_active)               ?: $data['param']['Filter']['IsActive']              = $is_active;
            empty($status_archive)          ?: $data['param']['Filter']['StatusArchive']         = $status_archive;
        }

        $request  = new Request($data, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function GetBannerPhrases(array $banners_ids)
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'ru',
            'param'  => $banners_ids
        );

        $request  = new Request($params, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function GetBannerPhrasesFilter(array $banner_ids, array $fields_names = array(), $consider_time_target = '', $request_prices = '')
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'ru',
            'param'  => array(
                'BannerIDS' => $banner_ids
            )
        );

        empty($fields_names)         ?: $params['param']['FieldsNames']        = $fields_names;
        empty($consider_time_target) ?: $params['param']['ConsiderTimeTarget'] = $consider_time_target;
        empty($request_prices)       ?: $params['param']['RequestPrices']      = $request_prices;

        $request  = new Request($params, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function ArchiveBanners(array $banners_ids)
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'ru',
            'param'  => array(
                'BannerIDS'  => $banners_ids
            )
        );

        $request  = new Request($params, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function DeleteBanners(array $banners_ids)
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'ru',
            'param'  => array(
                'BannerIDS'  => $banners_ids
            )
        );

        $request  = new Request($params, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function ModerateCampaign($campaign_id = 0)
    {

        return $this->ModerateBanners([], $campaign_id);

    }

    public function ModerateBanners(array $banners_ids, $campaign_id = 0)
    {
        $data = [
            'method' => __FUNCTION__,
            'locale' => 'ru',
            'param'  => []
        ];

        !count($banners_ids) ?: $data['param']['BannerIDS'] = $banners_ids;
        empty($campaign_id) ?: $data['param']['CampaignID'] = $campaign_id;

        $request  = new Request($data, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function ResumeBanners(array $banners_ids)
    {
        $data = array(
            'method' => __FUNCTION__,
            'locale' => 'ru',
            'param'  => array(
                'BannerIDS'  => $banners_ids
            )
        );

        $request  = new Request($data, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function StopBanners(array $banners_ids)
    {
        $data = array(
            'method' => __FUNCTION__,
            'locale' => 'ru',
            'param'  => array(
                'BannerIDS'  => $banners_ids
            )
        );

        $request  = new Request($data, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function UnArchiveBanners(array $banners_ids)
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'ru',
            'param'  => array(
                'BannerIDS'  => $banners_ids
            )
        );

        $request  = new Request($params, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function SetAutoPrice($campaign_id, $single_price)
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'ru',
            'param'  => array(
                'CampaignID'  => $campaign_id,
                'Mode'        => 'SinglePrice',
                'SinglePrice' => $single_price
            )
        );

        $request  = new Request($params, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function UpdatePrices(array $in_params = array())
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'ru',
            'param'  => array()
        );

        foreach($in_params as $in_params_group)
        {
            $element = array(
                'PhraseID'   => $in_params_group[0],
                'BannerID'   => $in_params_group[1],
                'CampaignID' => $in_params_group[2]
            );

            empty($in_params_group[3]) ?: $element['Price']              = $in_params_group[3];
            empty($in_params_group[4]) ?: $element['AutoBroker']         = $in_params_group[4];
            empty($in_params_group[5]) ?: $element['AutoBudgetPriority'] = $in_params_group[5];
            empty($in_params_group[6]) ?: $element['ContextPrice']       = $in_params_group[6];

            $params['param'][] = $element;
        }

        $request  = new Request($params, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }


    /* ###### Overall figures ###### */

    public function CreateNewSubclient($login, $name, $surname, $currency)
    {
        $data = [
            'method' => __FUNCTION__,
            'locale' => 'ru',
            'param'  => [
                'Login'   => $login,
                'Name'    => $name,
                'Surname' => $surname,
                'Currency' => $currency
            ]
        ];

        $request  = new Request($data, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function GetClientInfo(array $logins)
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'ru',
            'param'  => $logins
        );

        $request  = new Request($params, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function GetClientsList($status_arch = '')
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'ru'
        );

        empty($status_arch) ?: $params['param']['Filter']['StatusArch'] = $status_arch;

        $request  = new Request($params, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function GetClientsUnits(array $logins)
    {
        $data = array(
            'method' => __FUNCTION__,
            'locale' => 'ru',
            'param'  => $logins
        );

        $request  = new Request($data, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function GetSubClients($login = '', $status_arch = '')
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'ru'
        );

        empty($login)       ?: $params['param']['Login'] = $login;
        empty($status_arch) ?: $params['param']['Filter']['StatusArch'] = $status_arch;

        $request  = new Request($params, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function UpdateClientInfo($login, $phone, $fio, $email, $client_rights = array(), $send_news = '', $send_acc_news = '', $send_warn = '')
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'ru',
            'param'  => array(
                array(
                    'Login'        => $login,
                    'Phone'        => $phone,
                    'FIO'          => $fio,
                    'Email'        => $email,
                    'ClientRights' => array()
                )
            )
        );

        empty($client_rights) ?: $params['param'][0]['ClientRights'] = $client_rights;
        empty($send_news)     ?: $params['param'][0]['SendNews']     = $send_news;
        empty($send_acc_news) ?: $params['param'][0]['SendAccNews']  = $send_acc_news;
        empty($send_warn)     ?: $params['param'][0]['SendWarn']     = $send_warn;

        $request  = new Request($params, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function GetRegions()
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'ru'
        );

        $request  = new Request($params, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function GetRubrics()
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'ru'
        );

        $request  = new Request($params, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function GetAvailableVersions()
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'ru'
        );

        $request  = new Request($params, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function GetChanges(array $campaign_ids, array $banner_ids, array $logins, $timestamp = '')
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'ru',
            'param'  => array()
        );

        !(!empty($campaign_ids) && empty($banner_ids) && empty($logins)) ?: $params['param']['CampaignIDS'] = $campaign_ids;
        !(empty($campaign_ids) && !empty($banner_ids) && empty($logins)) ?: $params['param']['BannerIDS']   = $banner_ids;
        !(empty($campaign_ids) && empty($banner_ids) && !empty($logins)) ?: $params['param']['Logins']      = $logins;
        !(!empty($timestamp)) ?: $params['param']['Timestamp'] = $timestamp;

        $request  = new Request($params, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function GetTimeZones()
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'ru'
        );

        $request  = new Request($params, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function GetVersion()
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'ru'
        );

        $request  = new Request($params, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }

    public function PingAPI()
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'ru'
        );

        $request  = new Request($params, $this->authToken);
        $response = $request->getResponse();

        return $response;
    }
}
