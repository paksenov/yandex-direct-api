<?php
namespace vedebel\ydapi;

use vedebel\ydapi\lib\Registry;
use vedebel\ydapi\lib\Request;
use vedebel\ydapi\lib\Response;

/* -------------------- Environment initialization -------------------- */

error_reporting(E_ALL);
ini_set('display_errors', 'On');
ini_set('default_socket_timeout', '1200');
date_default_timezone_set('Europe/Kiev');
set_time_limit(0);

// ID: 6c0b12d6848c4cfabf00e7054e3c4711
// Пароль: 346b34eaec1f496d9c895a83d424498a
// Callback URL: https://oauth.yandex.ru/verification_code


/* -------------------- Autoloader & Registry initialization -------------------- */

$registry = Registry::getInstance();

$registry->lib_root        = __DIR__.'/lib';
$registry->yd_api_json_url = 'https://api.direct.yandex.ru/live/v4/json/'; 

/* -------------------- Yandex Direct API -------------------- */

class YandexDirectAPI
{
    
private $authToken   = '';
private $clientLogin = false;


public function setClientLogin($login = false) 
    {
        $this->clientLogin = $login;
    }

/* -------------------- API methods -------------------- */

/* ###### Finance ###### */

public function CreateInvoice($master_token, $operation_num, $login, array $payments)
    {
        $params = array(
            'method'        => __FUNCTION__,
            'locale'        => 'en',
            'finance_token' => $this->getFinanceToken($master_token, $operation_num, __FUNCTION__, $login),
            'operation_num' => $operation_num,
            'param'         => array(
                'Payments' => $payments
            )
        );

        $request  = new Request($this->clientLogin, $params, $this->authToken);
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

        $request  = new Request($this->clientLogin, $params, $this->authToken);
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

        $request  = new Request($this->clientLogin, $params, $this->authToken);
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

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }


/* ###### Statistics and Analysis ######*/
    
public function GetSummaryStat(array $campaign_ids, $start_date, $end_date)
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'en',
            'param'  => array(
                'CampaignIDS' => $campaign_ids,
                'StartDate'   => $start_date,
                'EndDate'     => $end_date,
                'Currency' => 'UAH'
            )
        );

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }
    
 /*
  * Add Currency
  */   
public function CreateNewReport($campaign_id, $start_date, $end_date, $type_result_report, array $group_by_columns = array(), $Currency = 'USD', $limit = 0, $offset = 0, $group_by_date = '', array $order_by = array(), $compress_report = 0, $page_type  = '', $position_type = '', array $banner = array(), array $geo = array(), array $phrase = array(), array $page_name = array(), array $stat_goals = array())
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'en',
            'param'  => array(
                'CampaignID'       => $campaign_id,
                'StartDate'        => $start_date,
                'EndDate'          => $end_date,
                'TypeResultReport' => $type_result_report,
                'Currency'         => $Currency
            )
        );

        empty($group_by_columns) ?: $params['param']['GroupByColumns'] = $group_by_columns;
        empty($limit)            ?: $params['param']['Limit']          = $limit;
        empty($offset)           ?: $params['param']['Offset']         = $offset;
        empty($group_by_date)    ?: $params['param']['GroupByDate']    = $group_by_date;
        empty($order_by)         ?: $params['param']['OrderBy']        = $order_by;
        empty($compress_report)  ?: $params['param']['CompressReport'] = $compress_report;
        
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

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }

public function DeleteReport($report_id)
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'en',
            'param'  => $report_id
        );

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }

public function GetReportList()
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'en'
        );

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }

public function GetStatGoals($campaign_id)
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'en',
            'param'  => array(
                'CampaignID' => $campaign_id
            )
        );

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }

public function CreateNewWordstatReport(array $phrases, array $geo_id = array())
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'en',
            'param'  => array(
                'Phrases' => $phrases
            )
        );
        
        empty($geo_id) ?: $params['param']['GeoID'] = $geo_id;

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }

public function DeleteWordstatReport($report_id)
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'en',
            'param'  => $report_id
        );

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }

public function GetKeywordsSuggestion(array $keywords)
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'en',
            'param'  => array(
                'Keywords' => $keywords
            )
        );

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }

public function GetWordstatReport($report_id)
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'en',
            'param'  => $report_id
        );

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }

public function GetWordstatReportList()
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'en'
        );

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }

public function CreateNewForecast(array $categories, array $phrases, array $geo_id = array())
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'en',
            'param'  => array()
        );
        
        empty($categories) ?: $params['param']['Categories'] = $categories;
        empty($phrases)    ?: $params['param']['Phrases']    = $phrases;
        empty($geo_id)     ?: $params['param']['GeoID']      = $geo_id;

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }

public function DeleteForecastReport($forecast_id)
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'en',
            'param'  => $forecast_id
        );

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }

public function GetForecast($forecast_id)
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'en',
            'param'  => $forecast_id
        );

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }

public function GetForecastList()
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'en'
        );

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }


/* ###### Campaigns and Ads ###### */

public function CreateOrUpdateCampaign4Live($params) {
        $params = array(
            'method' => 'CreateOrUpdateCampaign',
            'locale' => 'en',
            'param'  =>  $params
        );
        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
        return $response;
}

/* ###### Upload Image ###### */

public function UploadRawData($params) {
        $params = array(
            'method' => 'AdImage',
            'locale' => 'en',
            'param'  =>  $params
        );
        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
        return $response;
}

public function AssocImage($params) {
        $params = array(
            'method' => 'AdImageAssociation',
            'locale' => 'en',
            'param'  =>  $params
        );
        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
        return $response;
}

/* ###### Campaigns and Ads ###### */
public function CreateOrUpdateCampaign($login, $campaign_id, $name, $fio, 
                                       $strategy_name, $email, $warn_place_interval, 
                                       $money_warning_value, array $minus_keywords = array(), 
                                       array $days_hours = array(), $time_zone = '', 
                                       $start_date = '', $send_acc_news = '', 
                                       $send_warn = '', $max_price = 0.0, $average_price = 0.0, 
                                       $weekly_sum_limit = 0.0, $clicks_per_week = 0.0, 
                                       $metrica_sms = '', $moderate_result_sms = '', 
                                       $money_in_sms = '', $money_out_sms = '', 
                                       $sms_time_from = '', $sms_time_to = '', 
                                       $status_behavior = '', $show_on_holidays = '', 
                                       $holiday_show_from = 0, $holiday_show_to = 0, 
                                       $status_context_stop = '', $context_limit = '', 
                                       $context_limit_sum = 0, $context_price_percent = 0,
                                       $auto_optimization = '', 
                                       $status_metrica_control = '', 
                                       $disabled_domains = '', 
                                       $disabled_ips = '', $status_openStat = '', 
                                       $consider_time_target = '', $add_relevant_phrases = '', $relevant_phrases_budget_limit = 0)
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'en',
            'param'  => array(
                'Login'      => $login,
                'CampaignID' => $campaign_id,
                'Name'       => $name,
                'FIO'        => $fio,
                'Strategy'   => array(
                    'StrategyName' => $strategy_name
                ),
                'EmailNotification' => array(
                    'Email'             => $email,
                    'WarnPlaceInterval' => $warn_place_interval,
                    'MoneyWarningValue' => $money_warning_value
                )
            )
        );

        empty($start_date)       ?: $params['param']['StartDate']                  = $start_date;
        empty($max_price)        ?: $params['param']['Strategy']['MaxPrice']       = $max_price;
        empty($average_price)    ?: $params['param']['Strategy']['AveragePrice']   = $average_price;
        empty($weekly_sum_limit) ?: $params['param']['Strategy']['WeeklySumLimit'] = $weekly_sum_limit;
        empty($clicks_per_week)  ?: $params['param']['Strategy']['ClicksPerWeek']  = $clicks_per_week;
        
        if(!empty($send_acc_news) || 
           !empty($send_warn))
            {
                empty($send_acc_news) ?: $params['param']['EmailNotification']['MetricaSms']        = $send_acc_news;
                empty($send_warn)     ?: $params['param']['EmailNotification']['ModerateResultSms'] = $send_warn;
            }
      
        if(!empty($metrica_sms)         || 
           !empty($moderate_result_sms) ||
           !empty($money_in_sms)        ||
           !empty($money_out_sms)       ||
           !empty($sms_time_from)       ||
           !empty($sms_time_to))
            {
                $params['param']['SmsNotification'] = array();
                
                empty($metrica_sms)         ?: $params['param']['SmsNotification']['MetricaSms']        = $metrica_sms;
                empty($moderate_result_sms) ?: $params['param']['SmsNotification']['ModerateResultSms'] = $moderate_result_sms;
                empty($money_in_sms)        ?: $params['param']['SmsNotification']['MoneyInSms']        = $money_in_sms;
                empty($money_out_sms)       ?: $params['param']['SmsNotification']['MoneyOutSms']       = $money_out_sms;
                empty($sms_time_from)       ?: $params['param']['SmsNotification']['SmsTimeFrom']       = $sms_time_from;
                empty($sms_time_to)         ?: $params['param']['SmsNotification']['SmsTimeTo']         = $sms_time_to;
            }

        if(!empty($show_on_holidays)  || 
           !empty($holiday_show_from) ||
           !empty($holiday_show_to)   ||
           !empty($time_zone)         ||
           !empty($days_hours))
            {
                $params['param']['TimeTarget'] = array();
                
                empty($show_on_holidays)  ?: $params['param']['TimeTarget']['ShowOnHolidays']  = $show_on_holidays;
                empty($holiday_show_from) ?: $params['param']['TimeTarget']['HolidayShowFrom'] = $holiday_show_from;
                empty($holiday_show_to)   ?: $params['param']['TimeTarget']['HolidayShowTo']   = $holiday_show_to;
                empty($time_zone)         ?: $params['param']['TimeTarget']['TimeZone']        = $time_zone;
                empty($days_hours)        ?: $params['param']['TimeTarget']['DaysHours']       = $days_hours;
            }
            
        empty($status_behavior)               ?: $params['param']['StatusBehavior']             = $status_behavior;
        empty($status_context_stop)           ?: $params['param']['StatusContextStop']          = $status_context_stop;
        empty($context_limit)                 ?: $params['param']['ContextLimit']               = $context_limit;
        empty($context_limit_sum)             ?: $params['param']['ContextLimitSum']            = $context_limit_sum;
        empty($context_price_percent)         ?: $params['param']['ContextPricePercent']        = $context_price_percent;
        empty($auto_optimization)             ?: $params['param']['AutoOptimization']           = $auto_optimization;
        empty($status_metrica_control)        ?: $params['param']['StatusMetricaControl']       = $status_metrica_control;
        empty($disabled_domains)              ?: $params['param']['DisabledDomains']            = $disabled_domains;
        empty($disabled_ips)                  ?: $params['param']['DisabledIps']                = $disabled_ips;
        empty($status_openStat)               ?: $params['param']['StatusOpenStat']             = $status_openStat;
        empty($consider_time_target)          ?: $params['param']['ConsiderTimeTarget']         = $consider_time_target;
        empty($minus_keywords)                ?: $params['param']['MinusKeywords']              = $minus_keywords;
        empty($add_relevant_phrases)          ?: $params['param']['AddRelevantPhrases']         = $add_relevant_phrases;
        empty($relevant_phrases_budget_limit) ?: $params['param']['RelevantPhrasesBudgetLimit'] = $relevant_phrases_budget_limit;

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }

public function GetBalance(array $campaigns_ids)
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'en',
            'param'  => $campaigns_ids
        );

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }

public function GetCampaignsList(array $logins)
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'en',
            'param'  => $logins
        );

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }
    
public function GetCampaignsDetails(array $camps)
    {
        $params = array(
            'method' =>'GetCampaignsParams',
            'locale' => 'en',
            'param'  => array(
                           'CampaignIDS' => $camps
                        )
        );

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }

public function GetCampaignsListFilter(array $logins, array $status_moderate = array(), array $is_active = array(), array $status_archive = array(), array $status_activating = array(), array $status_show = array())
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'en',
            'param'  => array(
                'Logins' => $logins
            )
        );
        
        if(!empty($status_moderate)   || 
           !empty($is_active)         || 
           !empty($status_archive)    || 
           !empty($status_activating) || 
           !empty($status_show))
            {
                $params['param']['Filter'] = array();
                
                empty($status_moderate)   ?: $params['param']['Filter']['StatusModerate']   = $status_moderate;
                empty($is_active)         ?: $params['param']['Filter']['IsActive']         = $is_active;
                empty($status_archive)    ?: $params['param']['Filter']['StatusArchive']    = $status_archive;
                empty($status_activating) ?: $params['param']['Filter']['StatusActivating'] = $status_activating;
                empty($status_show)       ?: $params['param']['Filter']['StatusShow']       = $status_show;
            }

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }

public function GetCampaignsParams(array $campaigns_ids)
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'en',
            'param'  => array(
                'CampaignIDS' => $campaigns_ids
            )
        );

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }
 
public function ArchiveCampaign($campaign_id)
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'en',
            'param'  => array(
                'CampaignID' => $campaign_id
            )
        );

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }

public function DeleteCampaign($campaign_id)
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'en',
            'param'  => array(
                'CampaignID' => $campaign_id
            )
        );

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }

public function ResumeCampaign($campaign_id)
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'en',
            'param'  => array(
                'CampaignID' => $campaign_id
            )
        );

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }

public function StopCampaign($campaign_id)
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'en',
            'param'  => array(
                'CampaignID' => $campaign_id
            )
        );

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }

public function UnArchiveCampaign($campaign_id)
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'en',
            'param'  => array(
                'CampaignID' => $campaign_id
            )
        );

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }

public function CreateOrUpdateBanners($banner_id, $campaign_id, $title, $text, $geo, $phrases, $href = '', array $sitelinks = array(), $contact_person = '', $country = '', $country_code = '', $city = '', $street = '', $house = '', $build = '', $apart = '', $city_code = '', $phone = '', $phone_ext = '', $company_Name = '', $im_client = '', $im_login = '', $extra_message = '', $contact_email = '', $work_time = '', $ogrn = '', array $point_on_map = array(), array $minus_keywords = array(), $groupName = false)
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'en',
            'param'  => array(
                array(
                    'BannerID'   => $banner_id,
                    'CampaignID' => $campaign_id,
                    'Title'      => $title,
                    'Text'       => $text,
                    'Geo'        => $geo,
                    'Phrases'    => $phrases,
                    'AdGroupName'=> $groupName? $groupName : $title
                )
            )
        );
        
        //!(!empty($href) && empty($country)) ?: $params['param'][0]['Href'] = $href;
        
        if (!empty($href))
            $params['param'][0]['Href'] = $href;
        
        empty($minus_keywords) ?: $params['param'][0]['MinusKeywords'] = $minus_keywords;
        empty($sitelinks)      ?: $params['param'][0]['Sitelinks']     = $sitelinks;

        if(!empty($contact_person) ||
           !empty($country)        ||
           !empty($country_code)   ||
           !empty($city)           ||
           !empty($street)         ||
           !empty($house)          ||
           !empty($build)          ||
           !empty($apart)          ||
           !empty($city_code)      ||
           !empty($phone)          ||
           !empty($phone_ext)      ||
           !empty($company_Name)   ||
           !empty($im_client)      ||
           !empty($im_login)       ||
           !empty($extra_message)  ||
           !empty($contact_email)  ||
           !empty($work_time)      ||
           !empty($ogrn)           ||
           !empty($point_on_map))
            {
                //$params['param']['ContactInfo'] = array();

                empty($contact_person) ?: $params['param'][0]['ContactInfo']['ContactPerson'] = $contact_person;
                empty($country)        ?: $params['param'][0]['ContactInfo']['Country']       = $country;
                empty($country_code)   ?: $params['param'][0]['ContactInfo']['CountryCode']   = $country_code;
                empty($city)           ?: $params['param'][0]['ContactInfo']['City']          = $city;
                empty($street)         ?: $params['param'][0]['ContactInfo']['Street']        = $street;
                empty($house)          ?: $params['param'][0]['ContactInfo']['House']         = $house;
                empty($build)          ?: $params['param'][0]['ContactInfo']['Build']         = $build;
                empty($apart)          ?: $params['param'][0]['ContactInfo']['Apart']         = $apart;
                empty($city_code)      ?: $params['param'][0]['ContactInfo']['CityCode']      = $city_code;
                empty($phone)          ?: $params['param'][0]['ContactInfo']['Phone']         = $phone;
                empty($phone_ext)      ?: $params['param'][0]['ContactInfo']['PhoneExt']      = $phone_ext;
                empty($company_Name)   ?: $params['param'][0]['ContactInfo']['CompanyName']   = $company_Name;
                empty($im_client)      ?: $params['param'][0]['ContactInfo']['IMClient']      = $im_client;
                empty($im_login)       ?: $params['param'][0]['ContactInfo']['IMLogin']       = $im_login;
                empty($extra_message)  ?: $params['param'][0]['ContactInfo']['ExtraMessage']  = $extra_message;
                empty($contact_email)  ?: $params['param'][0]['ContactInfo']['ContactEmail']  = $contact_email;
                empty($work_time)      ?: $params['param'][0]['ContactInfo']['WorkTime']      = $work_time;
                empty($ogrn)           ?: $params['param'][0]['ContactInfo']['OGRN']          = $ogrn;
                empty($point_on_map)   ?: $params['param'][0]['ContactInfo']['PointOnMap']    = $point_on_map;
            }
        //print_r($params);
        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }
    
public function CreateOrUpdateBanners4live($banner_id, $campaign_id, $title, $text, $geo, $phrases, $href, $groupName = false, $groupID = 0, array $sitelinks = array(), $contact_person = '', $country = '', $country_code = '', $city = '', $street = '', $house = '', $build = '', $apart = '', $city_code = '', $phone = '', $phone_ext = '', $company_Name = '', $im_client = '', $im_login = '', $extra_message = '', $contact_email = '', $work_time = '', $ogrn = '', array $point_on_map = array(), array $minus_keywords = array())
    {
        $params = array(
            'method' => 'CreateOrUpdateBanners',
            'locale' => 'en',
            'param'  => array(
                array(
                    'BannerID'   => $banner_id,
                    'CampaignID' => $campaign_id,
                    'Title'      => $title,
                    'Text'       => $text,
                    'Geo'        => $geo,
                    'Phrases'    => $phrases,
                    'AdGroupID'  => $groupID ? $groupID : 0, 
                    'AdGroupName'=> $groupName? $groupName : $title
                )
            )
        );
        //echo 'Phone ext = ';
        //var_dump($phone_ext);
        //!(!empty($href) && empty($country)) ?: $params['param'][0]['Href'] = $href;
        
        if (!empty($href))
            $params['param'][0]['Href'] = $href;
        
        empty($minus_keywords) ?: $params['param'][0]['MinusKeywords'] = $minus_keywords;
        empty($sitelinks)      ?: $params['param'][0]['Sitelinks']     = $sitelinks;

        if(!empty($contact_person) ||
           !empty($country)        ||
           !empty($country_code)   ||
           !empty($city)           ||
           !empty($street)         ||
           !empty($house)          ||
           !empty($build)          ||
           !empty($apart)          ||
           !empty($city_code)      ||
           !empty($phone)          ||
           !empty($phone_ext)      ||
           !empty($company_Name)   ||
           !empty($im_client)      ||
           !empty($im_login)       ||
           !empty($extra_message)  ||
           !empty($contact_email)  ||
           !empty($work_time)      ||
           !empty($ogrn)           ||
           !empty($point_on_map))
            {
                //$params['param']['ContactInfo'] = array();

                empty($contact_person) ?: $params['param'][0]['ContactInfo']['ContactPerson'] = $contact_person;
                empty($country)        ?: $params['param'][0]['ContactInfo']['Country']       = $country;
                empty($country_code)   ?: $params['param'][0]['ContactInfo']['CountryCode']   = $country_code;
                empty($city)           ?: $params['param'][0]['ContactInfo']['City']          = $city;
                empty($street)         ?: $params['param'][0]['ContactInfo']['Street']        = $street;
                empty($house)          ?: $params['param'][0]['ContactInfo']['House']         = $house;
                empty($build)          ?: $params['param'][0]['ContactInfo']['Build']         = $build;
                empty($apart)          ?: $params['param'][0]['ContactInfo']['Apart']         = $apart;
                empty($city_code)      ?: $params['param'][0]['ContactInfo']['CityCode']      = $city_code;
                empty($phone)          ?: $params['param'][0]['ContactInfo']['Phone']         = $phone;
                empty($phone_ext)      ?: $params['param'][0]['ContactInfo']['PhoneExt']      = $phone_ext;
                empty($company_Name)   ?: $params['param'][0]['ContactInfo']['CompanyName']   = $company_Name;
                empty($im_client)      ?: $params['param'][0]['ContactInfo']['IMClient']      = $im_client;
                empty($im_login)       ?: $params['param'][0]['ContactInfo']['IMLogin']       = $im_login;
                empty($extra_message)  ?: $params['param'][0]['ContactInfo']['ExtraMessage']  = $extra_message;
                empty($contact_email)  ?: $params['param'][0]['ContactInfo']['ContactEmail']  = $contact_email;
                empty($work_time)      ?: $params['param'][0]['ContactInfo']['WorkTime']      = $work_time;
                empty($ogrn)           ?: $params['param'][0]['ContactInfo']['OGRN']          = $ogrn;
                empty($point_on_map)   ?: $params['param'][0]['ContactInfo']['PointOnMap']    = $point_on_map;
            }
           // $params['param'][1] = $params['param'][0];
           // $params['param'][2] = $params['param'][0];
           // $params['param'][3] = $params['param'][0];
          //  $params['param'][4] = $params['param'][0];
        //print_r($params);
        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }    
     
    
public function UpdateBanners4Live(array $BannersData = array())
    {
        $params = array(
            'method' => 'CreateOrUpdateBanners',
            'locale' => 'en',
            'param'  => $BannersData
        );
        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }

public function GetBanners(array $campaign_ids, array $banner_ids, $get_phrases = '', array $status_phone_moderate = array(), array $status_banner_moderate = array(), array $status_phrases_moderate = array(), array $status_activating = array(), array $status_show = array(), array $is_active = array(), array $status_archive = array())
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'en',
            'param'  => array()
        );
        
        !(!empty($campaign_ids) && empty($banner_ids)) ?: $params['param']['CampaignIDS'] = $campaign_ids;
        !(empty($campaign_ids) && !empty($banner_ids)) ?: $params['param']['BannerIDS']   = $banner_ids;
        empty($get_phrases) ?: $params['param']['GetPhrases'] = $get_phrases;

        if(!empty($status_phone_moderate)   || 
           !empty($status_banner_moderate)  || 
           !empty($status_phrases_moderate) || 
           !empty($status_activating)       || 
           !empty($status_show)             ||
           !empty($is_active)               ||
           !empty($status_archive))
            {
                $params['param']['Filter'] = array();
                
                empty($status_phone_moderate)   ?: $params['param']['Filter']['StatusPhoneModerate']   = $status_phone_moderate;
                empty($status_banner_moderate)  ?: $params['param']['Filter']['StatusBannerModerate']  = $status_banner_moderate;
                empty($status_phrases_moderate) ?: $params['param']['Filter']['StatusPhrasesModerate'] = $status_phrases_moderate;
                empty($status_activating)       ?: $params['param']['Filter']['StatusActivating']      = $status_activating;
                empty($status_show)             ?: $params['param']['Filter']['StatusShow']            = $status_show;
                empty($is_active)               ?: $params['param']['Filter']['IsActive']              = $is_active;
                empty($status_archive)          ?: $params['param']['Filter']['StatusArchive']         = $status_archive;
            }

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }

public function GetBannerPhrases(array $banners_ids)
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'en',
            'param'  => $banners_ids
        );

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }

public function GetBannerPhrasesFilter(array $banner_ids, array $fields_names = array(), $consider_time_target = '', $request_prices = '')
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'en',
            'param'  => array(
                'BannerIDS' => $banner_ids
            )
        );
        
        empty($fields_names)         ?: $params['param']['FieldsNames']        = $fields_names;
        empty($consider_time_target) ?: $params['param']['ConsiderTimeTarget'] = $consider_time_target;
        empty($request_prices)       ?: $params['param']['RequestPrices']      = $request_prices;

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }

public function ArchiveBanners($campaign_id, array $banners_ids) 
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'en',
            'param'  => array(
                'CampaignID' => $campaign_id,
                'BannerIDS'  => $banners_ids
            )
        );

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }

public function DeleteBanners($campaign_id, array $banners_ids)
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'en',
            'param'  => array(
               // 'CampaignID' => $campaign_id,
                'BannerIDS'  => $banners_ids
            )
        );

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }

public function ModerateBanners($campaign_id, array $banners_ids)
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'en',
            'param'  => array(
                'CampaignID' => $campaign_id,
                'BannerIDS'  => $banners_ids
            )
        );

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }

public function ResumeBanners($campaign_id, array $banners_ids)
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'en',
            'param'  => array(
                'CampaignID' => $campaign_id,
                'BannerIDS'  => $banners_ids
            )
        );

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }

public function StopBanners($campaign_id, array $banners_ids)
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'en',
            'param'  => array(
                'CampaignID' => $campaign_id,
                'BannerIDS'  => $banners_ids
            )
        );

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }

public function UnArchiveBanners($campaign_id, array $banners_ids)
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'en',
            'param'  => array(
                'CampaignID' => $campaign_id,
                'BannerIDS'  => $banners_ids
            )
        );

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }

public function SetAutoPrice($campaign_id, $single_price)
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'en',
            'param'  => array(
                'CampaignID'  => $campaign_id,
                'Mode'        => 'SinglePrice',
                'SinglePrice' => $single_price
            )
        );

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }

public function UpdatePrices(array $in_params = array())
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'en',
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

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }


/* ###### Overall figures ###### */

public function CreateNewSubclient($login, $name, $surname)
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'en',
            'param'  => array(
                'Login'   => $login,
                'Name'    => $name,
                'Surname' => $surname,
                'Currency' => 'UAH'
            )
        );

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }

public function GetClientInfo(array $logins)
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'en',
            'param'  => $logins
        );

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }

public function GetClientsList($status_arch = '')
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'en'
        );
        
        empty($status_arch) ?: $params['param']['Filter']['StatusArch'] = $status_arch;

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }

public function GetClientsUnits(array $logins)
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'en',
            'param'  => $logins
        );

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }
    
public function GetSubClients($login = '', $status_arch = '')
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'en'
        );
        
        empty($login)       ?: $params['param']['Login'] = $login;
        empty($status_arch) ?: $params['param']['Filter']['StatusArch'] = $status_arch;

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }

public function UpdateClientInfo($login, $phone, $fio, $email, $client_rights = array(), $send_news = '', $send_acc_news = '', $send_warn = '')
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'en',
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

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }

public function GetRegions()
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'en'
        );

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }

public function GetRubrics()
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'en'
        );

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }

public function GetAvailableVersions()
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'en'
        );

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }

public function GetChanges(array $campaign_ids, array $banner_ids, array $logins, $timestamp = '')
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'en',
            'param'  => array()
        );
        
        !(!empty($campaign_ids) && empty($banner_ids) && empty($logins)) ?: $params['param']['CampaignIDS'] = $campaign_ids;
        !(empty($campaign_ids) && !empty($banner_ids) && empty($logins)) ?: $params['param']['BannerIDS']   = $banner_ids;
        !(empty($campaign_ids) && empty($banner_ids) && !empty($logins)) ?: $params['param']['Logins']      = $logins;
        !(!empty($timestamp)) ?: $params['param']['Timestamp'] = $timestamp;

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }

public function GetTimeZones()
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'en'
        );

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }

public function GetVersion()
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'en'
        );

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }

public function PingAPI()
    {
        $params = array(
            'method' => __FUNCTION__,
            'locale' => 'en'
        );

        $request  = new Request($this->clientLogin, $params, $this->authToken);
        $response = $request->getResponse();
         
        return $response;
    }
    
}

?>
