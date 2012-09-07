<?php
/**
 * Nella_Sniffs_Namespaces_FullClassThrowExceptionSniff.
 *
 * PHP version 5.3
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Patrik Votoček <patrik@votocek.cz>
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

/**
 * Nella_Sniffs_Namespaces_FullClassThrowExceptionSniff.
 *
 * Ensures throw full class.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Patrik Votoček <patrik@votocek.cz>
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class Nella_Sniffs_Namespaces_FullClassThrowExceptionSniff implements PHP_CodeSniffer_Sniff
{


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_THROW);

    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in
     *                                        the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Ignore if is not creating new instance
        $new = $phpcsFile->findNext(T_NEW, $stackPtr);
        if (!$new) {
        	return;
        }

		$next = $stackPtr + 1;
		foreach (array_slice($tokens, $next, $new - $next) as $token) {
			if ($token['code'] != T_WHITESPACE) {
				return;
			}
        }

        $error = 'Throw exception must be full class';
        $nsSeparator = $phpcsFile->findNext(T_NS_SEPARATOR, $new);
        if (!$nsSeparator) {
			$phpcsFile->addError($error, $stackPtr, 'ThrowNew');
        }
        $next = $new + 1;
        foreach (array_slice($tokens, $next, $nsSeparator - $next) as $token) {
			if ($token['code'] != T_WHITESPACE) {
				$phpcsFile->addError($error, $stackPtr, 'ThrowNew');
				break;
			}
        }
    }//end process()


}//end class

