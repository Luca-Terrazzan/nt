<?php
namespace Assignment\Core;

/**
 * Class for parameters validations.
 * TODO: update all validators for 0 leadings (new func)
 *
 * @author     Luca Terrazzan <luca.terraz@gmail.com>
 */
class ParamValidator
{
    /**
     * Param validation
     * TODO: search_keyword validation (if any)
     *
     * @param string $node_id
     * @param string $language
     * @param string $search_keyword
     * @param string $page_num
     * @param string $page_size
     * @return boolean
     */
    public static function validateParams($node_id, $language, $search_keyword, $page_num, $page_size)
    {
        $languageError = self::validateLanguage($language);
        if ($languageError) {
            return $languageError;
        }
        $nodeError = self::validateNodeId($node_id);
        if ($nodeError) {
            return $nodeError;
        }
        $pageSizeError = self::validatePageSize($page_size);
        if ($pageSizeError) {
            return $pageSizeError;
        }
        $pageNumError = self::validatePageNumber($page_num);
        if ($pageNumError) {
            return $pageNumError;
        }
        return 0;
    }

    /**
     * Validation of the node_id parameter,
     * it is considered valid if node_id belongs to [0, inf]
     *
     * @param String $node_id
     * @return -2 => invalid id (e.g. NaN, negative id)
     *         -1 => missing id
     *          0 => OK
     */
    private static function validateNodeId($node_id)
    {
        if ($node_id === null || $node_id === '') {
            // does not use empty() to avoid node_id '0' to be treated as invalid
            return -1;
        }
        $node_id = ltrim($node_id, '0');
        // if the id is empty after trim => node_id = 0
        if ($node_id === '') {
            return 0;
        }
        $int_id = (int) $node_id;
        // check if the node_id contains non-numeric characters
        if ((string)$int_id !== $node_id || $int_id < 0) {
            return -2;
        }
        return 0;
    }

    /**
     * Validates the language parameter, for now any non-empty
     * string is fine. It gets sanitized from injections in DbMngr.
     *
     * @param string $language
     * @return int   -1 => missing parameter
     *                0 => OK
     */
    private static function validateLanguage($language)
    {
        if (empty($language)) {
            return -1;
        }
        return 0;
    }

    /**
     * Validate page_num param
     *
     * @param string $pageNumber
     * @return int    -3 => out of ranges or NaN
     *                 0 => OK
     */
    private static function validatePageNumber($pageNumber)
    {
        $intPageNumber = (int) $pageNumber;
        // check if the pageNumber contains non-numeric characters
        if ((string)$intPageNumber !== $pageNumber || $intPageNumber < 0) {
            return -3;
        }
        return 0;
    }

    /**
     * Validate page_size param
     *
     * @param string $pageNumber
     * @return int    -3 => out of ranges or NaN
     *                 0 => OK
     */
    private static function validatePageSize($pageSize)
    {
        $intPageSize = (int) $pageSize;
        // check if the pagesize contains non-numeric characters
        if ((string)$intPageSize !== $pageSize || $intPageSize < 1 || $intPageSize > 1000) {
            return -4;
        }
        return 0;
    }
}
