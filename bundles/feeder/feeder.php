<?php
/*
  Standards-compliant, uniform, Unicode-aware feed generator.
  by Proger_XP | in Public Domain | http://proger.i-forge.net/PHP_Feeder/7sg

  Generates valid XML feeds for RSS 0.92, RSS 2.0 and Atom 1.0 from a uniform data source.
  No external dependencies; PHP 5+ is required.

  Documentation used:
  * http://proger.i-forge.net/7Zf       - in-depth review of RSS 0.92/2.0 & Atom standards
  * RSS 0.92 standard                   - http://backend.userland.com/rss092
  * RSS 2.0 standard                    - http://cyber.law.harvard.edu/rss/rss.html
  * Atom 1.0 specification (RFC 4287)   - http://tools.ietf.org/html/rfc4287
*/

class EFeed extends Exception {
  public $obj;

  function __construct($msg, FeedObject $obj = null) {
    parent::__construct($msg);
    $this->obj = $obj;
  }
}

  class ENotATextFeedPath extends Exception {
    public $path, $ext;

    function __construct($path, $ext, TextFeeder $obj = null) {
      parent::__construct("Cannot load text feed data from '$path' - it contains".
                          " neither feed$ext nor channel$ext.");

      $this->path = $path;
      $this->ext = $ext;
    }
  }

  class ENoFeedEntry extends Exception {
    public $entry;

    function __construct($entry, FeedObject $obj = null) {
      parent::__construct("Cannot serve entry '$entry' - it doesn't exist.", $obj);
      $this->entry = $entry;
    }
  }

/************************************************************************
 * Core Feeder classes
 ************************************************************************/

abstract class FeedObject {
  const Version = 1.2;
  const Homepage = 'http://proger.i-forge.net/PHP_Feeder/7sg';

  static $iriEncode = array('"' => '%22', '&' => '%26', '<' => '%3C', '>' => '%3E',
                            '[' => '%5B', '\\' => '%5C', ']' => '%5D', '^' => '%5E',
                            '`' => '%60', '{' => '%7B', '|' => '%7C', '}' => '%7D');

  static $extToMIME = array(
    'css' => 'text/css', 'htm' => 'text/html', 'html' => 'text/html', 'phtml' => 'text/html',
    'shtml' => 'text/html', 'ssi' => 'text/html', 'php' => 'text/html',
    'xml' => 'application/xhtml+xml', 'mht' => 'message/rfc822',
    'swf' => 'application/x-shockwave-flash', 'tar' => 'application/x-tar',
    'gz' => 'application/x-gzip', 'bz2' => 'application/x-bzip2', 'wbm' => 'image/vnd.wap.wbmp',
    'rar' => 'application/x-rar-compressed', 'zip' => 'application/zip',
    '7z' => 'application/x-7z-compressed', 'txt' => 'text/plain', 'wiki' => 'text/plain',
    'wacko' => 'text/plain', 'log' => 'text/plain', 'phps' => 'text/html',
    'rtf' => 'application/rtf', 'pdf' => 'application/pdf', 'doc' => 'application/msword',
    'xls' => 'application/msexcel', 'png' => 'image/png', 'gif' => 'image/gif',
    'jpeg' => 'image/jpeg', 'jpg' => 'image/jpeg', 'bmp' => 'image/x-ms-bmp',
    'wbm' => 'image/vnd.wap.wbmp', 'wbmp' => 'image/vnd.wap.wbmp', 'tga' => 'application/tga',
    'tif' => 'image/tiff', 'tiff' => 'image/tiff', 'ico' => 'image/vnd.microsoft.icon',
    'eps' => 'application/postscript', 'svg' => 'image/svg+xml', 'psd' => 'image/vnd.adobe.photoshop');

  static $imageExt = array('png' => 1, 'gif' => 1, 'jpg' => 1, 'jpeg' => 1, 'ico' => 1);

  public $singleTags = array('area', 'base', 'basefont', 'br', 'col', 'frame', 'hr',
                             'img', 'input', 'link', 'meta', 'param', 'embed',
                             'keygen', 'source', 'track', 'wbr');

  function __construct() { }
  function Error($msg) { throw new EFeed($msg, $this); }

  function IsMIME($type) {
    @list($group, $name) = explode('/', $type, 2);
    return "$group" !== '' and "$name" !== '' and
           ltrim($group.$name, 'a..zA..Z0..9.+-') === '';
  }

  static function ExtOf($file) {
    $pos = strrpos($file, '.');
    return $pos ? strtolower( substr($file, $pos + 1) ) : null;
  }

  function Quote($str, $quotes = ENT_COMPAT, $doubleEncode = true) {
    return htmlspecialchars($str, $quotes, 'utf-8', $doubleEncode);
  }

  function Indent($level) { return str_repeat('  ', $level); }

  protected function Accessor($prop, $new = null) {
    $value = &$this->$prop;

    if ($new !== null) {
      if (is_int($value)) {
        if (is_numeric($new)) {
          $value = (int) $new;
        } else {
          $this->Error("Invalid value for [$prop] - must be a number, [$new] given.");
        }
      } elseif (is_string($value)) {
        $value = (string) $new;
      } else {
        $this->Error("Object property [$prop] cannot be set using Accessor().");
      }
    }

    return $value;
  }

    protected function TimeAccessor($prop, $new = null) {
      if ($new !== null and !is_numeric($new)) {
        $time = strtotime($new);
        if ($time) {
          $new = $time;
        } else {
          $this->Error("Cannot convert [$new] to timestamp.");
        }
      }

      return $this->Accessor($prop, $new);
    }

    protected function ClassArrayAccessor($itemClass, $prop, array $new = null) {
      if ($new !== null) {
        foreach ($new as $item) {
          if (! $item instanceof $itemClass) {
            $this->Error("Array to be set contained a non-$class item object.");
          }
        }
      }

      return $this->Accessor($prop, $new);
    }

  protected function ArrayItemAccessor($prop, $index, $new = null) {
    $value = &$this->$prop;

    if (isset($value[$index]) or $index == count($value)) {
      $new === null or $value[$index] = $new;
      return @$value[$index];
    } elseif ($new !== null) {
      is_object($new) and $new = get_class($new);
      $this->Error("Cannot set array item #$index of [$prop] to [$new] because".
                   " there's no such index.");
    }
  }

  // accessor = function ($value, $type = null)
  function SetFromArray(array $props) {
    $aliases = $this->SetFromArrayAliases();

    foreach ($props as $prop => $value) {
      $args = array($value);
      @list($prop, $arg) = explode(' ', $prop, 2);
      isset($arg) and $args[] = $arg;

      $prop = strtolower($prop);
      isset($aliases[$prop]) and $prop = $aliases[$prop];

      if (is_array($prop)) {
        if (is_object($prop[0])) {
          list($obj, $func) = $prop;
        } else {
          list($prop, $class) = $prop;
          $func = 'SetFromString';

          $value = (array) $value;
          foreach ($value as $item) {
            $obj = new $class;
            $propValue = &$this->$prop;
            $propValue[] = $obj;

            call_user_func_array(array($obj, $func), array($item));
          }

          continue;
        }
      } elseif (isset($this->$prop) and is_object($this->$prop)) {
        $obj = $this->$prop;
        $func = 'SetFromString';
      } else {
        $obj = $this;
        $func = ucfirst($prop);
      }

      if (method_exists($obj, $func)) {
        $strings = (array) $args[0];
        foreach ($strings as &$str) {
          $args[0] = $str;
          call_user_func_array(array($obj, $func), $args);
        }
      }
    }
  }

    function SetFromArrayAliases() {
      // 'title' is used throughout this project for consistency; 'caption' is
      // its alias in most places.
      return array('caption' => 'title');
    }

  // Call/All() are taken from UverseWiki R475.
  static function CallAll($callbacks, array $args = array()) {
    if (is_array($callbacks)) {
      foreach ($callbacks as $callback) {
        if (self::Call($callback, $args)) { return true; }
      }
    }
  }

    static function Call($callback, array $args = array()) {
      $args = (array) $args;

      if (is_array($callback) and count($callback) > 2) {
        $callArgsFirst = $callback[1][0] === '*';
        if ($callArgsFirst) {
          $callArgs = array_merge(array_splice($callback, 2), $args);
        } else {
          $callArgs = array_merge($args,  array_splice($callback, 2));
        }
      } else {
        $callArgs = $args;
      }

      is_array($callback) and $callback[1] = ltrim($callback[1], '*');
      return call_user_func_array($callback, $callArgs);
    }

  function ExpandURL($url, array $baseURLs) {
    foreach ($baseURLs as $base) {
      is_array($base) and $base = reset($base);

      if ($base instanceof FeedObjWithCommonAttrs) {
        $base = $base->BaseURL();
      }

      if (is_string($base) and $base !== '') {
        if (strpos($url, ':')) {
          break;
        } elseif ("$base" !== '') {
          $base = rtrim($base, '\\/').'/';
          if ($url[0] === '/') {
            strpos($base, ':') and $url = $base.ltrim($url, '/');
          } else {
            $url = $base.$url;
          }
        }
      }
    }

    return $url;
  }

  // IRI (Internationalized Resource Identifier) is similar to URI (Uniform RI)
  // but is more language-friendly; is defined in RFC 3987 (http://www.ietf.org/rfc/rfc3987.txt).
  // This function doesn't implement 100% of the spec but it works as far as
  // I've tested it.
  function EncodeIRI($iri, $decode = false) {
    $table = self::$iriEncode;
    $decode and $table = array_flip($table);
    @list($path, $query) = explode('?', $iri, 2);

      if ($path) {
        $decode ? ($table['%20'] = ' ') : ($table[' '] = '%20');
        $path = strtr($path, $table);
      }

      if (isset($query)) {
        $decode ? ($table['+'] = ' ') : ($table[' '] = '+');
        $path .= '?'.strtr($query, $table);
      }

    return $path;
  }

    function DecodeIRI($iri) { return $this->EncodeIRI($iri, true); }

  function HtmlSummaryFrom($str, $words = 150, $cut = '&hellip;') {
    $regexp = '/([\s<>])/';

    $delims = preg_split($regexp.'u', $str, -1, PREG_SPLIT_DELIM_CAPTURE) or
    $delims or $delims = preg_split($regexp, $str, -1, PREG_SPLIT_DELIM_CAPTURE);
    $delims or $delims = array($str);

    $tags = array();
    $isTag = false;
    $result = '';

    if (isset($this->singleTags[0])) {
      $this->singleTags = array_flip($this->singleTags);
    }

    foreach ($delims as $i => $delim) {
      $result .= $delim;

      if ($i % 2) {
        if ($delim === '<') {
          $tag = $delims[$i + 1];
          if ($tag[0] === '/') {
            reset($tags) === substr($tag, 1) and array_shift($tags);
          } elseif (!$isTag and !isset( $this->singleTags[$tag] )) {
            array_unshift($tags, $tag);
          }

          $isTag = true;
        } elseif ($delim === '>') {
          $isTag = false;
        }

        if (!$isTag and --$words < 0) {
          break;
        }
      }
    }

    $result = trim($result);
    foreach ($tags as $tag) { $result .= "</$tag>"; }

    $words < 0 and $result .= $cut;
    return $result;
  }

  // Taken from Laravel 3.1.9 `develop`.
  function TextSummaryFrom($str, $words = 150, $cut = '...') {
    $regexp = '/^\s*+(?:\S++\s*+){1,'.$words.'}/';
		preg_match($regexp.'u', $str, $matches) or preg_match($regexp, $str, $matches);

		strlen($str) === strlen($matches[0]) and $cut = '';
		return rtrim($matches[0]).$cut;
  }
}

abstract class FeedObjWithCommonAttrs
    extends FeedObject {                // RSS 0.92     2.0? Feed? Item?  Atom 1.0 [feed/item]
  protected $lang = 'en';               // language     yes  yes   no     xml:lang
  protected $baseURL = '';              // --           --                xml:base

  function SetFromArrayAliases() {
    return array('language' => 'lang', 'baseurl' => 'baseURL')
           + parent::SetFromArrayAliases();
  }

  function Lang($new = null) {
    $new === null or $new = strtolower($new);
    return $this->Accessor('lang', $new);
  }

  function Language($new = null) { return $this->Accessor('lang', $new); }
  function BaseURL($new = null) { return $this->Accessor('baseURL', $new); }
}

/************************************************************************
 * Feeder entity classes
 ************************************************************************/

abstract class FeedDescriptor           // RSS 0.92     2.0? Feed? Item?  Atom 1.0 [feed/item]
    extends FeedObjWithCommonAttrs {    // ---------    ---- ----- -----  --------------------
  protected $title;                     // title        yes  yes   yes    title
  protected $description;               // description  yes  yes   yes    subtitle (feed)/summary (entry)
  protected $copyright;                 // copyright    yes  yes   no     rights
  protected $links = array();           // --           --                <link>
  //                                       + enclosure in RSS.
  protected $permalink = '';            // link         yes  yes   yes    id/(link=alternate & id)
  //                                       + guid in items in RSS 2.0.
  protected $authors = array();         // managingEditor
  //                                       or author    yes  yes   yes    author
  protected $contributors = array();    // --           --                contributor
  protected $categories = array();      // category     yes  Y 2.0 yes *  category
  protected $updated = 0;               // lastBuildDate  Y  yes   no     updated

  function __construct() {
    parent::__construct();

    $this->title = new FeedText;
    $this->description = new FeedText;
    $this->links = new FeedLinks;
  }

  function SetFromArrayAliases() {
    return array('link' => 'links',
                 'author' => array('authors', 'FeedPerson'),
                 'contributor' => array('contributors', 'FeedPerson'),
                 'category' => array('categories', 'FeedCategory'))
           + parent::SetFromArrayAliases();
  }

  function TItle() { return $this->title; }
  function Description() { return $this->description; }

  function Copyright() { return $this->copyright; }
  function Links() { return $this->links; }
  function Permalink($new = null) { return $this->Accessor('permalink', $new); }

  function Authors(array $new = null) {
    return $this->ClassArrayAccessor('FeedPerson', 'authors', $new);
  }

    function Author($index = 0, FeedPerson $new = null) {
      return $this->ArrayItemAccessor('authors', $index, $new);
    }

  function Contributors(array $new = null) {
    return $this->ClassArrayAccessor('FeedPerson', 'contributors', $new);
  }

    function Contributor($index = 0, FeedPerson $new = null) {
      return $this->ArrayItemAccessor('contributors', $index, $new);
    }

  function Categories(array $new = null) {
    return $this->ClassArrayAccessor('FeedCategory', 'categories', $new);
  }

    function Category($index = 0, FeedCategory $new = null) {
      return $this->ArrayItemAccessor('categories', $index, $new);
    }

  function Updated($new = null) { return $this->TimeAccessor('updated', $new); }
}

class FeedChannel extends FeedDescriptor {
  protected $logo, $icon;               // RSS:       image           | FeedImage
  protected $webMasters = array();      // Atom:      contributor     | FeedPerson
  protected $rating = '';               // Atom:      --              | str
  protected $pubDate = 0;               // Atom:      --              | int
  protected $skipHours = array();       // Atom:      --              | array of 0..23
  protected $skipDays = array();        // Atom:      --              | array of (Monday|...)
  protected $generator;                 // RSS 0.92:  --              | FeedGenerator
  protected $textInput;                 // Atom:      --              | FeedTextInput
  protected $ttl = 0;                   // Atom:      --              | int
  protected $cloud;                     // Atom:      --              | FeedCloud

  function __construct() {
    parent::__construct();

    $this->logo = new FeedImage;
    $this->icon = new FeedImage;

    $this->generator = new FeedGenerator;
    $this->generator->SetFromString($this->GeneratorString());

    $this->textInput = new FeedTextInput;
    $this->cloud = new FeedCloud;
  }

  function GeneratorString() {
    return sprintf('PHP Feeder %1.1f %s', self::Version, self::Homepage);
  }

  function SetFromArrayAliases() {
    return array('webmaster' => array('webMasters', 'FeedPerson'),
                 'pubdate' => 'pubDate',
                 'skiphours' => array($this, 'SetSkipHours'),
                 'skipdays' => array($this, 'SetSkipDays'),
                 'textinput' => 'textInput')
           + parent::SetFromArrayAliases();
  }

    function SetSkipHours($str) {
      foreach (explode(' ', $str) as $value) {
        $value = trim($value);
        if ($value !== '') {
          if (($value >= 1 and $value <= 23) or (is_numeric($value) and ((int) $value) === 0)) {
            if (!in_array($value, $this->skipHours)) {
              $this->skipHours[] = (int) $value;
            }
          } else {
            $this->Error("Invalid value for skipHours - expected a number in range".
                         " 0..23, [$value] given.");
          }
        }
      }
    }

    function SetSkipDays($str) {
      static $days = array('monday' => 1, 'tuesday' => 1, 'wednesday' => 1,
                           'thursday' => 1, 'friday' => 1, 'saturday' => 1, 'sunday' => 1);

      foreach (explode(' ', $str) as $value) {
        $value = strtolower(trim($value));
        if ($value !== '') {
          if (isset($days[$value])) {
            if (!in_array($value, $this->skipDays)) {
              $this->skipDays[] = ucfirst($value);
            }
          } else {
            $this->Error("Invalid value for skipDays - expected one from '".join("', '", $days).
                         "', [$value] given.");
          }
        }
      }
    }

  function Logo() { return $this->logo; }
  function Icon() { return $this->icon; }

  function WebMasters(array $new = null) {
    return $this->ClassArrayAccessor('FeedPerson', 'webMasters', $new);
  }

    function WebMaster($index = 0, FeedPerson $new = null) {
      return $this->ArrayItemAccessor('webMasters', $index, $new);
    }

  function Rating($new = null) { return $this->Accessor('rating', $new); }
  function PubDate($new = null) { return $this->TimeAccessor('pubDate', $new); }
  function SkipHours(array $new = null) { return $this->Accessor('skipHours', $new); }
  function SkipDays(array $new = null) { return $this->Accessor('skipDays', $new); }
  function Generator() { return $this->generator; }
  function TextInput() { return $this->textInput; }
  function TTL($new = null) { return $this->Accessor('ttl', $new); }
  function Cloud() { return $this->cloud; }
}

class FeedEntry extends FeedDescriptor {
  protected $published = 0;             // RSS 2.0: pubDate; 0.91: -- | int
  protected $commentsURL = '';          // RSS 0.91:  --; Atom: link rel=related
  protected $source;                    // RSS takes permalink, title | Feeder
  protected $content;                   // RSS:       --              | FeedContent

  function __construct(array $data = null) {
    parent::__construct();
    $this->content = new FeedContent;

    $data === null or $this->SetFromArray($data);
  }

    function SetFromArrayAliases() {
      return array('commentsurl' => 'commentsURL') + parent::SetFromArrayAliases();
    }

  function Published($new = null) { return $this->TimeAccessor('published', $new); }
  function CommentsURL($new = null) { return $this->Accessor('commentsURL', $new); }
  function Source(FeedContent $new = null) { return $this->Accessor('Source', $new); }
  function Content() { return $this->content; }
}

class FeedText extends FeedObjWithCommonAttrs {
  protected $byType = array();

  function Has($type) { return isset($this->byType[ strtolower($type) ]); }
  function Remove($type) { return $this->byType[$type] = null; }
  function Clear() { return $this->byType = array(); }

  function HasAny() {
    foreach ($this->byType as $type => &$data) {
      if (isset($data)) { return true; }
    }
  }

  function Get($type) {
    return isset($this->byType[$type]) ? $this->byType[$type] : null;
  }

  function Add($type, $data) {
    if ($this->Has($type)) { $this->Error("Duplicate FeedText type [$type]."); }
    $this->Set($type, $data);
  }

  function Set($type, $data) {
    $type = strtolower($type);
    if ($this->IsValidType($type)) {
      $this->byType[$type] = $data;
    } else {
      $this->Error("Wrong FeedText type [$type].");
    }
  }

  function IsValidType($type) {
    static $valid = array('text' => 1, 'html' => 1, 'xhtml' => 1);
    return isset($valid[strtolower($type)]);
  }

  function SetFromString($value, $type = 'text') {
    $this->Set($type, $value);
  }

  function All() {
    $result = array();
    foreach ($this->byType as $type => &$data) { $result[] = compact('type', 'data'); }
    return $result;
  }
}

  class FeedContent extends FeedText {
    function IsValidType($type) {
      return parent::IsValidType($type) or $this->IsMIME($type);
    }

    function Set($type, $data) {
      if ($data[0] === '@') { $data = "@$data"; }
      parent::Set($type, $data);
    }

    function SetSrc($type, $src) {
      parent::Set($type, '@'.ltrim($str, '@'));
    }

    // if $value starts with '@' then it's the 'src' attribute; otherwise, and if
    // it starts with '@@' it's the element's content.
    function SetFromString($value, $type = 'text') {
      parent::Set($type, $value);
    }

    function All() {
      $result = array();

      foreach ($this->byType as $type => $data) {
        $src = null;
        if ($data[0] === '@') {
          $data = substr($data, 1);
          if ($data[0] !== '@') {
            $src = $data;
            $data = null;
          }
        }

        $result[] = compact('type', 'data', 'src');
      }

      return $result;
    }
  }

class FeedLinks extends FeedObject {
  protected $byRel = array();    // array( 'rel' => array(FeedLink, ...), ... )

  function Has($rel) { return !empty($this->byRel[ strtolower($rel) ]); }
  function Set($rel, array $new) { return $this->byRel[$rel] = $new; }
  function Remove($rel) { return $this->Set($rel, array()); }
  function Clear() { return $this->byRel = array(); }

  function HasAny($rel) {
    foreach ($this->byRel as $rel => &$data) {
      if (!empty($data)) { return true; }
    }
  }

  function Get($rel) {
    return isset($this->byRel[$rel]) ? $this->byRel[$rel] : array();
  }

  function Add(FeedLink $link) {
    $this->byRel[$link->Rel()][] = $link;
  }

  // format ("Title" must be last, others - anywhere):  [text/html] ...page.html [12345] Title
  // MIME is set automatically based on URL extension if it's recognized.
  function SetFromString($value, $rel = 'alternate') {
    $link = new FeedLink($rel);

    foreach (explode(' ', $value) as $part) {
      if ($link->Title() === '') {
        if (is_numeric($part)) {
          $link->Length($part);
        } elseif ($this->IsMIME($part)) {
          $link->Type($part);
        } elseif ($link->URL() === '') {
          $link->URL($part);
        } else {
          $link->Title($part);
        }
      } else {
        $link->Title($link->Title()." $part");
      }
    }

    $link->Type() === '' and $link->SetMimeByExt();
    $this->Add($link);
  }

  function All() { return $this->byRel; }
}

class FeedLink extends FeedObjWithCommonAttrs {
  protected $href = '', $rel = '', $type = '', $hreflang = '', $title = '', $length = 0;

  function __construct($rel) {
    parent::__construct();
    $this->rel = strtolower($rel);
  }

  function URL($new = null) { return $this->Accessor('href', $new); }
  function Href($new = null) { return $this->Accessor('href', $new); }
  function Rel() { return $this->rel; }
  function Type($new = null) { return $this->Accessor('type', $new); }
  function HrefLang($new = null) { return $this->Accessor('hreflang', $new); }
  function Title($new = null) { return $this->Accessor('title', $new); }
  function Length($new = null) { return $this->Accessor('length', $new); }

  function SetMimeByExt() {
    $ext = self::ExtOf($this->URL());
    isset(self::$extToMIME[$ext]) and $this->Type(self::$extToMIME[$ext]);
  }
}

class FeedImage extends FeedObjWithCommonAttrs {
  protected $imageURL = '';             // Atom:      node content    | str
  protected $linkURL = '';              // Atom:      --              | str
  protected $title;                     // Atom:      --              | FeedText
  protected $description;               // Atom:      --              | FeedText
  protected $width = 0;                 // Atom:      --              | int
  protected $height = 0;                // Atom:      --              | int

  function __construct() {
    parent::__construct();

    $this->title = new FeedText;
    $this->description = new FeedText;
  }

  function ImageURL($new = null) { return $this->Accessor('imageURL', $new); }
  function LinkURL($new = null) { return $this->Accessor('linkURL', $new); }
  function Title() { return $this->title; }
  function Description() { return $this->description; }
  function Width($new = null) { return $this->Accessor('width', $new); }
  function Height($new = null) { return $this->Accessor('height', $new); }

  // format:  [WxH] ....pic.gif
  // WxH can be of form (WxH|Wx|W|H|xH), where 'x' can be [xX*] or a space.
  // $type can be title, description or link (for link URL).
  function SetFromString($value, $type = null) {
    static $operators = array('x' => 1, 'X' => 1, '*' => 1);

    switch (strtolower($type)) {
    case 'title':       $this->title->Set('text', $value); break;
    case 'description': $this->description->Set('text', $value); break;
    case 'link':        $this->LinkURL($value);

    default:
      foreach (explode(' ', $value) as $part) {
        if ($this->ImageURL() === '') {
          if (is_numeric($part)) {
            $this->width > 0 ? $this->Width($part) : $this->Height($part);
          } elseif (isset($operators[ trim($part, '0..9') ])) {
            @list($width, $height) = explode(trim($part, '0..9'), $part);

            isset($width) and $this->Width($width);
            isset($height) and $this->Height($height);
          } else {
            $this->ImageURL($part);
          }
        } else {
          $this->ImageURL($this->ImageURL().' '.$part);
        }
      }
    }
  }
}

class FeedPerson extends FeedObjWithCommonAttrs {
  // in RSS this is converted to form 'Name (' + e-mail or url if not given + ')'.
  // if name is not given e-mail is returned, if it's also omitted - the URL.
  protected $name = '', $url = '', $email = '';

  function Name($new = null) { return $this->Accessor('name', $new); }
  function URL($new = null) { return $this->Accessor('url', $new); }
  function URI($new = null) { return $this->Accessor('url', $new); }
  function EMail($new = null) { return $this->Accessor('email', $new); }

  // format:  Name e@mail.com www.homepage.ru
  // if e-mail part is omitted then the value is considered Name unless last part
  // of it (space-separated), or entire string if there are no spaces, contains ":"
  // - in this case it's a homepage.
  // if e-mail part is given homepage (if any) gets 'http://' prefixed if it contains
  // neither ':' nor 'www.'.
  function SetFromString($value) {
    if (strpos($value, '@')) {
      $naming = true;
      foreach (explode(' ', $value) as $part) {
        if ($naming) {
          if (strpos($part, '@')) {
            $naming = false;
            $this->EMail($part);
          } else {
            $this->name === '' or $part = " $part";
            $this->name .= $part;
          }
        } else {
          $this->url === '' or $part = " {$this->url}";
          $this->url .= $part;
        }
      }
    } else {
      $last = substr($value, (int) strrpos($value, ' '));
      if (strpos($last, ':') or substr($last, 0, 4) === 'www.') {
        $this->URL( trim($last) );
        $value = substr($value, 0, -1 * strlen($last));
      }

      $this->Name($value);
    }

    if ($this->url !== '' and strpos($this->url, ':') === false) {
      $this->URL('http://'.$this->url);
    }
  }
}

class FeedCategory extends FeedObjWithCommonAttrs {
  protected $domain = '';               // Atom:      scheme          | str
  protected $term = '';                 // RSS:       node content    | str
  protected $label = '';                // RSS:       --              | str

  function Domain($new = null) { return $this->Accessor('domain', $new); }
  function Scheme($new = null) { return $this->Accessor('domain', $new); }
  function URL($new = null) { return $this->Accessor('domain', $new); }
  function Term($new = null) { return $this->Accessor('term', $new); }
  function Label($new = null) { return $this->Accessor('label', $new); }

  // format:  [http://|www.]domain Name [Label with spaces]
  function SetFromString($value) {
    $labelling = false;
    foreach (explode(' ', $value) as $part) {
      if ($labelling) {
        $this->label === '' or $this->label .= ' ';
        $this->label .= $part;
      } elseif ((strpos($part, ':') or substr($part, 0, 4) === 'www.') and $this->domain === '') {
        $this->Domain($part);
      } else {
        $this->Term($part);
        $labelling = true;
      }
    }

    if ($this->domain !== '' and strpos($this->domain, ':') === false) {
      $this->Domain('http://'.$this->domain);
    }
  }
}

class FeedGenerator extends FeedObjWithCommonAttrs {
  // in RSS node content is set to whichever property is set first.
  protected $url = '';                  // Atom:      uri             | str
  protected $name = '';
  protected $version = '';

  function URL($new = null) { return $this->Accessor('url', $new); }
  function URI($new = null) { return $this->Accessor('url', $new); }
  function Name($new = null) { return $this->Accessor('name', $new); }
  function Version($new = null) { return $this->Accessor('version', $new); }

  // format (order of parts doesn't matter):  [http://|www.]homepage Name [v]1[.0]
  function SetFromString($value) {
    $verbatimLastVersion = '';
    foreach (explode(' ', $value) as $part) {
      if ((strpos($part, ':') or substr($part, 0, 4) === 'www.') and $this->url === '') {
        $this->URL($part);
      } elseif ((is_numeric($part) or trim(ltrim($part, 'v'), '0..9') === '.') and $this->version === '') {
        $verbatimLastVersion = $part;
        $this->Version(ltrim($part, 'v'));
      } else {
        if ($this->version !== '') {
          $part = $verbatimLastVersion.' ';
          $this->version = '';
        }

        $this->name === '' or $part = " $part";
        $this->name .= $part;
      }
    }

    if ($this->url !== '' and strpos($this->url, ':') === false) {
      $this->URL('http://'.$this->url);
    }
  }
}

class FeedTextInput extends FeedObject {
  protected $title = '', $description = '', $name = '', $url = '';

  function Title($new = null) { return $this->Accessor('title', $new); }
  function Caption($new = null) { return $this->Accessor('title', $new); }
  function Description($new = null) { return $this->Accessor('description', $new); }
  function Name($new = null) { return $this->Accessor('name', $new); }
  function URL($new = null) { return $this->Accessor('url', $new); }
  function Link($new = null) { return $this->Accessor('url', $new); }

  // format:  [http://www.]url name Caption with spaces
  // $type can be caption, title (the same) or description.
  function SetFromString($value, $type = null) {
    switch (strtolower($type)) {
    case 'title':
    case 'caption':     $this->Title($value); break;
    case 'description': $this->Description($value); break;

    default:
      foreach (explode(' ', $value) as $part) {
        if ((strpos($part, ':') or substr($part, 0, 4) === 'www.') and $this->url === '') {
          $this->URL($part);
        } elseif ($this->name === '') {
          $this->Name($part);
        } else {
          $this->title === '' or $part = " $part";
          $this->title .= $part;
        }
      }

      if ($this->url !== '' and strpos($this->url, ':') === false) {
        $this->URL('http://'.$this->url);
      }
    }
  }
}

class FeedCloud extends FeedObject {
  protected $protocol = '', $domain = '', $port = 0, $path = '', $procedure = '';

  function Protocol($new = null) { return $this->Accessor('protocol', $new); }
  function Domain($new = null) { return $this->Accessor('domain', $new); }
  function URL($new = null) { return $this->Accessor('domain', $new); }
  function Port($new = null) { return $this->Accessor('port', $new); }
  function Path($new = null) { return $this->Accessor('path', $new); }
  function Procedure($new = null) { return $this->Accessor('procedure', $new); }
  function RegisterProcedure($new = null) { return $this->Accessor('procedure', $new); }

  // format:  domain.ru [/]path http-post [80] procedure
  function SetFromString($value) {
    foreach (explode(' ', $value) as $part) {
      if (is_numeric($part) and $this->port === 0) {
        $this->Port($part);
      } else {
        if ($this->domain === '') {
          $this->Domain($part);
        } elseif ($this->path === '') {
          $this->Path( '/'.ltrim($part, '\\/') );
        } elseif ($this->protocol === '') {
          $this->Protocol($part);
        } else {
          $this->procedure === '' or $part = " $part";
          $this->procedure .= $part;
        }
      }
    }

    $this->port === 0 and $this->Port(80);
  }
}

/************************************************************************
 * Feeders - representing a complete channel with its entries
 ************************************************************************/

class Feeder extends FeedObject {
  protected $channel, $entries;

  function __construct() {
    parent::__construct();
    $this->Clear();
  }

    function Clear() {
      $this->channel = new FeedChannel;
      $this->entries = array();
    }

  function Channel() { return $this->channel; }
  function Info() { return $this->channel; }

  function Entries(array $new = null) {
    return $this->ClassArrayAccessor('FeedEntry', 'entries', $new);
  }

    function Entry($index = 0, FeedEntry $new = null) {
      return $this->ArrayItemAccessor('entries', $index, $new);
    }

    function Add(array $data = null) {
      $entry = new FeedEntry($data);
      $this->entries[] = $entry;
      return $entry;
    }

  function Normalize() {
    $this->NormalizeChannel();
    $this->NormalizeEntries();
  }

  function NormalizeChannel() {
    if (!$this->channel->Updated()) {
      $lastUpdate = 0;

        foreach ($this->entries as $entry) {
          $entry->Updated()   > $lastUpdate and $lastUpdate = $entry->Updated();
          $entry->Published() > $lastUpdate and $lastUpdate = $entry->Published();
        }

      $lastUpdate and $this->channel->Updated($lastUpdate);
    }

    $links = $this->channel->Links()->Get('alternate');

      foreach ($links as $key => $link) {
        if ($link->URL() === $this->channel->Permalink()) {
          unset($links[$key]);
        }
      }

    $this->channel->Links()->Set('alternate', $links);
  }

  function NormalizeEntries() {
    foreach ($this->entries as $entry) {
      $this->NormalizeEntry($entry);
    }
  }

    function NormalizeEntry(FeedEntry $entry) {
      $entry->Updated() or $entry->Updated($entry->Published());
    }
}

class TextFeeder extends Feeder {
  public $alternate;

  protected $modRewrite = null;   // null (autodetect), true or false.
  protected $path = '', $dataURL = '';
  protected $yamlExt = 'yml';
  protected $entriesPerFeed = 15;

  function __construct($path, $dataURL = null) {
    parent::__construct();

    $this->alternate = array_keys(FeedOut::$formats);

    $this->DataURL($dataURL);
    $this->SetPath($path);
  }

    protected function SetPath($path) {
      $this->path = strtr(rtrim($path, '\\/'), '\\', '/').'/';
      $this->Load();
    }

    function Path() { return $this->path; }

  function DataURL($new = null) {
    $new === null or $new = rtrim($new, '\\/').'/';
    return $this->Accessor('dataURL', $new);
  }

  function EntriesPerFeed($new = null) {
    return $this->Accessor('entriesPerFeed', $new);
  }

  protected function Load() {
    $chanFile = $this->FindYAML('feed');
    is_file($chanFile) or $chanFile = $this->FindYAML('channel');

    if (!is_file($chanFile)) {
      throw new ENotATextFeedPath($this->path, '.'.$this->yamlExt, $this);
    }

    $this->chanFile = $chanFile;

    $channel = $this->LoadYAML($chanFile, false);
    if (count($channel) > 1) {
      $entries = array_splice($channel, 1);
    } else {
      $entries = array();

      foreach ($this->FindRecentEntries() as $info) {
        $entry = $this->LoadYAML($info['file'], true);
        $this->AutoAttachToEntry($entry, $info);

        $entry['_info'] = $info;
        $entries[] = $entry;
      }
    }

    $channel = $channel[0];
    $this->AutoAttachToChannel($chanFile, $channel);

      if (isset( $channel['modrewrite'] )) {
        $this->modRewrite = (bool) $channel['modRewrite'];
      }

      if (isset( $channel['data url'] )) {
        $this->DataURL($channel['data url']);
      }

    $this->LoadFromArrays($channel, $entries);
  }

    protected function AutoAttachToChannel($file, array &$channel) {
      $base = basename($file, '.'.$this->yamlExt);

      foreach (glob("$base.*", GLOB_NOSORT) as $file) {
        $ext = self::ExtOf($file);

        if ($ext !== $this->yamlExt) {
          if (isset(self::$imageExt[$ext])) {
            if (empty($channel['logo'])) {
              $channel['logo'] = $this->EncodeIRI( $this->dataURL.basename($file) );
            }
          } elseif ($ext === 'txt') {
            if (empty($channel['description'])) {
              $channel['description'] = file_get_contents("$base.txt");
            }
          } elseif ($ext === 'html' or $ext === 'htm') {
            if (empty($channel['description html'])) {
              $channel['description html'] = file_get_contents("$base.$ext");
            }
          }
        }
      }

      $icons = glob("$base icon.{".join(',', array_keys(self::$imageExt)).'}', GLOB_BRACE);
      if (empty($channel['icon']) and $icons) {
        $channel['icon'] = $this->EncodeIRI( $this->dataURL.basename($icons[0]) );
      }
    }

    protected function AutoAttachToEntry(array &$entry, array $info) {
      $base = basename($info['file'], $info['ext']);

      foreach (glob("$base.*", GLOB_NOSORT) as $file) {
        $ext = self::ExtOf($file);

        if (".$ext" !== $info['ext']) {
          if ($ext === 'txt') {
            if (empty($entry['content'])) {
              $entry['content'] = file_get_contents("$base.txt");
            }
          } elseif ($ext === 'html' or $ext === 'htm') {
            if (empty($entry['content html'])) {
              $entry['content html'] = file_get_contents("$base.$ext");
            }
          } else {
            $addr = $this->EncodeIRI($this->dataURL.basename($file));

            $enclosures = &$entry['link enclosure'];
            $enclosures = isset($enclosures) ? ((array) $enclosures) : array();
            $enclosures[] = filesize($file).' '.$addr;
          }
        }
      }
    }

    protected function LoadFromArrays(array $channel, array $entries) {
      $this->Clear();
      $this->SortEntriesByTimeDesc($entries);

      $this->channel->SetFromArray($channel);

      foreach ($entries as $i => &$entry) {
        $obj = new FeedEntry;
        $obj->TextInfo = $entry['_info'];

        unset($entry['_info']);
        $obj->SetFromArray($entry);

        $this->entries[] = $obj;

        if ($i >= $this->entriesPerFeed) { break; }
      }

      $this->Normalize();
    }

      function NormalizeChannel() {
        $this->channel->Updated() or $this->channel->Updated(filemtime($this->chanFile));

        if (!$this->channel->Permalink()) {
          $links = $this->channel->Links()->Get('self');
          $links and $this->channel->Permalink($links[0]->URL());
        }

        foreach ($this->alternate as $format) {
          $link = new FeedLink('alternate');

          $link->Type(FeedOut::MimeOf($format));
          $link->URL( $this->UrlOf('format=', $this->EncodeIRI(strtolower($format))) );
          $link->Title($format);

          $this->channel->Links()->Add($link);
        }

        parent::NormalizeChannel();
      }

      function NormalizeEntry(FeedEntry $entry) {
        $entry->Published() or $entry->Published($entry->TextInfo['time']);
        $entry->Title()->HasAny() or $entry->Title()->Add('text', $entry->TextInfo['title']);
        $entry->Permalink() or $entry->Permalink( $this->UrlOfEntry($entry) );

        parent::NormalizeEntry($entry);
      }

  function UrlOfEntry(FeedEntry $entry) {
    $name = basename($entry->TextInfo['file'], '.'.$this->yamlExt);
    $query = $this->EncodeIRI($name);

    return $this->UrlOf('', $query);
  }

    function UrlOf($queryPf, $querySf) {
      if ($this->modRewrite === null) {
        $htaFile = $this->Path().'.htaccess';
        $regexp = '/^\s*RewriteEngine on\b/im';
        $this->modRewrite = (is_file($htaFile) and
                             preg_match($regexp, file_get_contents($htaFile)));
      }

      $base = $this->modRewrite ? '' : basename(__FILE__).'?'.$queryPf;
      return $this->DataURL().$base.$querySf;
    }

  function FindYAML($name) {
    if (strpbrk($name, '\\/') === false) {
      return $this->path."$name.".$this->yamlExt;
    }
  }

    function FindEntries() {
      $entries = array();

        $files = glob($this->FindYAML('*'), GLOB_NOSORT);
        foreach ($files as &$file) {
          $name = basename($file);
          if (substr($name, 0, 8) > 0) {
            $year = substr($name, 0, 4);
            $month = substr($name, 4, 2);
            $day = substr($name, 6, 2);

            $time = mktime(0, 0, 0, $month, $day, $year);
            if ($time) {
              $ext = '.'.$this->yamlExt;
              $title = trim(substr(basename($name, $ext), 8));
              $entries[] = compact('day', 'month', 'year', 'time', 'title', 'file', 'ext');
            }
          }
        }

      return $entries;
    }

    function FindRecentEntries() {
      $files = $this->FindEntries();
      $this->SortEntriesByTimeDesc($files);

      return array_slice($files, 0, $this->entriesPerFeed);
    }

    function SortEntriesByTimeDesc(array &$entries) {
      usort($entries, array($this, 'ByTimeEntrySorter'));
    }

      function ByTimeEntrySorter($a, $b) {
        $a = isset($a['_info']) ? $a['_info']['time'] : $a['time'];
        $b = isset($b['_info']) ? $b['_info']['time'] : $b['time'];
        return -1 * ($a > $b ? +1 : ($a < $b ? -1 : 0));
      }

    function UseYamlExtension($enable = true) {
      $this->yamlExt = $enable ? 'yml' : 'txt';
    }

    // Feeder's YAML files are alike to standard YAML but still differ:
    // * keys with the same name don't override previous value but create an array instead;
    // * of block operators only folding (>) and preformatted (|) are supported;
    // * lists and most other markup isn't supported;
    // * comments are only allowed if they're prefixed with a whitespace
    //   (i.e. not inside values).
    function LoadYAML($file, $singleDoc, $lowerCaseKeys = true) {
      if (!is_file($file)) { $this->Error("YAML file to load [$file] doesn't exist."); }

      $docs = array(array());
      $doc = &$docs[0];

      $folding = $preserving = false;
      $indent = 0;

      foreach (file($file, FILE_IGNORE_NEW_LINES) as $line) {
        $line = rtrim($line);

        if (substr(ltrim($line), 0, 1) === '#') {
          // do nothing, continue.
        } elseif ($line === '...') {
          break;
        } elseif ($line === '---') {
          if (isset($docs[1]) or !empty($doc)) {
            if ($singleDoc) {
              break;
            } else {
              $docs[] = array();
              $doc = $docs[count($docs) - 1];
            }
          }
        } else {
          $key = null;
          $append = false;

            if ($folding !== false or $preserving !== false) {
              if (ltrim( substr($line, 0, $indent), ' ' ) !== '') {
                $folding = $preserving = false;
              } else {
                $append = true;
                if ($folding !== false) {
                  $key = $folding;
                  $value = trim($line);

                  if ($value === '') {
                    $value = "\n";
                  } elseif ($doc[$key] !== '') {
                    $value = " $value";
                  }
                } else {
                  $key = $preserving;

                  $value = substr($line, $indent);
                  $doc[$key] === '' or $value = "\n$value";
                }
              }
            }

            if ($line !== '' and !$folding and !$preserving) {
              $line = trim($line);
              @list($key, $value) = explode(': ', $line, 2);

              if (!isset($value) and substr($line, -1) === ':') {
                $key = $line;
                $value = '';
              }

              $lowerCaseKeys and $key = strtolower($key);

              if (!isset($value)) {
                $this->Error("Cannot parse [$file] - line [$line] is expected to be".
                             " a 'key: value' pair.");
              }

              $value = trim($value);

              if ($folding === false and $preserving === false) {
                if ($value === '>') {
                  $folding = $key;
                } elseif ($value === '|') {
                  $preserving = $key;
                }

                if ($folding !== false or $preserving !== false) {
                  $indent = strlen($line) - strlen(ltrim($line)) + 2;
                  $value = '';
                }
              }
            }

          if (isset($key)) {
            if (isset($doc[$key])) {
              if ($append) {
                if (is_array($doc[$key])) {
                  $str = &$doc[$key][count($doc[$key]) - 1];
                } else {
                  $str = &$doc[$key];
                }

                $str .= $value;
              } else {
                is_array($doc[$key]) or $doc[$key] = array($doc[$key]);
                $doc[$key][] = $value;
              }
            } else {
              $doc[$key] = $value;
            }
          }
        }
      }

      return $singleDoc ? $docs[0] : $docs;
    }
}

/************************************************************************
 * Feed output classes
 ************************************************************************/

abstract class FeedOut extends FeedObject {
  static $formats = array('atom' => 'Atom', 'rss20' => 'RSS 2.0', 'rss092' => 'RSS 0.92');

  public $charset = 'utf-8';
  public $mime;
  public $useGZ = true;

  // if non-zero will generate missing Description for entries with defined content
  // having given number of words.
  public $generateSummaries = 150;

  protected $feed;    // a Feeder object.

  static function Factory($format) {
    $class = ucfirst(strtolower($format)).'Feed';

    if (!class_exists($class)) {
      throw new EFeed("Cannot create a Feed object for unknown format [$format].");
    }

    return new $class;
  }

  static function MimeOf($format) {
    return self::Factory($format)->mime;
  }

  function Output(Feeder $feed) {
    $output = $this->Build($feed);
    $output = $this->ConvertCharset($output, $this->charset);

    if ($this->mime) {
      header("Content-Type: {$this->mime}; charset=".$this->charset);
    }

    header('Etag: '.md5($output));
    header('Last-Modified: '.gmdate('D, d M Y H:i:s', $feed->Channel()->Updated()).' GMT');

    if ($ttl = $this->feed->Channel()->TTL()) {
      $ttl *= 60;
      header("Cache-Control: maxage=$ttl");
      header('Expires: '.gmdate('D, d M Y H:i:s', time() + $ttl).' GMT');
    }

    if ($this->useGZ and !ob_get_level() and !ini_get('zlib.output_compression')) {
      ob_start('ob_gzhandler');
    }

    echo $output;
  }

    function ConvertCharset($str, $charset) {
      $charset = strtolower($charset);

      if ($charset === 'iso-8859-1') {
        $str = utf8_decode($str);
      } elseif ($charset !== 'utf-8' and $charset !== 'utf8') {
        if (function_exists('iconv')) {
          $str = iconv('utf-8', $charset, $str);
        } else {
          $this->Error('Iconv PHP module is necessary for feed charset conversion to work.');
        }
      }

      if (is_string($str)) {
        return $str;
      } else {
        $this->Error("Cannot convert feed charset from UTF-8 to $charset.");
      }
    }

  function Build(Feeder $feed) {
    $this->feed = $feed;

    $this->StartBuilding();
    return $this->DoBuild();
  }

    protected function StartBuilding() {
      if ($length = $this->generateSummaries) {
        foreach ($this->feed->Entries() as $entry) {
          $this->SummariesTo($entry->Description(), $entry->Content(), $length);
        }
      }
    }

    protected abstract function DoBuild();

   function SummariesTo(FeedText $desc, FeedContent $content, $length = 150) {
    if (!$desc->HasAny()) {
      foreach ($content->All() as $data) {
        if ("$data[data]" !== '' and $desc->IsValidType($data['type'])) {
          if (in_array( $data['type'], array('html', 'xhtml') )) {
            $func = 'HtmlSummaryFrom';
          } else {
            $func = 'TextSummaryFrom';
          }

          $desc->Add($data['type'], $this->$func($data['data'], $length));
        }
      }
    }
  }
}

abstract class XmlFeedOut extends FeedOut {
  protected $xmlVersion = 1.0;
  protected $xmlDeclAttrs = array('version' => 'xmlVersion', 'encoding' => 'charset');

  public $feedTag, $feedAttrs = array(), $feedTags = array();
  public $extraFeedTag, $extraFeedTagAttrs = array();
  public $entryTag, $entryAttrs = array(), $entryTags = array();

  function DoBuild() {
    $result = '<?xml'.$this->XmlAttrs($this->xmlDeclAttrs)."?>\n".
              '<'.$this->feedTag.$this->XmlAttrs($this->feedAttrs, $this->feed->Channel()).">\n";

    $indent = 1;

    if ($extraTag = $this->extraFeedTag) {
      $attrStr = $this->XmlAttrs($this->extraFeedTagAttrs, $this->feed->Channel());
      $result .= $this->Indent($indent)."<$extraTag$attrStr>\n";
      ++$indent;
    }

    $result .= $this->XmlTags($this->feedTags, $indent, $this->feed->Channel());
    foreach ($this->feed->Entries() as $entry) {
      $result .= "\n".$this->FullXmlTag($this->entryTag, $this->entryAttrs, $this->entryTags, $indent, $entry);
    }

    if ($extraTag) {
      $result .= $this->Indent($indent - 1)."</$extraTag>\n";
    }

    $result .= '</'.$this->feedTag.'>';

    return $result;
  }

    abstract function WriteObject(FeedObject $obj, $indent, $tag);

  function UnsupportedObjectToWrite($obj, $tag) {
    $class = get_class($obj);
    $tag = $tag ? " as tag [$tag]" : '';
    $this->Error("Do not know how to write object of class $class$tag.");
  }

  // $values = object, array or null (= $this).
  function XmlAttrs(array $list, $values = null) {
    $values === null and $values = $this;
    $result = '';

      foreach ($list as $attr => $prop) {
        $method = null;
        $urlencode = false;

          if (is_array($prop)) {
            $temp = array_shift($prop);
            $method = $prop;
            $prop = $temp;
          }

          if ($prop and $prop[0] === '%') {
            $urlencode = true;
            $prop = substr($prop, 1);
          }

        $attr = is_int($attr) ? $prop : trim($attr);
        $value = $this->ValueFrom($values, $prop);

        if (isset($method)) {
          $args = array($value, $attr);
          is_object($method[0]) or array_unshift($method, $this);
          $result .= ' '.self::Call($method, $args);
        } elseif ("$value" !== '' and $value !== 0) {
          if (is_float($value)) {
            $value = sprintf('%1.1f', $value);
          } elseif (!is_scalar($value)) {
            $class = is_object($values) ? get_class($values) : 'Array';
            $valClass = get_class($value);
            $this->Error("Cannot write XML attribute because $class->$prop is a $valClass object.");
          }

          $urlencode and $value = $this->EncodeIRI($value);
          $result .= " $attr=\"{$this->Quote($value)}\"";
        }
      }

    return $result;
  }

    function ValueFrom($values, $prop) {
      if (is_object($values)) {
        if (isset($values->$prop)) {
          return $values->$prop;
        } else {
          $accessor = ucfirst($prop);
          if (method_exists($values, $accessor)) {
            return $values->$accessor();
          }
        }
      } elseif (isset($values[$prop])) {
        return $values[$prop];
      }
    }

  // $list format: array( ['tagName' =>] <reader>, ... ), where <reader> is:
  // * 'propName';
  // * array('propName') - write the first item of array-property 'propName';
  // * array('propName', 'methodName') - call self's method with value of 'propName';
  // * array('propName', $object, 'methodName') - the same as above but calls
  //   method of $object rather than $this.
  // If 'tagName' is omitted it's set to 'propName' specified by any of the above methods.
  // If object form (with 'methodName') is not used and 'tagName' is prefixed
  //   with '%' then the value is written using EncodeIRI().
  // $values = object, array or null (= $this).
  function XmlTags(array $list, $indentLevel, $values = null) {
    $values === null and $values = $this;
    $result = '';

      $indent = $this->Indent($indentLevel);
      foreach ($list as $tag => $prop) {
        $method = null;
        $pickFirstItem = $urlencode = false;

          if (is_array($prop)) {
            $temp = array_shift($prop);
            $pickFirstItem = empty($prop);
            $pickFirstItem or $method = $prop;
            $prop = $temp;
          }

          if ($prop and $prop[0] === '%') {
            $urlencode = true;
            $prop = substr($prop, 1);
          }

        $tag = is_int($tag) ? $prop : trim($tag);
        $value = $this->ValueFrom($values, $prop);

        if (isset($method)) {
          isset($value) or $value = $values;
          $args = array($value, $indentLevel, $tag);
          is_object($method[0]) or array_unshift($method, $this);
          $result .= self::Call($method, $args);
        } elseif (is_object($value)) {
          $result .= $this->WriteObject($value, $indentLevel, $tag);
        } elseif (is_array($value)) {
          foreach ($value as $item) {
            if (is_object($item)) {
              $result .= $this->WriteObject($item, $indentLevel, $tag);
            } else {
              $this->Error("Expected an object item while writing XML [$tag] tag array.");
            }

            if ($pickFirstItem) { break; }
          }
        } elseif ("$value" !== '' and $value !== 0) {
          $urlencode and $value = $this->EncodeIRI($value);
          $value = $this->Quote($value, ENT_NOQUOTES);
          $result .= "$indent<$tag>$value</$tag>\n";
        }
      }

    return $result;
  }

  function XmlTagNoClose($tag, array $attrs, array $children, $indent, $object = null) {
    $children = trim($this->XmlTags($children, $indent + 1, $object), "\r\n");
    if ($children !== '') {
      return $this->Indent($indent).'<'.$tag.$this->XmlAttrs($attrs, $object).">\n$children\n";
    }
  }

  function FullXmlTag($tag, array $attrs, array $children, $indent, $object = null) {
    $xml = $this->XmlTagNoClose($tag, $attrs, $children, $indent, $object);
    if ("$xml" !== '') {
      return $xml.$this->Indent($indent)."</$tag>\n";
    }
  }
}

class AtomFeed extends XmlFeedOut {
  public $mime = 'application/atom+xml';

  public $feedTag = 'feed';
  public $feedAttrs = array(array('lang', 'WriteLanguage'), 'xml:base' => '%baseURL',
                            array('xmlns', 'FeedNamespace'));
  public $feedTags = array(
    'title', 'subtitle' => 'description', 'link' => 'links',
    'id' => '%permalink', 'icon', 'logo',
    'rights' => 'copyright', 'author' => 'authors', 'contributor' => 'contributors',
    ' contributor' => 'webMasters',
    array('updated', 'WriteTime'), 'generator', 'category' => 'categories');

  public $entryTag = 'entry';
  public $entryAttrs = array('xml:lang' => array('lang', 'WriteLanguage'), 'xml:base' => '%baseURL');
  public $entryTags = array(
    'title', 'summary' => 'description', 'link' => 'links', 'id' => '%permalink',
    'author' => 'authors',
    'category' => 'categories', array('published', 'WriteTime'), array('updated', 'WriteTime'),
    'source', 'content', 'rights' => 'copyright', 'contributor' => 'contributors',
    array('', 'WriteStdEntryLinks'));

  function WriteObject(FeedObject $obj, $indentLevel, $tag) {
    $indent = $this->Indent($indentLevel);
    $moreIndent = $this->Indent($indentLevel + 1);

    $result = '';

    if ($obj instanceof FeedText) {
      $attrs = array('type', '%src');

      $texts = array();
      foreach ($obj->All() as $item) { $texts[$item['type']] = $item; }
      usort($texts, array($this, 'TextsSorter'));

      if ($texts) {
        $text = $texts[0];

        if (isset($text['src'])) {
          $result .= "$indent<$tag".$this->XmlAttrs($attrs, $text)." />\n";
        } else {
          $result .= "$indent<$tag".$this->XmlAttrs($attrs, $text).">\n";

          $data = $text['data'];
          if ($text['type'] === 'xhtml') {
            $data = "<div xmlns=\"http://www.w3.org/1999/xhtml\">\n".
                    $this->Indent($indentLevel + 2).$data.
                    "$moreIndent</div>\n";
          } else {
            $data = $this->Quote($data, ENT_NOQUOTES);
          }

          $result .= "$moreIndent$data\n$indent</$tag>\n";
        }
      }
    } elseif ($obj instanceof FeedLinks) {
      $attrs = array('rel', 'type', '%href', 'title', 'hreflang', 'length');

      foreach ($obj->All() as $rel => $links) {
        if ($links) {
          foreach ($links as $link) {
            if ($link) {
              $result .= "$indent<$tag".$this->XmlAttrs($attrs, $link)." />\n";
            }
          }
        }
      }
    } elseif ($obj instanceof FeedImage) {
      if ($obj->ImageURL()) {
        $url = $this->EncodeIRI($obj->ImageURL());
        $result .= "$indent<$tag>".$this->Quote($url, ENT_NOQUOTES)."</$tag>\n";
      }
    } elseif ($obj instanceof FeedPerson) {
      $tags = array('name', 'uri' => '%url', 'email');
      $result .= $this->FullXmlTag($tag, array(), $tags, $indentLevel, $obj);
    } elseif ($obj instanceof FeedGenerator) {
      $attrs = array('uri' => '%url', 'version');

      $attrStr = $this->XmlAttrs($attrs, $obj);
      if ($attrStr !== '') {
        $result .= "$indent<$tag$attrStr>{$obj->Name()}</$tag>\n";
      }
    } elseif ($obj instanceof FeedCategory) {
      $attrs = array('scheme' => '%domain', 'term', 'label');

      $attrStr = $this->XmlAttrs($attrs, $obj);
      $attrStr === '' or $result = "$indent<$tag$attrStr />\n";
    } elseif ($obj instanceof Feeder) {
      $this->Error("Related feed writing isn't implemented.");
    } else {
      return $this->UnsupportedObjectToWrite($obj, $tag);
    }

    return $result;
  }

    function TextsSorter($a, $b) {
      static $order = array('xhtml' => 1, 'html' => 2, 'text' => 3);

      $i1 = isset($order[$a['type']]) ? $order[$a['type']] : 9;
      $i2 = isset($order[$b['type']]) ? $order[$b['type']] : 9;
      return $i1 > $i2 ? +1 : ($i1 < $i2 ? -1 : 0);
    }

  function WriteTime($time, $indent, $tag) {
    if ($time > 0) {
      return $this->Indent($indent)."<$tag>".date(DATE_ATOM, $time)."</$tag>\n";
    }
  }

  function WriteLanguage($lang) {
    if ($lang) { return 'xml:lang="'.strtr($lang, '_', '-').'"'; }
  }

  function WriteStdEntryLinks(FeedEntry $entry, $indent) {
    $indent = $this->Indent($indent);
    $result = '';

      $href = $this->Quote($entry->Permalink());
      if ($href) {
        $result .= $indent.'<link rel="alternate" type="text/html" href="'.$href."\" />\n";
      }

      $href = $this->Quote($entry->CommentsURL());
      if ($href) {
        $result .= $indent.'<link rel="related" type="text/html" title="Comments" href="'.$href."\" />\n";
      }

    return $result;
  }

  function FeedNamespace() { return 'xmlns="http://www.w3.org/2005/Atom"'; }
}

class Rss092Feed extends XmlFeedOut {
  public $mime = 'text/xml';

  public $feedTag = 'rss';
  public $feedAttrs = array(array('version', 'WriteRssVersion'));
  public $feedTags = array(
    'generator' => array('generator', 'WriteGeneratorComment'), array('docs', 'WriteDocs'),
    'title', 'description',
    'link' => array('permalink'), 'language' => array('lang', 'WriteLanguage'),
    array('image', 'WriteImage'), 'copyright', 'managingEditor' => array('authors'),
    'webMaster' => array('webMasters'), 'rating', 'lastBuildDate' => array('updated', 'WriteTime'),
    array('pubDate', 'WriteTime'), array('skipDays', 'WriteSkipDays'), array('skipHours', 'WriteSkipHours'),
    'textInput', 'cloud', 'link' => array('links', 'WriteStdFeedLinks'));

  public $extraFeedTag = 'channel';

  public $entryTag = 'item';
  public $entryTags = array(
    'title', 'description', 'link' => array('permalink'), 'category' => 'categories',
    'enclosure' => array('links', 'WriteEnclosure'), 'source');

  protected function StartBuilding() {
    parent::StartBuilding();
    $this->ExpandURLs();
  }

    function ExpandURLs() {
      $chan = $this->feed->Channel();

        $this->ExpandCommonUrlsOf($chan, array($chan));

        $this->ExpandUrlOf($chan->Logo(), $chan, 'ImageURL');
        $this->ExpandUrlOf($chan->Logo(), $chan, 'LinkURL');
        $this->ExpandUrlOf($chan->Icon(), $chan, 'ImageURL');
        $this->ExpandUrlOf($chan->Icon(), $chan, 'LinkURL');

        $this->ExpandUrlOf($chan->WebMasters(), $chan);
        $this->ExpandUrlOf($chan->Generator(), $chan);
        $this->ExpandUrlOf($chan->TextInput(), $chan);

      $baseURLs = array(null, $chan);
      foreach ($this->feed->Entries() as $entry) {
        $baseURLs[0] = $entry;
        $this->ExpandCommonUrlsOf($entry, $baseURLs);

        $this->ExpandUrlOf($entry, $chan, 'CommentsURL');
        $this->ExpandUrlOf($entry->Contributors(), $baseURLs);
      }
    }

      function ExpandUrlOf($obj, $baseURLs, $prop = 'URL') {
        if (is_array($obj)) {
          foreach ($obj as $item) { $this->ExpandUrlOf($item, $baseURLs, $prop); }
        } else {
          $url = $obj->$prop();
          if ("$url" !== '') {
            is_array($baseURLs) or $baseURLs = array($baseURLs);
            array_unshift($baseURLs, $obj);

            $obj->$prop( $this->ExpandURL($url, $baseURLs) );
          }
        }
      }

      function ExpandCommonUrlsOf(FeedDescriptor $obj, $baseURLs) {
        $this->ExpandUrlOf($obj->Links()->All(), array_merge(array($obj->Links()), $baseURLs));

        $this->ExpandUrlOf($obj->Authors(), $baseURLs);
        $this->ExpandUrlOf($obj->Categories(), $baseURLs, 'domain');
      }

  function WriteObject(FeedObject $obj, $indentLevel, $tag) {
    $indent = $this->Indent($indentLevel);
    $moreIndent = $this->Indent($indentLevel + 1);

    $result = '';

    if ($obj instanceof FeedText) {
      if ($tag === 'description') {
        $result = (string) $obj->Get($obj->Has('html') ? 'html' : 'xhtml');
      }

      $result === '' and $result = (string) $obj->Get('text');

      if ($result === '') {
        $result = (string) $obj->Get($obj->Has('html') ? 'html' : 'xhtml');
        $result === '' or $result = strip_tags($result);
      }

      if ($result !== '') {
        $result = self::Quote($result, ENT_NOQUOTES);
        $result = "$indent<$tag>$result</$tag>\n";
      }
    } elseif ($obj instanceof FeedImage) {
      $children = array('url' => 'ImageURL', 'title', 'link' => 'LinkURL',
                        'width', 'height', 'description');
      $result = $this->FullXmlTag($tag, array(), $children, $indentLevel, $obj);
    } elseif ($obj instanceof FeedPerson) {
      if ($email = $obj->EMail()) {
        $result = $email;
        $obj->Name() === '' or $result .= ' ('.$obj->Name().')';
      } elseif ($name = $obj->Name()) {
        $result = $name;
      } else {
        $result = $obj->URL();
      }

      $result === '' or $result = "$indent<$tag>$result</$tag>\n";
    } elseif ($obj instanceof FeedTextInput) {
      $children = array('title', 'description', 'name', 'link');
      $result = $this->FullXmlTag($tag, array(), $children, $indentLevel, $obj);
    } elseif ($obj instanceof FeedCloud) {
      $attrs = array('domain', 'port', '%path', 'registerProcedure', 'protocol');
      $attrStr = $this->XmlAttrs($attrs, array(), $obj);
      $attrStr === '' or $result = "$indent<$tag$attrStr />\n";
    } elseif ($obj instanceof FeedCategory) {
      $term = $obj->Term();
      $term === '' and $term = $obj->Label();

      if ($term !== '') {
        $attrStr = $this->XmlAttrs(array('domain'), $obj);
        $result = "$indent<$tag$attrStr>$term</$tag>";
      }
    } elseif ($obj instanceof Feeder) {
      $this->Error("Related feed writing isn't implemented.");
    } else {
      return $this->UnsupportedObjectToWrite($obj, $tag);
    }

    return $result;
  }

  function WriteTime($time, $indent, $tag) {
    if ($time > 0) {
      return $this->Indent($indent)."<$tag>".date(DATE_RFC1123, $time)."</$tag>\n";
    }
  }

  function WriteRssVersion() { return 'version="0.92"'; }

  function WriteDocs($value, $indent) {
    return $this->Indent($indent)."<docs>http://backend.userland.com/rss092</docs>\n\n";
  }

  function WriteGeneratorComment(FeedGenerator $gen, $indent) {
    $name = $gen->Name();

      if ($version = $gen->Version()) {
        $name === '' or $name .= ' ';
        $name .= sprintf('%1.1f', $version);
      }

      $url = $gen->URL();
      $url and $name .= " | $url";

    return $this->Indent($indent)."<!-- Generated by $name -->\n";
  }

  function WriteStdFeedLinks(FeedLinks $links, $indent, $tag) {
    $list = $links->Get('self');
    if ($list) {
      return $this->Indent($indent)."<$tag>".$this->Quote($list[0]->URL())."</$tag>\n";
    }
  }

  function WriteEnclosure(FeedLinks $links, $indent, $tag) {
    $list = $links->Get('enclosure');
    if ($list) {
      $attrs = $this->XmlAttrs(array('url', 'length', 'type'), $list[0]);
      return $this->Indent($indent)."<$tag$attrs />\n";
    }
  }

  function WriteLanguage($lang, $indent) {
    if ($lang) {
      // RSS supports a "stripped-down" version of ISO-639-2:
      // http://backend.userland.com/stories/storyReader$16
      $lang = strtok(strtok($lang, '-'), '_');
      return $this->Indent($indent)."<language>$lang</language>\n";
    }
  }

  function WriteImage($channel, $indent) {
    $image = $channel->Logo();
    $image->ImageURL() or $image = $channel->Icon();

    if ($image->ImageURL()) {
      return $this->WriteObject($image, $indent, 'image');
    }
  }

  function WriteSkipDays(array $list, $indent, $tag) {
    if ($list) {
      $moreIndent = $this->Indent($indent + 1);
      $indent = $this->Indent($indent);

      $result = "$indent<$tag>\n";
      foreach ($list as $item) { $result .= "$moreIndent<day>$item</day>\n"; }
      return "$result$indent</$tag>\n";
    }
  }

    function WriteSkipHours(array $list, $indent, $tag) {
      if ($list) {
        $moreIndent = $this->Indent($indent + 1);
        $indent = $this->Indent($indent);

        $result = "$indent<$tag>\n";
        foreach ($list as $item) { $result .= "$moreIndent<hour>$item</hour>\n"; }
        return "$result$indent</$tag>\n";
      }
    }
}

class Rss20Feed extends Rss092Feed {
  public $mime = 'application/rss+xml';

  function WriteRssVersion() { return 'version="2.0"'; }

  function __construct() {
    parent::__construct();

    $this->feedTags['generator'] = 'generator';
    array_push($this->feedTags, 'ttl', 'category');

    $this->entryTags += array('author' => array('authors'),
                              'comments' => array('commentsURL'),
                              'guid' => 'permalink',
                              'pubDate' => array('published', 'WriteTime'));
  }

  function WriteObject(FeedObject $obj, $indentLevel, $tag) {
    $indent = $this->Indent($indentLevel);

    if ($obj instanceof FeedGenerator) {
      $parts = array($obj->Name(), $obj->Version(), $obj->URL());
      return "$indent<$tag>".join(' ', $parts)."</$tag>\n";
    } else {
      return parent::WriteObject($obj, $indentLevel, $tag);
    }
  }

  function WriteDocs($value, $indent) {
    return $this->Indent($indent)."<docs>http://cyber.law.harvard.edu/rss/rss.html</docs>\n\n";
  }
}


/************************************************************************
  The part responding to standalone script requests.
  Query parameters:
  * format = rss092, rss20, atom (default)
  * count = entries per page (15 by default)
  * (entry name) - as a first GET variable without value
*/

class FeedServer extends FeedObject {
  static function CurDir() { return strtr(getcwd(), '\\', '/').'/'; }

  static function ErrorHeaders($status = '500 Internal Server Error') {
    header("HTTP/1.0 $status");
    header("Status: $status");
    header('Content-Type: text/plain; charset=utf-8');

    $line = "TEXT FEEDER [ $status ]";
    echo "$line\n", str_repeat('=', strlen($line)), "\n\n";
  }

  static function Serve() {
    $entry = null;

      if ($_GET and ((string) reset($_GET)) === '') {
        $entry = urldecode( strtok($_SERVER['QUERY_STRING'], '&') );
      }

    if ($entry === null) {
      self::ServeFeed();
    } else {
      self::ServeEntry($entry);
    }
  }

  static function ServeFeed() {
    $feeder = new TextFeeder(self::CurDir(), self::BaseURL());

    if ($count = &$_GET['count']) {
      $feeder->entriesPerFeed = $count;
    }

    $format = @$_REQUEST['format'];
    $format or $format = 'atom';

    $out = FeedOut::Factory($format);
    $out->Output($feeder);
  }

    static function BaseURL() {
      if (isset($_SERVER['DOCUMENT_ROOT']) and isset($_SERVER['HTTP_HOST'])) {
        $hostRoot = $_SERVER['DOCUMENT_ROOT'];
        $lastDelim = substr($hostRoot, -1);
        if ($lastDelim != '/' and $lastDelim != '\\') {
          $hostRoot .= '/';
        }

        $siteRoot = dirname(__FILE__);  // this file is here: engine/*

        $scheme = @$_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $baseURL = "$scheme://$_SERVER[HTTP_HOST]/";
        if ($path = substr($siteRoot, strlen($hostRoot))) {
          $baseURL .= "$path/";
        }
        return strtr($baseURL, '\\', '/');
      }
    }

  static function ServeEntry($name) {
    $file = self::EntryFileOf($name);
    if (!is_file($file)) {
      throw new ENoFeedEntry($name);
    }

    $content = self::EntryContentOf($name);
    if (!isset($content)) {
      throw new EFeed("Cannot serve entry '$name' - it has no content.");
    }

    $tpl = self::CurDir().'entry.php';
    if (is_file($tpl)) {
      header('Content-Type: text/html; charset=utf-8');
      $ok = include $tpl;
      $ok or $tpl = null;
    } else {
      $tpl = null;
    }

      if (!$tpl) {
        header('Content-Type: text/plain; charset=utf-8');
        echo htmlspecialchars_decode($content);
      }
  }

    static function EntryFileOf($name) {
      $ext = glob(self::CurDir().'*.yml') ? '.yml' : '.txt';
      return self::CurDir().$name.$ext;
    }

    static function EntryContentOf($name) {
      $attached = self::EntryFileOf($name);
      $attached = substr($attached, 0, -1 * strlen(self::ExtOf($attached)) - 1);

      $file = $attached.'.html';
      is_file($file) or $file = $attached.'.htm';
      is_file($file) or $file = $attached.'.txt';

      if (is_file($file)) {
        $content = file_get_contents($file);
        self::ExtOf($file) === 'txt' and $content = self::Quote($content);
      } else {
        $feeder = new TextFeeder(self::CurDir(), self::BaseURL());

        $content = null;

          foreach ($feeder->Entries() as $entry) {
            if ($entry->TextInfo['file'] === $attached.$entry->TextInfo['ext']) {
              $content = $entry->Content()->Get('html');
              isset($content) or $content = $entry->Content()->Get('xhtml');

              if (!isset($content)) {
                $content = $entry->Content()->Get('text');
                isset($content) and $content = self::Quote($content);
              }

              break;
            }
          }
      }

      if (is_string($content)) { return $content; }
    }
}

if (count(get_included_files()) < 2 or defined('ServeFeeds')) {
  set_time_limit(5);
  ignore_user_abort(false);
  mb_internal_encoding('UTF-8');

  if (defined('ServeFeeds')) {
    // be aware that exceptions might be thrown here.
    FeedServer::Serve();
    return true;
  } else {
    try {
      FeedServer::Serve();
    } catch (ENotATextFeedPath $e) {
      FeedServer::ErrorHeaders('404 Not Found');
      echo $e->getMessage();
    } catch (ENoFeedEntry $e) {
      FeedServer::ErrorHeaders('404 Not Found');
      echo $e->getMessage();
    } catch (EFeed $e) {
      FeedServer::ErrorHeaders();
      echo $e->getMessage();
    } catch (Exception $e) {
      FeedServer::ErrorHeaders();
      echo 'Error '.get_class($e).'.';
    }
  }
}
