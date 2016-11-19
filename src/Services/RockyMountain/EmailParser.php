<?php

namespace zaboy\utils\Services\RockyMountain;

use zaboy\utils\utils\HtmlParser\Simple as HtmlParserSimple;
use zaboy\utils\DataStore\Email as DataStoreEmail;

/**
 *
 */
class EmailParser
{

    const TYPE_UNKNOWN = 'TYPE_UNKNOWN';
    const TYPE_RM_GIFT_CERTIFICAT = 'TYPE_RM_GIFT_CERTIFICAT';
    const TYPE_RM_ORDER_PLACING = 'TYPE_RM_ORDER_PLACING';

    /**
     *
     * @var HtmlParserSimple
     */
    //protected $htmlParser ;

    /**
     *
     * @var DataStoreEmail
     */
    protected $dataStoreEmail;

    public function __construct(DataStoreEmail $dataStoreEmail = null)
    {
        $this->dataStoreEmail = $dataStoreEmail ? $dataStoreEmail : new DataStoreEmail;
    }

    //1584fdaa897011b9
    public function getMessage($messageId = null)
    {
        return $this->dataStoreEmail->read($messageId);
    }

    public function getType($message)
    {
        switch (true) {
            case $this->checkTypeGiftCertificat($message):
                return self::TYPE_RM_GIFT_CERTIFICAT;
            case $this->checkOrderPlacing($message):
                return self::TYPE_RM_ORDER_PLACING;
            default:
                return self::TYPE_UNKNOWN;
        }
    }

    protected function checkTypeGiftCertificat($message)
    {
        $html = $message[DataStoreEmail::BODY_HTML];
        $amountPresnt = (bool) strpos($html, 'Amount');
        $redemptionPresent = (bool) strpos($html, 'Gift Certificate Redemption Number');
        return $amountPresnt && $redemptionPresent;
    }

    protected function checkOrderPlacing($message)
    {
        $html = $message[DataStoreEmail::BODY_HTML];
        return (bool) strpos($html, 'Thank you for placing your order with Rocky Mountain');
    }

    public function fillGiftCertificat($message)
    {
        $bodyTxt = $message[DataStoreEmail::BODY_HTML];
        $exp = '#Amount:([^C]*)Certificate Expiration:([^!]+)Gift Certificate Redemption Number:\s+([0-9]{16})#';
        $resalt = [];
        preg_match_all($exp, $bodyTxt, $resalt);
        $giftNumber = $resalt[3][0];
        $expiration = date_create($resalt[2][0])->format('Y-m-d');
        $value = $resalt[1][0];
        $sendingTime = gmdate("Y-M-d H:i:s", $message[DataStoreEmail::SENDING_TIME]);

        return /* $giftNumber . '<br>' . */ $expiration /* . '<br>' . $value . '<br>' . $sendingTime */;
    }

    public function fillOrderPlacing($message)
    {
        $bodyHtml = $message[DataStoreEmail::BODY_HTML];
        $exp = '#Order date:</strong>\s+([^!]+)\s+<br><br>\s+<strong>Order number:</strong>\s+([0-9]{7,8})\s+<br><br>\s+<strong>Ship[^|`]+Total:(</td><td style=[\',\"]white-space:nowrap;text-align:left;white-space:nowrap|</td><td style=[\',\"]text-align:right;white-space:nowrap;padding-right:100px|</strong></td>\s+<td);?[\',\"]?>\$\s?(-?\d+.\d+)#';
        /*      ##Order date:</strong>\s+([^!]+)\s+<br><br>\s+<strong>Order number:</strong>\s+([0-9]{7,8})\s+<br><br>\s+<strong>Ship[^|`]+Total:(</td><td style=[\',\"]white-space:nowrap;text-align:left;white-space:nowrap|</td><td style=[\',\"]text-align:right;white-space:nowrap;padding-right:100px|</strong></td>\s+<td);?[\',\"]?>\$\s?(-?\d+.\d+)#'; */

        $resalt = [];
        preg_match_all($exp, $bodyHtml, $resalt);

        $orderTime = isset($resalt[1][0]) ? date_create($resalt[1][0])->format('Y-m-d H:i:s') : '--orderTime--';

        $orderId = isset($resalt[2][0]) ? $resalt[2][0] : '--orderId--';

        $orderSum = isset($resalt[4][0]) ? $resalt[4][0] : '--orderSum--';

        if ($orderTime === '--orderTime--') {
            echo $bodyHtml;
        }

        $orderSumRus = str_replace('.', ',', $orderSum);

        $output = '<tr>';
        $output = $output . '<td>' . 'orderTime: ' . '</td><td>' . $orderTime . '</td>'
                . '<td>' . 'orderId: ' . '</td><td>' . $orderId . '</td>'
                . '<td>' . ';  orderSum: ' . '</td>' . '<td>' . $orderSumRus . '</td>';

        $output = $output . $this->fillOrderPlacingPayment($bodyHtml, $orderSum);
        $output = $output . '</tr>';

        return $output;
    }

    public function fillOrderPlacingPayment($bodyHtml, $orderSum)
    {
        $expGift = '#((Gift\sCard:</strong></td>\s+<td>)|(Gift\sCard:</td><td\s+style=[^>]+>))(-?\$\d+.\d{0,2})</td></tr>#';
        $resalt = [];
        preg_match_all($expGift, $bodyHtml, $resalt);
        $giftCard = isset($resalt[4][0]) ? str_replace('$', '', $resalt[4][0]) : false;
        $giftCard = $giftCard < 0 ? -1 * $giftCard : $giftCard;

        $expRmCash = '#RM Cash:</td><td\s+style=[^>]+>(-?\$\d+.\d{0,2})</td></tr>#';
        $resalt = [];
        preg_match_all($expRmCash, $bodyHtml, $resalt);
        $rmCash = isset($resalt[1][0]) ? str_replace('$', '', $resalt[1][0]) : false;
        $rmCash = $rmCash < 0 ? -1 * $rmCash : $rmCash;

        $expCashRewards = '#Cash Rewards:</strong></td>\s+<td>(-?\$\d+.\d{0,2})</td></tr>#';
        $resalt = [];
        preg_match_all($expCashRewards, $bodyHtml, $resalt);
        $cashRewards = isset($resalt[1][0]) ? str_replace('$', '', $resalt[1][0]) : false;
        $cashRewards = $cashRewards < 0 ? -1 * $cashRewards : $cashRewards;

        $cash = $cashRewards + $rmCash;


        $expPayPall = '#Payment:</td><[^>]+>(-?\$\d+.\d{0,2})\s\(PAYPAL\)</td></tr>#';
        //
        $resalt = [];
        preg_match_all($expPayPall, $bodyHtml, $resalt);

        $payPall = isset($resalt[1][0]) ? str_replace('$', '', $resalt[1][0]) : false;
        $payPall = $payPall < 0 ? -1 * $payPall : $payPall;



        $output = '<td>' . 'giftCard: ' . '</td><td>' . str_replace('.', ',', $giftCard) . '</td>'
                . '<td>' . '  payPall: ' . '</td>' . '<td>' . str_replace('.', ',', $payPall) . '</td>'
                . '<td>' . '  rmCash: ' . '</td>' . '<td>' . str_replace('.', ',', $rmCash) . '</td>'
                . '<td>' . '  cashRewards: ' . '</td>' . '<td>' . str_replace('.', ',', $cashRewards) . '</td>';

//'#PAYPAL|Gift|GIFT[^<]|Cash|CREDIT|Credits#'
        if ($orderSum > 0 && (($orderSum - $giftCard - $payPall - $cash) > 0.001) &&
                (
                preg_match('#Gift Card|GIFT[^<]#', $bodyHtml) && $giftCard === false
                /* ||
                  preg_match('#CREDIT|RM Cash#', $bodyHtml) && $rmCash === false ||
                  preg_match('#CREDIT|Cash Rewards#', $bodyHtml) && $cashRewards === false ||
                  preg_match('#PAYPAL#', $bodyHtml) && $payPall === false */
                )
        ) {
            echo ($orderSum - $giftCard - $payPall - $rmCash);
            echo '<br>';
            print_r($output);
            echo '<br>';
            echo $bodyHtml;
            exit;
        }
        return $output;
    }

}
