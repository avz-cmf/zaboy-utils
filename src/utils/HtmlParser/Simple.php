<?php

namespace zaboy\utils\utils\HtmlParser;

/**
 *
 * @see https://habrahabr.ru/post/176635/
 * @see http://simplehtmldom.sourceforge.net/manual.htm
 */
class Simple extends simple_html_dom
{

    /**
     *
     * @var simple_html_dom
     */
    public $dom;

    public function __construct($str = null)
    {
        $lowercase = true;
        $forceTagsClosed = true;
        $target_charset = DEFAULT_TARGET_CHARSET;
        $stripRN = true;
        $defaultBRText = DEFAULT_BR_TEXT;
        $defaultSpanText = DEFAULT_SPAN_TEXT;

        parent::__construct(null, $lowercase, $forceTagsClosed, $target_charset, $stripRN, $defaultBRText, $defaultSpanText);
        if (empty($str) || strlen($str) > MAX_FILE_SIZE) {
            throw new \RuntimeException('Wrong $str param. Strlen = ' . $str ? strlen($str) : 0);
        }

        $this->load($str, $lowercase, $stripRN);
    }

    public function fromCamelCase($input)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('_', $ret);
    }

    public function __call($name, array $params)
    {
        $dashesName = $this->fromCamelCase($name);
        if (method_exists($this, $dashesName))
            return call_user_func_array(array(&$this, $dashesName), $params);
        throw new \RuntimeException('Wrong method name: ' . $dashesName);
    }

}
