<?php
$varName  = 'hello';
$var_name = 'hello';
$varname  = 'hello';
$_varName = 'hello';

class MyClass
{
    $varName  = 'hello';
    $var_name = 'hello';
    $varname  = 'hello';
    $_varName = 'hello';

    public $varName  = 'hello';
    public $var_name = 'hello';
    public $varname  = 'hello';
    public $_varName = 'hello';

    protected $varName  = 'hello';
    protected $var_name = 'hello';
    protected $varname  = 'hello';
    protected $_varName = 'hello';

    private $_varName  = 'hello';
    private $_var_name = 'hello';
    private $_varname  = 'hello';
    private $varName   = 'hello';
}

echo $varName;
echo $var_name;
echo $varname;
echo $_varName;

echo "Hello $varName";
echo "Hello $var_name";
echo "Hello ${varName}";
echo "Hello ${var_name}";
echo "Hello $varname";
echo "Hello $_varName";

echo 'Hello '.$varName;
echo 'Hello '.$var_name;
echo 'Hello '.$varname;
echo 'Hello '.$_varName;

echo $_SERVER['var_name'];
echo $_REQUEST['var_name'];
echo $_GET['var_name'];
echo $_POST['var_name'];
echo $GLOBALS['var_name'];

echo MyClass::$varName;
echo MyClass::$var_name;
echo MyClass::$varname;
echo MyClass::$_varName;

echo $this->varName2;
echo $this->var_name2;
echo $this->varname2;
echo $this->_varName2;
echo $object->varName2;
echo $object->var_name2;
echo $objectName->varname2;
echo $objectName->_varName2;

echo $this->myFunction($one, $two);
echo $object->myFunction($oneTwo);
echo $object->myFunction($one_two);

$error = "format is \$GLOBALS['$varName']";
$error = "format is \$GLOBALS['$var_name']";

echo $_SESSION['var_name'];
echo $_FILES['var_name'];
echo $_ENV['var_name'];
echo $_COOKIE['var_name'];

$XML       = 'hello';
$myXML     = 'hello';
$XMLParser = 'hello';
$xmlParser = 'hello';

echo "{$_SERVER['HOSTNAME']} $varName";

// Need to be the last thing in this test file.
$obj->$class_var = $prefix.'-'.$type;

class foo
{
    const bar = <<<BAZ
qux
BAZ;
}

$foo = <<<'BAR'
$123
"$456"
BAR;

$foo = <<<BAR
$123
"$456"
BAR;

class a
{
    protected
        $_sheet,
        $_FieldParser,
        $_key;
}

var_dump($http_response_header);
var_dump($HTTP_RAW_POST_DATA);
var_dump($php_errormsg);

function funcName1($var_name1, $var_name2) {
    echo $object->myFunction($oneTwo);
    echo $object->myFunction($one_two);
}

function funcName2($varName1, $varName2) {
    echo $object->myFunction($oneTwo);
    echo $object->myFunction($one_two);
}

class TheClass
{

    function funcName1($var_name1, $var_name2) {
        $a = ($var_name1 + $var_name2);
        echo $object->myFunction($oneTwo);
        echo $object->myFunction($one_two);
    }

    function funcName2($varName1, $varName2) {
        echo $object->myFunction($oneTwo);
        echo $object->myFunction($one_two);

        echo "Hello {$_varName}";
        echo "Hello {$var_name}";
    }

}

class ClassExceptions
{

    function testMe()
    {
        $this->Application = '';
        $this->Conn = '';

        $this->Name = '';
        $this->MasterEvent = '';
        $this->Prefix = '';
        $this->Special = '';

        $this->IDField = '';
        $this->TableName = '';
        $this->IgnoreValidation = '';
    }

}

$a = $some_var->ThisDoesNotThrow->AnError;
$a = $some_var->$ThisDoesNotThrow->AnError;

echo "Hello {TheClass::CONST}";
echo "Hello {TheClass::$staticProperty}";
echo "Hello {$object->$dynamicProperty}";
echo "Hello {$object->$dynamicProperty[5]}";
