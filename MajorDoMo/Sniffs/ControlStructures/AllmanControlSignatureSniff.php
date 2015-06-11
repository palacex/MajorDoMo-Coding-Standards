<?php
/**
 * Verifies that control statements conform to their coding standards.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Louis Linehan <louis.linehan@gmail.com>
 * @copyright 2006-2014 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

/**
 * Verifies that control statements conform to their coding standards.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006-2014 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 * @version   Release: 2.3.0
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class MajorDoMo_Sniffs_ControlStructures_AllmanControlSignatureSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * A list of tokenizers this sniff supports.
     *
     * @var array
     */
    public $supportedTokenizers = array(
                                   'PHP',
                                   'JS',
                                  );


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return int[]
     */
    public function register()
    {
        return array(
                T_TRY,
                T_CATCH,
                T_DO,
                T_WHILE,
                T_FOR,
                T_IF,
                T_FOREACH,
                T_ELSE,
                T_ELSEIF,
               );

    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Scope keyword should be on a new line.
        if (isset($tokens[$stackPtr]['scope_opener']) === true
            || $tokens[$stackPtr]['code'] === T_WHILE
            || $tokens[($stackPtr)]['code'] === T_ELSE
        ) {
            $prevContentPtr = $phpcsFile->findPrevious(T_WHITESPACE, ($stackPtr - 1), null, true);

            // Skip if this T_IF is part of an else if.
            if ($tokens[($stackPtr)]['code'] !== T_IF
                && $tokens[($prevContentPtr)]['code'] !== T_ELSE
            ) {
                $keywordLine     = $tokens[($stackPtr)]['line'];
                $prevContentLine = $tokens[($prevContentPtr)]['line'];

                if ($keywordLine === $prevContentLine) {
                    $data  = array($tokens[$stackPtr]['content']);
                    $error = 'Scope keyword "%s" should be on a new line';
                    $fix   = $phpcsFile->addFixableError($error, $stackPtr, 'ScopeKeywordOnNewLine', $data);
                    if ($fix === true) {
                        $phpcsFile->fixer->beginChangeset();
                        $phpcsFile->fixer->addNewlineBefore($stackPtr);
                        $phpcsFile->fixer->endChangeset();
                    }
                }
            }
        }//end if

        // Expect 1 space after keyword, Skip T_ELSE, T_DO, T_TRY.
        if (isset($tokens[$stackPtr]['scope_opener']) === true
            && $tokens[($stackPtr)]['code'] !== T_ELSE
            && $tokens[($stackPtr)]['code'] !== T_DO
            && $tokens[($stackPtr)]['code'] !== T_TRY
        ) {
            $found = 1;
            if ($tokens[($stackPtr + 1)]['code'] !== T_WHITESPACE) {
                $found = 0;
            } else if ($tokens[($stackPtr + 1)]['content'] !== ' ') {
                if (strpos($tokens[($stackPtr + 1)]['content'], $phpcsFile->eolChar) !== false) {
                    $found = 'newline';
                } else {
                    $found = strlen($tokens[($stackPtr + 1)]['content']);
                }
            }

            if ($found !== 1) {
                $error = 'Expected 1 space after scope keyword "%s", found %s';
                $data  = array(
                          strtoupper($tokens[$stackPtr]['content']),
                          $found,
                         );

                $fix = $phpcsFile->addFixableError($error, $stackPtr, 'SpaceAfterScopeKeyword', $data);
                if ($fix === true) {
                    if ($found === 0) {
                        $phpcsFile->fixer->addContent($stackPtr, ' ');
                    } else {
                        $phpcsFile->fixer->replaceToken(($stackPtr + 1), ' ');
                    }
                }
            }
        }//end if

        // Opening brace should be on a new line.
        if (isset($tokens[$stackPtr]['scope_opener']) === true) {
            $openingBracePtr = $tokens[$stackPtr]['scope_opener'];
            $braceLine       = $tokens[$openingBracePtr]['line'];

            if ($tokens[($stackPtr)]['code'] === T_ELSE
                || $tokens[($stackPtr)]['code'] === T_TRY
                || $tokens[($stackPtr)]['code'] === T_DO
            ) {
                $scopeLine = $tokens[$stackPtr]['line'];
                // Measure from the scope opener.
                $lineDifference = ($braceLine - $scopeLine);
            } else {
                $closerLine = $tokens[$tokens[$stackPtr]['parenthesis_closer']]['line'];
                // Measure from the scope closing parenthesis.
                $lineDifference = ($braceLine - $closerLine);
            }

            if ($lineDifference !== 1) {
                $data = array(
                         $tokens[$openingBracePtr]['content'],
                         $tokens[$stackPtr]['content'],
                        );
                if (isset($closerLine) === true) {
                    $error = 'Opening brace "%s" should be on a new line after "%s (...)"';
                } else {
                    $error = 'Opening brace "%s" should be on a new line after the keyword "%s"';
                }

                $fix = $phpcsFile->addFixableError($error, $openingBracePtr, 'ScopeOpeningBraceOnNewLine', $data);
                if ($fix === true) {
                    $prevContentPtr = $phpcsFile->findPrevious(T_WHITESPACE, ($openingBracePtr - 1), null, true);
                    $phpcsFile->fixer->beginChangeset();
                    for ($i = ($prevContentPtr + 1); $i < $openingBracePtr; $i++) {
                        $phpcsFile->fixer->replaceToken($i, '');
                    }

                    $phpcsFile->fixer->addNewlineBefore($openingBracePtr);
                    $phpcsFile->fixer->endChangeset();
                }
            }//end if
        }//end if

        // No empty lines after opening brace.
        if (isset($tokens[$stackPtr]['scope_opener']) === true) {
            $openerPtr = $tokens[$stackPtr]['scope_opener'];

            $nextContentPtr = $phpcsFile->findNext(T_WHITESPACE, ($openerPtr + 1), null, true);

            $braceLine       = $tokens[$openerPtr]['line'];
            $nextContentLine = $tokens[$nextContentPtr]['line'];

            $lineDifference = ($nextContentLine - $braceLine);

            if ($lineDifference === 0 || $lineDifference > 1) {
                $data  = array($tokens[$openerPtr]['content']);
                $error = 'Expected content on line after "%s"';
                $fix   = $phpcsFile->addFixableError($error, $openerPtr, 'NewlineAfterOpeningBrace', $data);
                if ($fix === true) {
                    $phpcsFile->fixer->beginChangeset();
                    for ($i = ($openerPtr + 1); $i < $nextContentPtr; $i++) {
                        $phpcsFile->fixer->replaceToken($i, '');
                    }

                    $phpcsFile->fixer->addContent($openerPtr, $phpcsFile->eolChar);
                    $phpcsFile->fixer->endChangeset();
                }
            }
        }//end if

        // Closing brace should be on a new line.
        if (isset($tokens[$stackPtr]['scope_closer']) === true) {
            $closerPtr = $tokens[$stackPtr]['scope_closer'];

            $prevContentPtr = $phpcsFile->findPrevious(T_WHITESPACE, ($closerPtr - 1), null, true);

            $braceLine       = $tokens[$closerPtr]['line'];
            $prevContentLine = $tokens[$prevContentPtr]['line'];

            $lineDifference = ($braceLine - $prevContentLine);

            if ($lineDifference !== 1) {
                $data  = array($tokens[$closerPtr]['content']);
                $error = 'Closing brace "%s" should be on a new line';
                $fix   = $phpcsFile->addFixableError($error, $closerPtr, 'ClosingBraceOnNewLine', $data);
                if ($fix === true) {
                    $phpcsFile->fixer->beginChangeset();
                    for ($i = ($prevContentPtr + 1); $i < $closerPtr; $i++) {
                        $phpcsFile->fixer->replaceToken($i, '');
                    }

                    $phpcsFile->fixer->addNewlineBefore($closerPtr);
                    $phpcsFile->fixer->endChangeset();
                }
            }
        }//end if

    }//end process()


}//end class