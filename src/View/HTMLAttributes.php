<?php

namespace Sitefrog\View;

class HTMLAttributes
{
    private static $mappings = [
        'modal' => 'data-sf-modal-trigger'
    ];

    private static $bool = [
        'checked', 'multiple'
    ];

    public static function process($attrs)
    {

        $values = [];
        foreach ($attrs as $attrGroup => $attributes) {
            foreach ($attributes as $attribute => $value) {
                $realAttribute = self::$mappings[$attribute] ?? $attribute;

                if (in_array($realAttribute, self::$bool)) {
                    if (!$value) {
                        continue;
                    }
                }

                if (!isset($values[$realAttribute])) {
                    $values[$realAttribute] = [];
                }

                $values[$realAttribute] = array_merge($values[$realAttribute], is_array($value) ? $value : [$value]);
            }
        }

        $str = '';

        foreach ($values as $attribute => $value) {
            if (is_array($value)) {
                $value = implode(' ', $value);
            }

            $str.= "$attribute=\"$value\" ";
        }

        return $str;
    }
}
