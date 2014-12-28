<?php
/**
* This file is part of the Nella Project (http://nella-project.org).
*
* Copyright (c) Patrik Votoček (http://patrik.votocek.cz)
*
* For the full copyright and license information,
* please view the file LICENSE.md that was distributed with this source code.
*/

namespace Nella\Sniffs\Namespaces;

class FullyQualifiedExceptionsSniff implements \PHP_CodeSniffer_Sniff
{


	/**
	 * @return array
	 */
	public function register()
	{
		return array(T_THROW);
	}


	/**
	 * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
	 * @param int $stackPtr The position of the current token in the stack passed in $tokens.
	 */
	public function process(\PHP_CodeSniffer_File $phpcsFile, $stackPtr)
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

		$error = 'Throw exception must be fully qualified';
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
	}

}
