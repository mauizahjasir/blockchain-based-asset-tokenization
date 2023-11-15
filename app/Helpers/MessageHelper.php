<?php

namespace App\Helpers;

class MessageHelper
{
    public static function submissionFailure(): string
    {
        return 'There was an error with your submission. Please check the form and try again.';
    }

    public static function createSuccess(string $entity = ''): string
    {
        return "$entity created successfully";
    }

    public static function notAuthorizedUser()
    {
        return 'The wallet address of the requestor is not valid.';
    }

    public static function doesNotHavePermission()
    {
        return 'The user does not have required permission to perform the action';
    }

    public static function transactionFailure()
    {
        return 'Transaction Failed';
    }

    public static function permissionFailure($action = 'grant')
    {
        return "Unable to $action permission.";
    }

    public static function permissionSuccess($action = 'granted')
    {
        return "Permission $action successfully.";
    }
}
