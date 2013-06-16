<?php
/**
 * PsyConstants class
 *
 * Various constants that used in various site pages
 *
 * @author  Den Kazka <den.smart[at]gmail.com>
 * @since   2010
 * @version $Id
 */
class PsyConstants {

    const SITE_PREFIX = 1;
    const SITE_PREFIX_ADVANCED = 2;

    /**
     * Page constants
     */
    const PAGE_CHATOURREADERS = 3;
    const PAGE_MESSAGECENTER = 4;
    const PAGE_EMAILREADINGS_SCEDUAL = 5;
    const PAGE_CLIENTSLIST = 6;
    const PAGE_READERBALANCE = 7;
    const PAGE_SENDMESSAGETOADMIN = 8;
    const PAGE_SENDMESSAGEMAGAZINE = 9;
    const PAGE_PRICERATE = 10;
    const PAGE_PAYOUTS = 11;
    const PAGE_USERBAN = 12;
    const PAGE_WP_CONTROLPANEL = 13;
    const PAGE_EMAILREADINGS_READER = 14;
    const PAGE_CHATADDFUNDS = 15;
    const PAGE_CHATSTART = 16;
    const PAGE_PURCHASES = 17;
    const PAGE_PAGEREADER = 18;
    const PAGE_WHOISCHATTING = 19;
    const PAGE_OURCLIENTS = 20;
    const PAGE_PAYOUT_HISTORY = 21;
    const PAGE_MESSAGECENTER_SENT = 22;
    const PAGE_MESSAGEPENDING = 23;
    const PAGE_READEREDIT_TMP = 24;
    const PAGE_MESSAGESETTING = 25;
    const PAGE_BANNEDLIST = 26;
    const PAGE_AFFILIATE = 27;
    const PAGE_SETTINGS = 28;
    const PAGE_CRONLIST = 29;
    const PAGE_WP_LIST = 30;
    const PAGE_WP_FUNDSREADERS = 31;
    const PAGE_ADD_FUNDSREADERS = 32;
    const PAGE_SENDEMAILS = 33;
    const PAGE_EMAILREADERS = 34;
    const PAGE_REFSTAT = 35;
    const PAGE_NOTIFYREADERS = 36;
    const PAGE_ARCHIVE = 37;
    const PAGE_DOUBLEREADERS = 38;
    const PAGE_SEARCHTRANCE = 39;
    const PAGE_READERSVISIBILITY = 40;
    const PAGE_CHATCONTROL = 41;
    const PAGE_RETTIMESTAT = 42;
    const PAGE_EMAILREADINGS_ADMIN = 43;
    const PAGE_FREEBIE_FAQ = 44;
    const PAGE_CONTACT_US = 45;
    const PAGE_CHAT = 46;
    const PAGE_PROFILE = 47;
    const PAGE_CHATOURREADERS_GUEST = 48;
    const PAGE_READER_PAYOUTS = 49;
    const PAGE_AFFILIATE_ADD = 50;
    const PAGE_EMPLOYMENT = 51;
    const PAGE_DISCLAIMER = 52;
    const PAGE_PRIVACY_POLICY = 53;
    const PAGE_NRR_QUEST_FORM = 54;
    const PAGE_NRR_QUEST_NEW = 55;
    const PAGE_CHATBALANCE = 56;
    const PAGE_CHATMONITOR = 57;
    const PAGE_CHATSESSION = 58;
    const PAGE_WP_DEPOSITS = 59;
    const PAGE_EMAILREADINGS_READER_SPECIAL_EDIT = 60;
    const PAGE_DISCLAIMER_HTML = 61;
    const PAGE_WP_LOGS = 62;
    const PAGE_WP_CLIENTS = 63;
    const PAGE_FORCE_AUTO_INVOICING = 64;
    const PAGE_CLIENT_ADMIN = 71;
    const PAGE_CLIENT_ADD = 72;
    const PAGE_CLIENT_DOUBLE = 73;
    const PAGE_DOUBLE_ACCOUNTS = 74;
    const PAGE_CLIENT_NEW = 75;
    const PAGE_DELETE_OLD = 76;

    /**
     * Site type constants
     */
    const SITE_TYPE_CO_UK = 65;
    const SITE_TYPE_CA = 66;

    /**
     * Client limits constants
     */
    const DAY_LIMIT = 67;
    const MONTH_LIMIT = 68;

const ADD_MD5_1 = 78;
    const ADD_MD5_2 = 79;
    const CC_PASSWORD = 80;

    /**
     * Client login errors
     */
    const ACCOUNT_BANNED = 69;
    const ACCOUNT_UNACTIVATED = 70;

    const MONTH = 77;

 /**
     * Additional minutes for chat add funds
     */
    const CHAT_EXTRA_MINUTES = 81;
    /*
     * Affiliate rates for emailreadings
     */
    const EMAILREADINGS_AFF_AMOUNT = 82;


    public static $names = array(
            self::SITE_PREFIX => 'chat',
            self::SITE_PREFIX_ADVANCED => 'advanced',

            self::PAGE_CHATOURREADERS => 'site/ourreaders',
            self::PAGE_MESSAGECENTER => 'messages/index',
            self::PAGE_EMAILREADINGS_SCEDUAL => 'chat/emailreadings_scedual.php',
            self::PAGE_CLIENTSLIST => 'users/readersClients',
            self::PAGE_READERBALANCE => 'chat/reader_balance.php',
            self::PAGE_SENDMESSAGETOADMIN => 'chat/send_message.php?to=admin',
            self::PAGE_SENDMESSAGEMAGAZINE => 'chat/send_message.php?to=admin&amp;subject=Submit%20Magazine%20Article',
            self::PAGE_PRICERATE => 'chat/price_rate.php',
            self::PAGE_PAYOUTS => 'chat/payouts.php',
            self::PAGE_USERBAN => 'chat/userban.php',
            self::PAGE_WP_CONTROLPANEL => 'chat/wp_controlpanel.php',
            self::PAGE_EMAILREADINGS_READER => 'chat/emailreadings_reader.php',
            self::PAGE_CHATADDFUNDS => 'advanced/pay/chat',
            self::PAGE_CHATSTART => 'chat/client/chatStart',
            self::PAGE_PURCHASES => 'users/purchases',
            self::PAGE_PAGEREADER => 'chat/users/pagereader',
            self::PAGE_WHOISCHATTING => 'chat/who_is_chatting.php',
            self::PAGE_OURCLIENTS => 'users/adminSearch',
            self::PAGE_PAYOUT_HISTORY => 'chat/payout_historylist.php',
            self::PAGE_MESSAGECENTER_SENT => 'chat/messagecenter_sent.php',
            self::PAGE_MESSAGEPENDING => 'chat/message_pending.php',
            self::PAGE_READEREDIT_TMP => 'chat/reader_edit_tmp.php',
            self::PAGE_MESSAGESETTING => 'chat/message_settings.php',
            self::PAGE_BANNEDLIST => 'chat/bannedlist.php',
            self::PAGE_AFFILIATE => 'chat/affiliate.php',
            self::PAGE_SETTINGS => 'chat/settings.php',
            self::PAGE_CRONLIST => 'chat/cron_list.php',
            self::PAGE_WP_LIST => 'chat/wp_list.php',
            self::PAGE_WP_FUNDSREADERS => 'chat/wp_funds_readers.php',
            self::PAGE_CHATSESSION => 'chat/chat_session.php',
            self::PAGE_ADD_FUNDSREADERS => 'chat/add_funds_readers.php',
            self::PAGE_SENDEMAILS => 'chat/send_emails.php',
            self::PAGE_EMAILREADERS => 'chat/email_readers.php',
            self::PAGE_REFSTAT => 'chat/ref_stat.php',
            self::PAGE_NOTIFYREADERS => 'users/pageReader',
            self::PAGE_ARCHIVE => 'chat/archive.php',
            self::PAGE_DOUBLEREADERS => 'chat/double_readers.php',
            self::PAGE_SEARCHTRANCE => 'chat/trans/search_trans.php',
            self::PAGE_READERSVISIBILITY => 'chat/readers_visibility.php',
            self::PAGE_CHATCONTROL => 'chat/chat_control.php',
            self::PAGE_RETTIMESTAT => 'chat/ret_time_stat.php',
            self::PAGE_EMAILREADINGS_ADMIN => 'chat/emailreadings_admin.php',
            self::PAGE_FREEBIE_FAQ => 'site/faq',
            self::PAGE_CONTACT_US => 'site/contact',
            self::PAGE_CHAT => 'users/chathistory',
            self::PAGE_PROFILE => 'profile/userprofile',
            self::PAGE_CHATOURREADERS_GUEST => 'chat/chatourreaders_guest.php',
            self::PAGE_READER_PAYOUTS => 'chat/cron/reader_payouts.php',
            self::PAGE_AFFILIATE_ADD => 'chat/affiliateadd.php',
            self::PAGE_EMPLOYMENT => 'chat/employment.php',
            self::PAGE_DISCLAIMER => 'chat/disclaimer.php',
            self::PAGE_DISCLAIMER_HTML => 'disclaimer.html',
            self::PAGE_PRIVACY_POLICY => 'chat/privacy_policy.php',
            self::PAGE_NRR_QUEST_FORM => 'chat/nrr_quest_form.php',
            self::PAGE_NRR_QUEST_NEW => 'chat/nrr_quest_new.php',
            self::PAGE_CHATBALANCE => 'chat/chatbalance.html',
            self::PAGE_CHATMONITOR => 'chat/chatmonitor.php',
            self::PAGE_WP_DEPOSITS => 'chat/wp_deposits.php',
            self::PAGE_EMAILREADINGS_READER_SPECIAL_EDIT => 'chat/emailreadings_reader.php?action=special_edit',
            self::PAGE_WP_LOGS => 'chat/wp_logs.php',
            self::PAGE_WP_CLIENTS => 'chat/wp_clients.php',
            self::PAGE_FORCE_AUTO_INVOICING => 'chat/cron/reader_payouts.php?force=1',
            self::PAGE_CLIENT_ADMIN => 'users/clientAdministration',
            self::PAGE_CLIENT_ADD => 'chat/ourclients.php?action=clients_add',
            self::PAGE_CLIENT_DOUBLE => 'chat/ourclients.php?action=clients_double',
            self::PAGE_DOUBLE_ACCOUNTS => 'chat/double_accounts.php',
            self::PAGE_CLIENT_NEW => 'chat/ourclients.php?action=clients_new',
            self::PAGE_DELETE_OLD => 'chat/ourclients.php?action=delete_old',

            self::SITE_TYPE_CO_UK => 'www.psychic-contact.co.uk',
            self::SITE_TYPE_CA => 'www.psychic-contact.ca',

            self::DAY_LIMIT => 125,
            self::MONTH_LIMIT => 250,

  self::CHAT_EXTRA_MINUTES => 10,


    /**
     * Additional string for md5 encrypt (for credit cards)
     */
            self::ADD_MD5_1 => '(u77.',
            self::ADD_MD5_2 => '/-4-da',
            self::CC_PASSWORD => 'k48!!.z0j_*jsd',

            self::ACCOUNT_BANNED => '<font color="red">Your account is <b>Disabled</b></font>.<br>If you think this is a mistake, please contact us.',
            self::ACCOUNT_UNACTIVATED => 'Sorry, Your account may not yet be activated.<br>
                                        Please check the email at which you used to register, there will be a link to activate your account.<br>
                                        If you see this message and you have already had an active account then your account may have been suspended/blacklisted.<br>
                                        For further help feel free to <a href="http://www.psychic-contact.com/chat/contact_us.php">contact us</a>',
            self::MONTH => array(
                            1 => 'Jan',
                            2 =>  'Feb',
                            3 =>  'Mar',
                            4 =>  'Apr',
                            5 =>  'May',
                            6 =>  'Jun',
                            7 =>  'Jul',
                            8 =>  'Aug',
                            9 =>  'Sep',
                            10 =>  'Oct',
                            11 =>  'Nov',
                            12 =>  'Dec'),

            self::EMAILREADINGS_AFF_AMOUNT => array(
                'A-1' => array(
                    'q1' => '11.00',
                    'q2' => '15.50',
                    'q3' => '20.00',
                    'q4' => '25.00',
                    'q5' => '30.00'
                ),
                'A-2' => array(
                    'q1' => '10.00',
                    'q2' => '13.50',
                    'q3' => '17.50',
                    'q4' => '22.50',
                    'q5' => '26.50'
                )
            ),
    );

    /**
     * Returns constant value by number
     *
     * @param int $const constant number
     * @return string Return constant value
     */
    public static function getName($const) {

        if(!isset(self::$names[$const]))
            throw new CException("Wrong constant!");

        return self::$names[$const];
    }
}

?>
