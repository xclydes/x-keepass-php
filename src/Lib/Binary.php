<?php


namespace KeePassPHP\Lib;


use KeePassPHP\Util\GZDecode;

class Binary
{

    const XML_ATTR_ID = "ID";
    const XML_ATTR_COMPRESSED = "Compressed";

    /**
     * @var integer
     */
    private $_id;
    /**
     * @var boolean
     */
    private $compressed;
    /**
     * @var string|null
     */
    private $_rawContent;

    /**
     * Binary constructor.
     * @param int|Binary $input
     * @param bool $compressed
     * @param string|null $_rawContent
     */
    public function __construct($input, $compressed = false, $_rawContent = null)
    {
        //If the input is a binary
        if ($input instanceof Binary) {
            //Copy it
            $this->_id = $input->_id;
            $this->compressed = $input->compressed;
            $this->_rawContent = $input->_rawContent;
        } else {
            //Setup this is an independent instance
            $this->_id = $input;
            $this->compressed = $compressed;
            $this->_rawContent = $_rawContent;
        }
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @return bool
     */
    public function isCompressed()
    {
        return $this->compressed;
    }

    /**
     * @return string|null
     */
    public function getRawContent()
    {
        return $this->_rawContent;
    }

    public function getContent()
    {
        $c = $this->getRawContent();
        if ($this->isCompressed()) {
            $filename = '';
            $error = '';
            //TODO what compression is this?
            $c = GZDecode::gzdecode2($c, $filename, $error);
        }
        return $c;
    }

    /**
     * Creates a new Entry instance from a ProtectedXMLReader instance reading
     * a KeePass 2.x database and located at an Entry element node.
     * @param $reader ProtectedXMLReader A XML reader.
     * @param Database|null $context The database being built, for context
     * @return Binary A Entry instance if the parsing went okay, null otherwise.
     */
    public static function loadFromXML(ProtectedXMLReader $reader, $context = null)
    {
        if ($reader == null || !$reader->isElement(Database::XML_BINARY))
            return null;
        $binary = new Binary(
            (int)$reader->readAttribute(self::XML_ATTR_ID),
            $reader->readAttribute(self::XML_ATTR_COMPRESSED) === 'True',
            $reader->readTextInside()
        );
        return $binary;
    }
}