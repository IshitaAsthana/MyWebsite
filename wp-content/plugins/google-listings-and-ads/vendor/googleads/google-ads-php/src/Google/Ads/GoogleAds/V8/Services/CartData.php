<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/ads/googleads/v8/services/conversion_upload_service.proto

namespace Google\Ads\GoogleAds\V8\Services;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Contains additional information about cart data.
 *
 * Generated from protobuf message <code>google.ads.googleads.v8.services.CartData</code>
 */
class CartData extends \Google\Protobuf\Internal\Message
{
    /**
     * The Merchant Center ID where the items are uploaded.
     *
     * Generated from protobuf field <code>string merchant_id = 1;</code>
     */
    protected $merchant_id = '';
    /**
     * The country code associated with the feed where the items are uploaded.
     *
     * Generated from protobuf field <code>string feed_country_code = 2;</code>
     */
    protected $feed_country_code = '';
    /**
     * The language code associated with the feed where the items are uploaded.
     *
     * Generated from protobuf field <code>string feed_language_code = 3;</code>
     */
    protected $feed_language_code = '';
    /**
     * Sum of all transaction level discounts, such as free shipping and
     * coupon discounts for the whole cart. The currency code is the same
     * as that in the ClickConversion message.
     *
     * Generated from protobuf field <code>double local_transaction_cost = 4;</code>
     */
    protected $local_transaction_cost = 0.0;
    /**
     * Data of the items purchased.
     *
     * Generated from protobuf field <code>repeated .google.ads.googleads.v8.services.CartData.Item items = 5;</code>
     */
    private $items;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $merchant_id
     *           The Merchant Center ID where the items are uploaded.
     *     @type string $feed_country_code
     *           The country code associated with the feed where the items are uploaded.
     *     @type string $feed_language_code
     *           The language code associated with the feed where the items are uploaded.
     *     @type float $local_transaction_cost
     *           Sum of all transaction level discounts, such as free shipping and
     *           coupon discounts for the whole cart. The currency code is the same
     *           as that in the ClickConversion message.
     *     @type \Google\Ads\GoogleAds\V8\Services\CartData\Item[]|\Google\Protobuf\Internal\RepeatedField $items
     *           Data of the items purchased.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Ads\GoogleAds\V8\Services\ConversionUploadService::initOnce();
        parent::__construct($data);
    }

    /**
     * The Merchant Center ID where the items are uploaded.
     *
     * Generated from protobuf field <code>string merchant_id = 1;</code>
     * @return string
     */
    public function getMerchantId()
    {
        return $this->merchant_id;
    }

    /**
     * The Merchant Center ID where the items are uploaded.
     *
     * Generated from protobuf field <code>string merchant_id = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setMerchantId($var)
    {
        GPBUtil::checkString($var, True);
        $this->merchant_id = $var;

        return $this;
    }

    /**
     * The country code associated with the feed where the items are uploaded.
     *
     * Generated from protobuf field <code>string feed_country_code = 2;</code>
     * @return string
     */
    public function getFeedCountryCode()
    {
        return $this->feed_country_code;
    }

    /**
     * The country code associated with the feed where the items are uploaded.
     *
     * Generated from protobuf field <code>string feed_country_code = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setFeedCountryCode($var)
    {
        GPBUtil::checkString($var, True);
        $this->feed_country_code = $var;

        return $this;
    }

    /**
     * The language code associated with the feed where the items are uploaded.
     *
     * Generated from protobuf field <code>string feed_language_code = 3;</code>
     * @return string
     */
    public function getFeedLanguageCode()
    {
        return $this->feed_language_code;
    }

    /**
     * The language code associated with the feed where the items are uploaded.
     *
     * Generated from protobuf field <code>string feed_language_code = 3;</code>
     * @param string $var
     * @return $this
     */
    public function setFeedLanguageCode($var)
    {
        GPBUtil::checkString($var, True);
        $this->feed_language_code = $var;

        return $this;
    }

    /**
     * Sum of all transaction level discounts, such as free shipping and
     * coupon discounts for the whole cart. The currency code is the same
     * as that in the ClickConversion message.
     *
     * Generated from protobuf field <code>double local_transaction_cost = 4;</code>
     * @return float
     */
    public function getLocalTransactionCost()
    {
        return $this->local_transaction_cost;
    }

    /**
     * Sum of all transaction level discounts, such as free shipping and
     * coupon discounts for the whole cart. The currency code is the same
     * as that in the ClickConversion message.
     *
     * Generated from protobuf field <code>double local_transaction_cost = 4;</code>
     * @param float $var
     * @return $this
     */
    public function setLocalTransactionCost($var)
    {
        GPBUtil::checkDouble($var);
        $this->local_transaction_cost = $var;

        return $this;
    }

    /**
     * Data of the items purchased.
     *
     * Generated from protobuf field <code>repeated .google.ads.googleads.v8.services.CartData.Item items = 5;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Data of the items purchased.
     *
     * Generated from protobuf field <code>repeated .google.ads.googleads.v8.services.CartData.Item items = 5;</code>
     * @param \Google\Ads\GoogleAds\V8\Services\CartData\Item[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setItems($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \Google\Ads\GoogleAds\V8\Services\CartData\Item::class);
        $this->items = $arr;

        return $this;
    }

}

