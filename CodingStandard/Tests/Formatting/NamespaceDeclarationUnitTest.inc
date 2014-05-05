<?php
namespace SomeNamespace;

class TooCloseClass
{

}

namespace SomeNamespace;

use SomeOtherNamespace\TooCloseClass as OtherClass;

class TooCloseClass
{

}

namespace SomeNamespace;

// Something.
class AnotherClass
{

}

// =====

namespace SomeNamespace;


class TooCloseClass
{

}

namespace SomeNamespace;


use SomeOtherNamespace\TooCloseClass as OtherClass;

class TooCloseClass
{

}

namespace SomeNamespace;


// Something.
class AnotherClass
{

}

// =====


namespace SomeNamespace;



class TooCloseClass
{

}

namespace SomeNamespace;



use SomeOtherNamespace\TooCloseClass as OtherClass;

class TooCloseClass
{

}

namespace SomeNamespace;



// Something.
class AnotherClass
{

}

?>
