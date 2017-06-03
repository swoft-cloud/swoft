<?php

namespace PhpDocReader\PhpParser;

use SplFileObject;

/**
 * Parses a file for "use" declarations.
 *
 * Class taken and adapted from doctrine/annotations to avoid pulling the whole package.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Christian Kaps <christian.kaps@mohiva.com>
 */
class UseStatementParser
{
    /**
     * @return array A list with use statements in the form (Alias => FQN).
     */
    public function parseUseStatements(\ReflectionClass $class)
    {
        if (false === $filename = $class->getFilename()) {
            return array();
        }

        $content = $this->getFileContent($filename, $class->getStartLine());

        if (null === $content) {
            return array();
        }

        $namespace = preg_quote($class->getNamespaceName());
        $content = preg_replace('/^.*?(\bnamespace\s+' . $namespace . '\s*[;{].*)$/s', '\\1', $content);
        $tokenizer = new TokenParser('<?php ' . $content);

        $statements = $tokenizer->parseUseStatements($class->getNamespaceName());

        return $statements;
    }

    /**
     * Gets the content of the file right up to the given line number.
     *
     * @param string  $filename   The name of the file to load.
     * @param integer $lineNumber The number of lines to read from file.
     *
     * @return string The content of the file.
     */
    private function getFileContent($filename, $lineNumber)
    {
        if ( ! is_file($filename)) {
            return null;
        }

        $content = '';
        $lineCnt = 0;
        $file = new SplFileObject($filename);
        while (!$file->eof()) {
            if ($lineCnt++ == $lineNumber) {
                break;
            }

            $content .= $file->fgets();
        }

        return $content;
    }
}
