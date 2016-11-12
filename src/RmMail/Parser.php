<?php

namespace zaboy\utils\utils\RmMail;

use zaboy\utils\utils\HtmlParser\Simple as HtmlParserSimple;

/**
 *
 */
class Parser
{

    /**
     *
     * @var HtmlParserSimple
     */
    public $htmlParser;

    public function __construct()
    {
        $this->htmlParser = new HtmlParserSimple;
    }

}
