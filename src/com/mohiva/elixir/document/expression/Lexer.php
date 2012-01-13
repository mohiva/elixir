<?php
/**
 * Mohiva Elixir
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.textile.
 * It is also available through the world-wide-web at this URL:
 * https://github.com/mohiva/pyramid/blob/master/LICENSE.textile
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir/Document/Expression
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
namespace com\mohiva\elixir\document\expression;

use com\mohiva\common\parser\TokenStream;
use com\mohiva\pyramid\Token;

/**
 * Tokenize a document expression.
 * 
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir/Document/Expression
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
class Lexer {
	
	/**
	 * Expression tokens.
	 * 
	 * @var int
	 */
	const T_NONE              = 0;
	
	const T_OPEN_PARENTHESIS  = 101;  // (
	const T_CLOSE_PARENTHESIS = 102;  // )
	const T_OPEN_ARRAY        = 103;  // [
	const T_CLOSE_ARRAY       = 104;  // ]
	const T_POINT             = 105;  // .
	const T_COMMA             = 106;  // ,
	const T_QUESTION_MARK     = 107;  // ?
	const T_COLON             = 108;  // :
	const T_DOUBLE_COLON      = 109;  // ::
	const T_NS_SEPARATOR      = 110;  // \
	const T_VALUE             = 111;  // "",'',1,0.1,true,false,null
	const T_NAME              = 112;  // [a-zA-Z0-9_]
	
	const T_NOT               = 201;  // !
	const T_PLUS              = 202;  // +
	const T_MINUS             = 203;  // -
	const T_MUL               = 204;  // *
	const T_DIV               = 205;  // /
	const T_MOD               = 206;  // %
	const T_POWER             = 207;  // ^
	const T_ASSIGN            = 208;  // =
	const T_EQUAL             = 209;  // ==
	const T_NOT_EQUAL         = 210;  // !=
	const T_LESS              = 211;  // <
	const T_LESS_EQUAL        = 212;  // <=
	const T_GREATER           = 213;  // >
	const T_GREATER_EQUAL     = 214;  // >=
	const T_OR                = 215;  // ||
	const T_AND               = 216;  // &&
	const T_STRING_CAST       = 217;  // (string)
	const T_INT_CAST          = 218;  // (int)
	const T_FLOAT_CAST        = 219;  // (float)
	const T_BOOL_CAST         = 220;  // (bool)
	const T_XML_CAST          = 221;  // (xml)
	
	/**
	 * The lexemes to find the tokens.
	 * 
	 * @var array
	 */
	private $lexemes = array(
		"('(?:[^'\\\\]|\\\\['\"]|\\\\)*')",
		'("(?:[^"\\\]|\\\["\']|\\\)*")',
		//'("[^"\\\]*(?:\\\.[^"\\\]*)*)"',
		//"('[^'\\\\]*(?:\\\\.[^'\\\\]*)*)'",
		'([0-9]+\.?[0-9]*)',
		'([A-Za-z0-9_]+)',
		'(\:\:|==|!=|>=|<=|&&|\|\|)',
		'(\(string\)|\(int\)|\(float\)|\(bool\)|\(xml\))',
		'(.)'
	);
	
	/**
	 * Map the constant values with its token type.
	 * 
	 * @var int[]
	 */
	private $constTokenMap = array(
		'('         => self::T_OPEN_PARENTHESIS,
		')'         => self::T_CLOSE_PARENTHESIS,
		'['         => self::T_OPEN_ARRAY,
		']'         => self::T_CLOSE_ARRAY,
		'.'         => self::T_POINT,
		','         => self::T_COMMA,
		'?'         => self::T_QUESTION_MARK,
		':'         => self::T_COLON,
		'::'        => self::T_DOUBLE_COLON,
		'\\'        => self::T_NS_SEPARATOR,
		'!'         => self::T_NOT,
		'+'         => self::T_PLUS ,
		'-'         => self::T_MINUS,
		'*'         => self::T_MUL,
		'/'         => self::T_DIV,
		'%'         => self::T_MOD,
		'^'         => self::T_POWER,
		'='         => self::T_ASSIGN,
		'=='        => self::T_EQUAL,
		'!='        => self::T_NOT_EQUAL,
		'<'         => self::T_LESS,
		'<='        => self::T_LESS_EQUAL,
		'>'         => self::T_GREATER,
		'>='        => self::T_GREATER_EQUAL,
		'||'        => self::T_OR,
		'&&'        => self::T_AND,
		'(string)'  => self::T_STRING_CAST,
		'(int)'     => self::T_INT_CAST,
		'(float)'   => self::T_FLOAT_CAST,
		'(bool)'    => self::T_BOOL_CAST,
		'(xml)'     => self::T_XML_CAST
	);
	
	/**
	 * The token stream.
	 * 
	 * @var \com\mohiva\framework\util\TokenStream
	 */
	private $stream = null;
	
	/**
	 * The class constructor.
	 *
	 * @param \com\mohiva\common\parser\TokenStream $stream The token stream to use for this lexer.
	 */
	public function __construct(TokenStream $stream) {
		
		$this->stream = $stream;
	}
	
	/**
	 * Return the token stream instance.
	 * 
	 * @return \com\mohiva\common\parser\TokenStream The token stream to use for this lexer.
	 */
	public function getStream() {
		
		return $this->stream;
	}
	
	/**
	 * Tokenize the given input string and return the resulting token stream.
	 * 
	 * @param string $input The string input to scan.
	 * @return \com\mohiva\common\parser\TokenStream The resulting token stream.
	 */
	public function scan($input) {
		
		$this->stream->flush();
		$this->stream->setSource($input);
		$this->tokenize($input);
		$this->stream->rewind();
		
		return $this->stream;
	}
	
	/**
	 * Transform the input string into a token stream.
	 * 
	 * @param string $input The string input to tokenize.
	 */
	private function tokenize($input) {
		
		$pattern = '/' . implode('|', $this->lexemes) . '/';
		$flags = PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_OFFSET_CAPTURE;
		$matches = preg_split($pattern, $input, -1, $flags);
		foreach ($matches as $match) {
			
			$value = strtolower($match[0]);
			if ($value[0] == "'" ||
				$value[0] == '"' ||
				$value == 'true' ||
				$value == 'false' ||
				$value == 'null' ||
				is_numeric($value)) {
				
				$code = self::T_VALUE;
			} else if (isset($this->constTokenMap[$value])) {
				$code = $this->constTokenMap[$value];
			} else if (preg_match('/[a-z0-9_]+/', $value)) {
				$code = self::T_NAME;
			} else if (ctype_space($value)) {
				continue;
			} else {
				$code = self::T_NONE;
			}
			
			$this->stream->push(new Token(
				$code,
				$match[0],
				$match[1]
			));
		}
	}
}
