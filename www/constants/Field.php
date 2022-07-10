<?php
declare(strict_types=1);

namespace app\constants;

/**
 * Class Field
 * @package app\constants
 */
class Field
{
    /** Field types */
    /** @var string Text  */
    public const TYPE_TEXT = 'text';
    /** @var string Password */
    public const TYPE_PASSWORD = 'password';
    /** @var string Checkbox */
    public const TYPE_CHECKBOX = 'checkbox';
    /** @var string Select */
    public const TYPE_SELECT = 'select';

    /** Values of checkbox */
    /** @var string 1 */
    public const CHECKBOX_VALUE_CHECKED = '1';
    /** @var string 0 */
    public const CHECKBOX_VALUE_UNCHECKED = '0';
}