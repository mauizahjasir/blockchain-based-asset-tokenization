<?php

namespace App\Helpers;

class MessageHelper
{
    public static function submissionFailure(): string
    {
        return 'There was an error with your submission. Please check the form and try again.';
    }

    public static function notAuthorizedUser()
    {
        return 'The wallet address of the requestor is not valid.';
    }

    public static function doesNotHavePermission()
    {
        return 'The user does not have required permission to perform the action';
    }
}
