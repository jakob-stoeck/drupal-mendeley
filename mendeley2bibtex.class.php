<?
class Mendeley2bibtex {
  private $entryTypes = array(
    'Magazine Article' => 'article',
    'book',
    'booklet',
    'conference',
    'inbook',
    'incollection',
    'inproceedings',
    'manual',
    'mastersthesis',
    'misc',
    'phdthesis',
    'proceedings',
    'techreport',
    'unpublished'
  );

  /**
   * The key represents the Mendeley API entry, the value the BibTeX entry
   * 
   * Values without keys are BibTex fields which are not yet supported by Mendeley
   */
  private $map = array(
    'city' => 'address', // Publisher's address (usually just the city, but can be the full address for lesser-known publishers)
    'annote', // An annotation for annotated bibliography styles (not typical)
    'authors' => 'author', // The name(s) of the author(s) (in the case of more than one author, separated by and)
    'booktitle', // The title of the book, if only part of it is being cited
    'chapter', // The chapter number
    'crossref', // The key of the cross-referenced entry
    'edition' => 'edition', // The edition of a book, long form (such as "first" or "second")
    'editors' => 'editor', // The name(s) of the editor(s)
    'eprint', // A specification of an electronic publication, often a preprint or a technical report
    'howpublished', // How it was published, if the publishing method is nonstandard
    'institution', // The institution that was involved in the publishing, but not necessarily the publisher
    'journal', // The journal or magazine the work was published in
    'key', // A hidden field used for specifying or overriding the alphabetical order of entries (when the "author" and "editor" fields are missing). Note that this is very different from the key (mentioned just after this list) that is used to cite or cross-reference the entry.
    'month', // The month of publication (or, if unpublished, the month of creation)
    'note', // Miscellaneous extra information
    'number', // The "number" of a journal, magazine, or tech-report, if applicable. (Most publications have a "volume", but no "number" field.)
    'organization', // The conference sponsor
    'pages', // Page numbers, separated either by commas or double-hyphens.
    'publisher' => 'publisher', // The publisher's name
    'school', // The school where the thesis was written
    'series', // The series of books the book was published in (e.g. "The Hardy Boys" or "Lecture Notes in Computer Science")
    'title' => 'title', // The title of the work
    'type', // The type of tech-report, for example, "Research Note"
    'url' => 'url', // The WWW address
    'volume', // The volume of a journal or multi-volume book
    'year' => 'year', // The year of publication (or, if unpublished, the year of creation)
  );

  /**
   * Parses a document represented in JSON from mendeley API to bibtex vocabulary
   */
  function translate($json) {
    $bib = new BibTexReference();

    foreach($json as $mendeleyKey => $value) {
      if(
        isset($this->map[$mendeleyKey]) &&
        $bibTexKey = $this->map[$mendeleyKey] &&
        $methodName = 'set' . $bibTexKey
      ) {
        if($method_exists('BibTexReference', $methodName)) {
          $bib->$methodName($value);
        } elseif(property_exists('BibTexReference', $bibTexKey)) {
          $bib->$bibTexKey = $value;
        }
      } else {
        // property wasn't found in mendeley->bibtex map
      }
    }
  }

}

class BibTexReference {
  private $entryType;
  
  private $author; // The name(s) of the author(s) (in the case of more than one author, separated by and)
  private $editor; // The name(s) of the editor(s)
  public $address; // Publisher's address (usually just the city, but can be the full address for lesser-known publishers)
  public $annote; // An annotation for annotated bibliography styles (not typical)
  public $booktitle; // The title of the book, if only part of it is being cited
  public $chapter; // The chapter number
  public $crossref; // The key of the cross-referenced entry
  public $edition; // The edition of a book, long form (such as "first" or "second")
  public $eprint; // A specification of an electronic publication, often a preprint or a technical report
  public $howpublished; // How it was published, if the publishing method is nonstandard
  public $institution; // The institution that was involved in the publishing, but not necessarily the publisher
  public $journal; // The journal or magazine the work was published in
  public $key; // A hidden field used for specifying or overriding the alphabetical order of entries (when the "author" and "editor" fields are missing). Note that this is very different from the key (mentioned just after this list) that is used to cite or cross-reference the entry.
  public $month; // The month of publication (or, if unpublished, the month of creation)
  public $note; // Miscellaneous extra information
  public $number; // The "number" of a journal, magazine, or tech-report, if applicable. (Most publications have a "volume", but no "number" field.)
  public $organization; // The conference sponsor
  public $pages; // Page numbers, separated either by commas or double-hyphens.
  public $publisher; // The publisher's name
  public $school; // The school where the thesis was written
  public $series; // The series of books the book was published in (e.g. "The Hardy Boys" or "Lecture Notes in Computer Science")
  public $title; // The title of the work
  public $type; // The type of tech-report, for example, "Research Note"
  public $url; // The WWW address
  public $volume; // The volume of a journal or multi-volume book
  public $year; // The year of publication (or, if unpublished, the year of creation)

  private $bibTexEntryTypes = array(
    'article',
    'book',
    'booklet',
    'conference',
    'inbook',
    'incollection',
    'inproceedings',
    'manual',
    'mastersthesis',
    'misc',
    'phdthesis',
    'proceedings',
    'techreport',
    'unpublished'
  );

  private function implodeNames($names) {
    $names = (array)$names;
    return implode(' and ', $names);
  }

  function setAuthor($authors) {
    $this->author = $this->implodeNames($names);
  }

  function setEditor($editors) {
    $this->author = $this->implodeNames($editors);
  }

  function getRef() {
    return $this->slug($this->author). ':' .$this->slug($this->title);
  }

  public function setEntryType($type) {
    if(in_array($type, $bibTexEntryTypes)) {
      $this->entryType = $type;
    } else {
      $this->entryType = 'misc';
    }
  }

  private function slug($string) {
    $string = mbstrtolower($string, 'utf-8');
    $string = str_replace(' ', '_');
    return $string;
  }

  function toString() {
    $out = sprintf('@%s{%s,', $this->entryType, $this->getRef);

    unset($doc->entryType);
    unset($doc->name);

    foreach($object as $key => $value) {
      $out .= sprintf('%s = {%s}', $key, $value);
    }
    $out .= '}';
  }
}
