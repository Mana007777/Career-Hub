<?php

namespace App\Exceptions;

/**
 * Base exception for all custom, domain-specific exceptions in the CareerOp app.
 *
 * Extends the base PHP Exception so it is still caught anywhere that
 * currently catches \Exception, avoiding breaking existing behaviour.
 */
class CareerOpException extends \Exception
{
}

