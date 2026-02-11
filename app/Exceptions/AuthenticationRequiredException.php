<?php

namespace App\Exceptions;

/**
 * Thrown when an action requires an authenticated user but none is present.
 */
class AuthenticationRequiredException extends CareerOpException
{
}

