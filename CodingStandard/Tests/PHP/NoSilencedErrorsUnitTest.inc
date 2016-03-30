<?php
/**
 * @see something
 */
if (@in_array($array, $needle))
{
    echo '@';
}

@trigger_error('deprected notice', E_USER_DEPRECATED);
@trigger_error('regular notice', E_USER_NOTICE);

?>
