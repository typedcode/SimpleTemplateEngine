<?php

/**
 * @author Markus Hoffmann
 * SimpleTemplateEngine Version 0.8
 */

class SimpleTemplateEngine {

    private $allowedSignes = "[a-zA-Z0-9_-]";
    private $allowedSignesExtendet = "[a-zA-Z0-9_>()-]";
    private $regStringValue;
    private $regVar;
    private $regFunction;
    private $cssFilePaths = array();
    private $jsFilePaths = array();


    /**
     * Directory where the templates are located.
     *
     * @var String Path to the templates.
     */
    private $tplDir;

    /**
     * Array of registered Variables
     *
     * @var array Variables that can be set via setVariable($key, $value) and used in the template
     */
    private $assignedVariables = array();

    /**
     * Creates a new Template-Object and registeres the Tempalte-Directory.
     *
     * @param string $templateDir Folder where the templates can be found.
     */
    public function __construct( $templateDir = "" ) {
        $this->tplDir = $templateDir;

        if( $templateDir == "" ) {
            $this->tplDir = "./";
        }
        else if( substr( $this->tplDir, - 1 ) != '/' ) {
            $this->tplDir .= "/";
        }

        $this->regStringValue = "(\"(" . $this->allowedSignes . "+)\")";

        $this->regVar = "(\\$" . $this->allowedSignes ."+)";
        $this->regFunction = "(->" . $this->allowedSignes ."+\(\))";

        if( isset( $_SESSION ) ) {
            $this->assign( "SESSION", $_SESSION );
        }
    }

    /**
     * Returns the directory to the Templates
     *
     * @return string Path to the Templates
     */
    public function getTemplateDir() {
        return $this->tplDir;
    }

    /**
     * Assigns a value to a variable.
     *
     * @param String $key
     *            Key to the Variable in the template.
     * @param unknown $value
     *            Value to replace the variable in the template with.
     */
    public function assign( $key, $value ) {
        $this->assignedVariables[ $key ] = $value;
    }

    /**
     * Parses the template and returnes the parsed template.
     *
     * @param String $template
     *            Template to parse
     */
    public function parse( $template ) {
        $path = $this->tplDir . $template;

        if( ! file_exists( $path ) ) {
            throw new Exception( "ERROR: Template file to display '" . $template . "' does not exist." );
        }

        return $this->parseImpl( file_get_contents( $path ) );
    }

    private function parseImpl( $parseString, $additionalVars = array() ) {
        $parseString = $this->evaluateIfs( $parseString, $additionalVars );
        $parseString = $this->evaluateForeach( $parseString, $additionalVars );
        $parseString = $this->evaluateInclude( $parseString, $additionalVars );
        $parseString = $this->evaluateForeachCSS( $parseString );
        $parseString = $this->evaluateForeachJS( $parseString );
        $parseString = $this->substituteVars( $parseString, $additionalVars );

        return $parseString;
    }

    private function evaluateForeachCSS( $content ) {
        $result = preg_match( "/(\{foreachcss})(.*)({\/foreachcss})/s", $content, $matches, PREG_OFFSET_CAPTURE );

        if( $result == 0 ) {
            return $content;
        }

        $before = substr( $content, 0, $matches[0][1] );
        $cssCode = $matches[2][0];
        $after = substr( $content, $matches[3][1] + 13 );

        $content = $before;

        foreach( $this->cssFilePaths as $cssPath ) {
            $content .= str_replace( "{\$__CSS}", $cssPath, $cssCode );
        }

        $content .= $after;

        return $content;
    }

    private function evaluateForeachJS( $content ) {
        $result = preg_match( "/(\{foreachjs})(.*)({\/foreachjs})/s", $content, $matches, PREG_OFFSET_CAPTURE );

        if( $result == 0 ) {
            return $content;
        }

        $before = substr( $content, 0, $matches[0][1] );
        $jsCode = $matches[2][0];
        $after = substr( $content, $matches[3][1] + 12 );

        $content = $before;

        foreach( $this->jsFilePaths as $jsPath ) {
            $content .= str_replace( "{\$__JS}", $jsPath, $jsCode );
        }

        $content .= $after;

        return $content;
    }

    private function evaluateForeach( $content, $additionalVars = array() ) {
        preg_match_all( "/\{foreach [^\}]+\}|\{\/foreach\}/", $content, $matches, PREG_OFFSET_CAPTURE );

        $results = $matches[ 0 ];

        if( sizeof( $results ) == 0 ) {
            return $content;
        }

        $before = substr( $content, 0, $results[ 0 ][ 1 ] );
        $foreachContentStart = strlen( $results[ 0 ][ 0 ] ) + $results[ 0 ][ 1 ];
        $depth = 0;

        for( $i = 1; $i < sizeof( $results ); $i++ ) {
            if( $results[ $i ][ 0 ] !== "{/foreach}" ) {
                $depth++;
                continue;
            }
            else {
                if( $depth != 0 ) {
                    $depth--;
                    continue;
                }
                else {
                    $foreachContent = substr( $content, $foreachContentStart, $results[$i][1] - $foreachContentStart );
                    $after = substr( $content, $results[$i][1] + 10 );
                    break;
                }
            }
        }

        $reg = "/\\$((" . $this->allowedSignesExtendet . ")+)( as )\\$((" . $this->allowedSignes . ")+)/";

        $splitResult = preg_split( $reg, $results[ 0 ][ 0 ], 2, PREG_SPLIT_DELIM_CAPTURE );

        if( sizeof( $splitResult ) === 1 ) {
            return $content;
        }

        $resultParts = explode( "->", $splitResult[ 1 ] );
        $array = array();

        if( count( $resultParts ) != 1 ) {
            $array = $this->assignedVariables[ $resultParts[ 0 ] ];
            for( $i = 1; $i < count( $resultParts ); $i++) {
                $fieldOrFunction = $resultParts[ $i ];

                //If () was not found, a filed is accessed
                if( !strpos( $fieldOrFunction, "()" ) ) {
                    $array = $array->$fieldOrFunction;
                }
                else {
                    //Remove the braces at the end of the functionString
                    $fieldOrFunction = substr( $fieldOrFunction, 0, -2 );
                    $array = $array->$fieldOrFunction();
                }
            }
        }
        else {
            $array = $this->getValue( $splitResult[ 1 ], $additionalVars );
        }

        $content = $before;

        foreach( $array as $value ) {
            $content .= $this->parseImpl( $foreachContent, array_merge( $additionalVars, array( $splitResult[4] => $value ) ) );
        }

        $content .= $after;
        return $content;
    }

    /**
     * Goes through every $this->assignedVariables and replaces every appereance in $content with the value.
     */
    private function substituteVars( $content, $additionalVars = array() ) {
        $varRegEx = "/(\{\\$)([a-zA-Z0-9_]{1,})((->)([a-zA-Z0-9_\()]{1,}))?(})/";

        while( preg_match( $varRegEx, $content ) ) {
            $result = preg_split( $varRegEx, $content, 2, PREG_SPLIT_DELIM_CAPTURE );

            $content = $result[ 0 ];

            $content .= $this->getVarValue( $result[ 2 ], $result[ 5 ], $additionalVars );

            $content .= $result[ 7 ];
        }

        return $content;
    }

    /**
     *
     */
    private function getVarValue( $varName, $functionOrMemberName, $additionalVars = array() ) {
        $valueForKey = $this->getValue( $varName, $additionalVars );

        if( is_null( $valueForKey ) ) {
            throw new Exception( "TEMPLATE ERROR: '" . $varName . "' ist not assigned." );
        }

        if( strlen( $functionOrMemberName ) > 0 ) {

            $execute = rtrim( $functionOrMemberName, "()" );

            if( $execute == $functionOrMemberName ) {
                if( is_array( $valueForKey ) ) {
                    return $valueForKey[ $execute ];
                }
                else {
                    return $valueForKey->$execute;
                }
            }
            else {
                return $valueForKey->$execute();
            }
        }

        return $valueForKey;
    }

    private function evaluateInclude( $content, $additionalVars = array() ) {
        $includeRegEx = "/(\{)(include)(\s)(\\$){0,1}([a-zA-Z\/.-_]{1,})(})/";

        while( preg_match( $includeRegEx, $content ) ) {
            $result = preg_split( $includeRegEx, $content, 2, PREG_SPLIT_DELIM_CAPTURE );

            $templatePath = "";

            if( $result[ 4 ] == "$" ) {
                $templatePath = $this->getValue( $result[ 5 ] );
            }
            else {
                $templatePath = $result[ 5 ];
            }

            $content = $result[ 0 ];

            $path = $this->tplDir . $templatePath;

            if( ! file_exists( $path ) ) {
                throw new Exception( "ERROR: Could not find Template '" . $templatePath . "' in given Template directory." );
            }

            $content .= $this->parseImpl( file_get_contents( $path ), $additionalVars );
            $content .= $result[ 7 ];
        }

        return $content;
    }

    /**
     * Searches for patterns like {if TEST} {fi} in the template and evaluates them.
     */
    private function evaluateIfs( $content, $additionalVars = array() ) {
        preg_match_all( "/\{if [^\}]+\}|\{else\}|\{\/if\}/", $content, $matches, PREG_OFFSET_CAPTURE );

        $results = $matches[ 0 ];

        while( sizeof( $results ) > 0 ) {

            $evaluationString = substr( $results[ 0 ][ 0 ], 3, -1 );
            $before = substr( $content, 0, $results[ 0 ][ 1 ] );
            $ifContentStartIndex = $results[ 0 ][ 1 ] + strlen( $results[ 0 ][ 0 ] );
            $ifContent = "";
            $after = "";
            $elseContentStartIndex = -1;
            $elseContent = "";

            $depth = 0;

            for( $var = 1; $var < sizeof( $results ); $var++ ) {
                $current = $results[ $var ];

                if( $this->startsWith( $current[ 0 ], "{if") ) {
                    $depth ++;
                    continue;
                }

                if( $depth == 0 ) {
                    if( $current[ 0 ] === "{/if}" ) {
                        if( $elseContentStartIndex === -1 ) {
                            $ifContent = substr( $content, $ifContentStartIndex, $current[ 1 ] - $ifContentStartIndex );
                        }
                        else {
                            $elseContent = substr( $content, $elseContentStartIndex, $current[ 1 ] - $elseContentStartIndex );
                        }
                        $after = substr( $content, $current[ 1 ] + 5 );
                        break;
                    }
                    else {
                        //Here starts the else section
                        $ifContent = substr( $content, $ifContentStartIndex, $current[ 1 ] - $ifContentStartIndex );
                        $elseContentStartIndex = $current[ 1 ] + 6;
                    }
                }
                else if( $current[ 0 ] === "{/if}" ) {
                    $depth--;
                    continue;
                }
            }

            $content = $before;

            if( $this->evaluateTest( $evaluationString, $additionalVars ) ) {
                $content .= $ifContent;
            }
            else{
                $content .= $elseContent;
            }

            $content .= $after;

            preg_match_all( "/\{if[^\}]+\}|\{else\}|\{\/if\}/", $content, $matches, PREG_OFFSET_CAPTURE );

            $results = $matches[ 0 ];

        }

        return $content;
    }

    private function startsWith ( $string, $startString ) {
        $len = strlen( $startString );
        return ( substr( $string, 0, $len ) === $startString );
    }

    private function evaluateTest( $evaluationString, $additionalVars = array() ) {
        $evaluationString = trim( $evaluationString );

        $negate = false;

        $result = false;

        $evalStart = substr( $evaluationString, 0, 4 );

        if( $evalStart == "NOT(" ) {
            $negate = true;
            $evaluationString = substr( $evaluationString, 4, - 1 );
        }

        if( strpos( $evaluationString, " " ) !== false ) {
            $evalStringRegEx = "/(\\$)?([a-zA-Z0-9]*)(->([a-zA-Z]*(\(\))?))?( )(eq|lt|gt|ne)( )(\\$)?([a-zA-Z0-9]*)(->([a-zA-Z]*(\(\))?))?/";

            if( preg_match( $evalStringRegEx, $evaluationString ) ) {
                $evalStringResult = preg_split( $evalStringRegEx, $evaluationString, null, PREG_SPLIT_DELIM_CAPTURE );

                if( $evalStringResult[ 1 ] == "$" ) {
                    $valueLeft = $this->getVarValue( $evalStringResult[ 2 ], $evalStringResult[ 4 ], $additionalVars );
                }
                else {
                    $valueLeft = $evalStringResult[ 2 ];
                }

                if( $evalStringResult[ 9 ] == "$" ) {
                    if( isset( $evalStringResult[ 12 ] ) ) {
                        $func = $evalStringResult[ 12 ];
                    }
                    else {
                        $func = "";
                    }

                    $valueRight = $this->getVarValue( $evalStringResult[ 10 ], $func, $additionalVars );
                }
                else {
                    $valueRight = $evalStringResult[ 10 ];
                }

                switch( $evalStringResult[ 7 ] ) {
                    case "eq":
                        {
                            return $valueLeft == $valueRight;
                        }

                    case "gt":
                        {
                            if( ! is_numeric( $valueLeft ) || ! is_numeric( $valueRight ) ) {
                                throw new Exception( "ERROR evaluating if Clause. One Value is not Numeric: " . $valueLeft . " | " . $valueRight );
                            }
                            return $valueLeft > $valueRight;
                        }
                    case "lt":
                        {
                            if( ! is_numeric( $valueLeft ) || ! is_numeric( $valueRight ) ) {
                                throw new Exception( "ERROR evaluating if Clause. One Value is not Numeric: " . $valueLeft . " | " . $valueRight );
                            }
                            return $valueLeft < $valueRight;
                        }
                    case "ne":
                        {
                            return $valueLeft != $valueRight;
                        }
                }
            }
            else {
                throw new Exception( "ERROR in evaluationstring: " . $evaluationString );
            }
        }
        else {
            // boolean evaluation
            $result = $this->evaluateBoolean( $evaluationString, $additionalVars );
        }

        if( $negate ) {
            return ! $result;
        }

        return $result;
    }

    /**
     * Returns the value for the key $key
     */
    private function getValue( $key, $additionalVars = array() ) {

        $allVars = array_merge( $this->assignedVariables, $additionalVars );

        if( array_key_exists( $key, $allVars ) ) {
            return $allVars[ $key ];
        }

        return null;
    }

    private function evaluateBoolean( $boolean, $additionalVars = array() ) {
        $varStart = substr( $boolean, 0, 1 );
        $value = "";

        $issetRegex = "/isset\((" . $this->allowedSignes . "*)\)/s";
        $inArrayRegex = "/in_array\((" . $this->regVar . "(" . $this->regFunction . ")?),(" . $this->regVar . "(" . $this->regFunction . ")?|" . $this->regStringValue . ")\)/s";

        if( preg_match( $issetRegex, $boolean ) ) {
            $results = preg_split( $issetRegex, $boolean, null, PREG_SPLIT_DELIM_CAPTURE );
            $value = $this->getValue( $results[ 1 ], $additionalVars );

            if( is_null( $value ) ) {
                return false;
            }

            return true;
        }
        else if( preg_match( $inArrayRegex, $boolean) ) {
            $splitResult = preg_split( $inArrayRegex, $boolean, null, PREG_SPLIT_DELIM_CAPTURE);

            $arrayVarName = ltrim($splitResult[2], "$");

            //getting the haystack
            $haystack = $this->getValue( $arrayVarName, $additionalVars );

            if( !empty( $splitResult[3] ) ){
                $method = $splitResult[3];
                $method = ltrim( $method, "->" );
                $method = rtrim( $method, "()");
                $haystack = $haystack->$method();
            }

            //Determain the needle!
            if( isset( $splitResult[10] ) ) {
                $needle = $splitResult[10];
            }else {
                $needle = ltrim( $splitResult[6], "$" );
                $needle = $this->getValue( $needle, $additionalVars );

                if( !empty( $splitResult[7]) ) {
                    $method = $splitResult[7];
                    $method = ltrim( $method, "->" );
                    $method = rtrim( $method, "()" );
                    $needle = $needle->$method();
                }
            }

            return in_array( $needle, $haystack );
        }
        else
            if( $varStart == "$" ) {
                $key = substr( $boolean, 1 );
                // if starts with $ the var should be found in $this->assignedVariables
                $value = $this->getValue( $key, $additionalVars );

                if( is_null( $value ) ) {
                    return false;
                }

                if( is_bool( $value ) ) {
                    return $this->getValue( $key, $additionalVars );
                }
            }
            else {
                if( "TRUE" == strtoupper( $boolean ) ) {
                    return true;
                }
                else
                    if( "FALSE" == strtoupper( $boolean ) ) {
                        return false;
                    }
            }

        throw new Exception( "TEMPLATE ERROR: Value '" . $value . "' could not be evaluated to a boolean value." );
    }

    public function addCSSFilePath( $cssFilePath ) {
        if( empty( $cssFilePath ) || in_array( $cssFilePath, $this->cssFilePaths ) ) {
            return;
        }

        array_push( $this->cssFilePaths, $cssFilePath );
    }

    public function getCSSFilePaths() : array {
        return $this->cssFilePaths;
    }

    public function addJSFilePath( $jsFilePath ) {
        if( empty( $jsFilePath ) ||  in_array( $jsFilePath, $this->jsFilePaths ) ) {
            return;
        }

        array_push( $this->jsFilePaths, $jsFilePath );
    }

    public function getJSFilePaths() : array {
        return $this->jsFilePaths;
    }
}

?>
