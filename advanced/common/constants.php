<?php 

global $GD2INSTALLED;


$aff_percent["A-1"] = 5;
$aff_percent["A-2"] = 10;

$ROWS_PER_PAGE                					= 20;

$MAX_CHART_FIELDS        						= 5;
$MAX_ENUM_RECORDS        						= 100;
$MAX_ENUM_FIELDSIZE       						= 60;

$MAX_RECORDS_NONINDEX_SEARCH 					= 100000;

$MAX_LIST_FIELDLENGTH = 100;

$MAX_IMAGE_FILESIZE                           	= 500000;

$MAX_MESSAGE_FILESIZE                        	= 2000000;

$MAX_NUM_PAGES                                	= 100;

// Max width/height
$MAXWIDTH_LOGO                                	= 200;
$MAXHEIGHT_LOGO                                	= 48;
$MAXWIDTH_MEDIAFILE                        		= 118;
$MAXHEIGHT_MEDIAFILE                			= 118;
$MAXWIDTH_REPRESENTATIVE        				= 118;
$MAXHEIGHT_REPRESENTATIVE        				= 118;
$MAXWIDTH_PRODUCTLOGO                			= 118;
$MAXHEIGHT_PRODUCTLOGO                			= 118;

// Account type
$ACCOUNT_RR                                    	= 0;
$ACCOUNT_MLM                                	= 1;
$ACCOUNT_DOWNLINE                        		= 3;
$ACCOUNT_CHAT                                	= 2;
$ACCOUNT_DTF                                	= 4;

// Message type
$MESSAGE_EMAIL                                	= 1;
$MESSAGE_APP                                	= 0;
$MESSAGE_SMS                                	= 2;

// Restricted fields (not logged)
$ARRAY_RESTRICTED_FIELDS = array(
									'rr_account_id',
									'rr_formid',
									'rr_missingvaluesasempty',
									'rr_record_id_alias',
									'_accountid',
									'_redirect',
									'_send_email',
									'rr_redirect1',
									'PHPSESSID',
									'rr_redirect0',
									'rr_recordname',
									'rr_preprocess',
									'rr_recordfield',
									'rr_action',
									'rr_params',
									'rr_createdate',
									'rr_lastaccess',
									'rr_redirect1_resultstr',
									'rr_redirect0_resultstr',
									'rr_redirect0dup_resultstr',
									'rr_redirect1del_resultstr',
									'rr_tablename',
									'_error_url',
									'rr_emailsent',
									'rr_constraint',
									'rr_synchronized',
									'submit'
								);


								$DIR_REPRESENTATIVE               	= "/data/representative/";
$DIR_MESSAGE                        = "/data/message/";
$DIR_ACCOUNT                        = "/data/account/";
$DIR_DATA                           = "/data/";


$FILE_EMPTY_REPRESENTATIVE          = $DIR_REPRESENTATIVE."empty.jpg";
$FILE_EMPTY_REPRESENTATIVE_WTEXT    = $DIR_REPRESENTATIVE."empty_wtext.jpg";

$FILE_EMPTY_ACCOUNT                 = $DIR_ACCOUNT."empty.jpg";
$FILE_EMPTY_ACCOUNT_WTEXT           = $DIR_ACCOUNT."empty_wtext.jpg";

$FILE_EMPTY_MEDIAFILE               = "/data/empty_file.jpg";

$SESSION_SIGNUP_ACCOUNTID           = "signup_account_id";
$SESSION_SIGNUP_REPRESENTATIVEID    = "signup_representative_id";
$SESSION_SIGNUP_PRODUCTID           = "signup_product_id";

$PREF_FAVORITE                      = "Favorite";

$PREF_DEFPAGE                       = "DefaultPage";
$PREF_DEFPAGE_HOME                  = "DefPageHome";
$PREF_DEFPAGE_JOIN                  = "DefPageJoin";
$PREF_DEFPAGE_CHAT                  = "DefPageChat";
$PREF_DEFPAGE_CHATMAIN              = "DefPageMain";
$PREF_DEFPAGE_DOWNLINE              = "DefDownline";
$PREF_DEFPAGE_LEADS                 = "DefLeads";
$PREF_DEFPAGE_MYWEBSITE             = "DefPageMain";
$PREF_DEFPAGE_SPONSOR               = "DefSponsor";
$PREF_DEFPAGE_RESOURCES             = "DefResources";
$PREF_DEFPAGE_COMMUNICATE           = "DefCommunicate";
$PREF_DEFPAGE_ADMIN                 = "DefPageAdmin";
$PREF_DEFPAGE_REPOSITORY            = "DefPageRepository";
$PREF_DEFPAGE_DELIVERY              = "DefPageDelivery";
$PREF_DEFPAGE_REPORTS               = "DefPageReports";
$PREF_DEFPAGE_MESSAGES         		= "DefPageMessages";
$PREF_DEFPAGE_READERS        		= "DefPageReaders";
$PREF_DEFPAGE_POWERLINE 			= "DefPagePowerline";

$PREF_SIGNUPS                       = "SignupsChart";
$PREF_GLOBAL                        = "Global";
$PREF_CHATHISTORY                	= "ChatHistoryChart";
$PREF_CATEGORIES                	= "CategoriesChart";
$PREF_DOWNLINE                      = "DownlineChart";
$PREF_POWERLINE                     = "PowerlineChart";
$PREF_CHAT                          = "ChatChart";
$PREF_LEADS                         = "LeadsChart";
$PREF_ORDERS                        = "OrdersChart";
$PREF_EXTRACT                       = "ExtractChart";
$PREF_ATTACHMENTS                	= "AttachmentsChart";
$PREF_SEGMENTS                      = "SegmentsChart";
$PREF_REPOSITORY                	= "RepositoryChart";
$PREF_OPERATORS                     = "OperatorsChart";
$PREF_VIEWDATA                      = "ViewDataChart";
$PREF_REPRESENTATIVES        		= "RepresentativeChart";
$PREF_PRODUCTS                      = "ProductsChart";
$PREF_CLIENTS                       = "ClientsChart";
$PREF_MESSAGES                      = "MessageChart";
$PREF_HISTORY                       = "HistoryChart";
$PREF_PAYMENTS                      = "PaymentsChart";
$PREF_MEDIA                         = "MediaChart";
$PREF_DPAGES                        = "DPagesChart";
$PREF_DMENUS                        = "DMenusChart";
$PREF_CAMPAIGNS                     = "CampaignsChart";
$PREF_HISTORY                       = "HistoryChart";
$PREF_CAMPAIGNMESSAGES        		= "CampaignMessages";
$PREF_START_FROM_PAGE        		= "start_from_page";
$PREF_SEGMENTS_ONE                	= "SegmentsOneChart";
$PREF_VIEWDATA_ONE                	= "ViewDataOneChart";
$PREF_SORT_ASCENDING        		= "sort_ascending";
$PREF_SORT_BY_FIELD                	= "sort_by_field";
$PREF_HIDELEFT                      = "hide_left";
$PREF_FILTER                        = "filter";
$PREF_FRAMES                        = "Frames";
$PREF_LASTPAGE                      = "LastPage";

// Tables
$PREF_MYMEMBERS                     = "MyMembers";

$STR_CHAT                           = $lang['Chat_'];
$STR_OVERVIEW                       = $lang['Overview'];
$STR_HISTORY                        = $lang['History'];
$STR_PAYMENTS                       = $lang['Payments'];
$STR_SEND_MESSAGES                	= $lang['Communicate'];
$STR_CAMPAIGNS                      = $lang['Campaigns'];
$STR_AUDIENCES                      = $lang['User_Groups'];
$STR_CHANNELS                       = $lang['Categories'];
$STR_REPRESENTATIVES        		= $lang['Representatives'];

$STR_DOWNLINE                       = $lang['Downline'] ;
$STR_POWERLINE                      = $lang['Powerline'];
$STR_LEADS                        	= $lang['Leads'];
$STR_ORDERS                        	= $lang['Order_History'];

$STR_MANAGE_CONTENT                	= $lang['Manage_Content'];
$STR_MESSAGES                       = $lang['Messages'];
$STR_MEDIA                          = $lang['Graphics_Library'];
$STR_DYNAMIC_PAGES                	= $lang['Dynamic_Pages'];

$STR_MANAGE_USERS                	= $lang['Manage_Users'];
$STR_MANAGE                         = $lang['Manage'] ;
$STR_LISTS                          = $lang['Database'];

$STR_REPORTS                        = $lang['Reports'];
$STR_RESPONSE_RATE                  = $lang['Response Rate'];

$STR_SETTINGS                       = $lang['Admin'];
$STR_PROFILE                        = $lang['Profile'];
$STR_LIFEFORCE                      = $lang['LifeForce'];
$STR_PAYPAL                         = $lang['PayPal'];
$STR_NOTIFICATIONS                  = $lang['Notifications'];
$STR_ACCOUNT                        = $lang['Account_Info'];
$STR_OPERATORS                      = $lang['Account_Managers'];
$STR_PRODUCTS                       = $lang['Products'];
$STR_DOWNLINE                       = $lang['Downline'];

$STR_IGNORETHIS                     = "_ignorethis";
$STR_IGNORETHISFIELD        		= "rr_ignorethisvalue";
$STR_FIELDTYPE                      = "_rr_fieldtype";

$STR_DESC_VARCHAR                	= "Text (Oneline)";
$STR_DESC_LONGTEXT                	= "TextArea (Multiple lines)";
$STR_DESC_INT                       = "Number (123)";
$STR_DESC_DOUBLE                	= "Decimal (123.45)";
$STR_DESC_TIMESTAMP                	= "Time/Date";
$STR_DESC_ENUM                      = "Enumeration";
$STR_CHARTFIELD                     = "rr_chartfield_";

define("MSG_DUPLICATE", 1);
define("MSG_UNACTIVATED", 2);
define("MSG_BANNED", 3);
define("MSG_TRIES_LIMIT", 4);
define("MSG_TRANS_DECLINED", 5);
define("MSG_ACCOUNT_APPROVED", 6);
define("MSG_ACCESS_DENIED",7);

?>