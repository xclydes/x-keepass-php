<?php


namespace KeePassPHP\Lib;


class Binary
{

    const XML_ATTR_ID = "ID";
    const XML_ATTR_COMPRESSED = "Compressed";

    /**
     * @var integer
     */
    private $_id;
    /**
     * @var string|null
     */
    private $name;
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
    public function __construct($input, $compressed = false, $_rawContent = null, $name = null)
    {
        //If the input is a binary
        if ($input instanceof Binary) {
            //Copy it
            $this->_id = $input->_id;
            $this->compressed = $input->compressed;
            $this->_rawContent = $input->_rawContent;
            $this->name = $input->name;
        } else {
            //Setup this is an independent instance
            $this->_id = $input;
            $this->compressed = $compressed;
            $this->_rawContent = $_rawContent;
            $this->name = $name;
        }
    }

    /**
     * The name assigned to this binary
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName($name)
    {
        $this->name = $name;
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
            //TODO what compression is this?
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