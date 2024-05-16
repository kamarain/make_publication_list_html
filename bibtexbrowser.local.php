<?php
@define('BIBLIOGRAPHYSTYLE','JoniBibliographyStyle');
@define('BIBTEXBROWSER_BIBTEX_LINKS',false);
@define('BIBTEXBROWSER_LINK_STYLE','bib2links_joni');
@define('BIBTEXBROWSER_CODE_LINKS',true);
@define('BIBTEXBROWSER_DATA_LINKS',true);
@define('BIBTEXBROWSER_YOUTUBE_LINKS',true);
@define('BIBTEXBROWSER_LAYOUT','list');

function JoniBibliographyStyle($bibentry) {
  $title = $bibentry->getTitle();
  $type = $bibentry->getType();

  // later on, all values of $entry will be joined by a comma
  $entry=array();

  // title
  // usually in bold: .bibtitle { font-weight:bold; }
  $title = '<span class="bibtitle"  itemprop="name"><strong>'.$title.'</strong></span>';
  //if ($bibentry->hasField('url')) $title = ' <a'.get_target().' href="'.$bibentry->getField('url').'">'.$title.'</a>';


  $coreInfo = $title;

  // adding author info
  if ($bibentry->hasField('author')) {
    $coreInfo .= ' (<span class="bibauthor">';

    $authors = array();
    foreach ($bibentry->getFormattedAuthorsArray() as $a) {
       $authors[]='<span itemprop="author" itemtype="http://schema.org/Person">'.$a.'</span>';
    }
    $coreInfo .= $bibentry->implodeAuthors($authors);

    $coreInfo .= '</span>)';
  }

  // core info usually contains title + author
  $entry[] = $coreInfo;

  // now the book title
  $booktitle = '';
  if ($type=="inproceedings") {
      $booktitle = __('In').' '.'<span itemprop="isPartOf"><em>'.$bibentry->getField(BOOKTITLE).'</em></span>'; }
  if ($type=="incollection") {
      $booktitle = __('Chapter in').' '.'<span itemprop="isPartOf"><em>'.$bibentry->getField(BOOKTITLE).'</em></span>';}
  if ($type=="inbook") {
      $booktitle = __('Chapter in').' '.$bibentry->getField('chapter');}
  if ($type=="article") {
      $booktitle = __('In').' '.'<span itemprop="isPartOf"><em>'.$bibentry->getField("journal").'</em></span>';}

  //// we may add the editor names to the booktitle
  $editor='';
  if ($bibentry->hasField(EDITOR)) {
    $editor = $bibentry->getFormattedEditors();
  }
  if ($editor!='') $booktitle .=' ('.$editor.')';
  // end editor section

  // is the booktitle available
  if ($booktitle!='') {
    $entry[] = '<span class="bibbooktitle">'.$booktitle.'</span>';
  }


  $publisher='';
  if ($type=="phdthesis") {
      $publisher = __('PhD thesis').', '.$bibentry->getField(SCHOOL);
  }
  if ($type=="mastersthesis") {
      $publisher = __('Master\'s thesis').', '.$bibentry->getField(SCHOOL);
  }
  if ($type=="bachelorsthesis") {
      $publisher = __('Bachelor\'s thesis').', '.$bibentry->getField(SCHOOL);
  }
  if ($type=="techreport") {
      $publisher = __('Technical report');
      if ($bibentry->hasField("number")) {
          $publisher .= ' '.$bibentry->getField("number");
      }
      $publisher .= ', '.$bibentry->getField("institution");
  }

  if ($type=="misc") {
      $publisher = $bibentry->getField('howpublished');
  }

  if ($bibentry->hasField("publisher")) {
    $publisher = $bibentry->getField("publisher");
  }

  if ($publisher!='') $entry[] = '<span class="bibpublisher">'.$publisher.'</span>';


  if ($bibentry->hasField('volume')) $entry[] =  __('volume').' '.$bibentry->getField("volume");


  if ($bibentry->hasField(YEAR)) $entry[] = '<span itemprop="datePublished">'.$bibentry->getYear().'</span>';

  $result = implode(", ",$entry).'.';

  // Code array tab
  //if ($bibentry->hasField('code')) $entry[] =  __('volume').' '.$bibentry->getField("volume");

  // some comments (e.g. acceptance rate)?
  if ($bibentry->hasField('comment')) {
      $result .=  " (".$bibentry->getField("comment").")";
  }

  // add the Coin URL
  $result .=  $bibentry->toCoins();

  return '<span itemscope="" itemtype="http://schema.org/ScholarlyArticle">'.$result.'</span>';
}

function bib2links_joni($bibentry) {
  $links = array();

  if (BIBTEXBROWSER_BIBTEX_LINKS) {
    $link = $bibentry->getBibLink();
    if ($link != '') { $links[] = $link; };
  }

  if (BIBTEXBROWSER_PDF_LINKS) {
    $link = $bibentry->getPdfLink();
    if ($link != '') { $links[] = $link; };
  }

  if (BIBTEXBROWSER_DOI_LINKS) {
    $link = $bibentry->getDoiLink();
    if ($link != '') { $links[] = $link; };
  }

  if (BIBTEXBROWSER_GSID_LINKS) {
    $link = $bibentry->getGSLink();
    if ($link != '') { $links[] = $link; };
  }

  if (BIBTEXBROWSER_CODE_LINKS) {
     $link=$bibentry->getLink('code');
     if ($link != '') { $links[] = $link; };
  }

  // "video" would be better, but some content managers get confused from [video]
  if (BIBTEXBROWSER_YOUTUBE_LINKS) {
     $link=$bibentry->getLink('youtube');
     if ($link != '') { $links[] = $link; };
  }

  if (BIBTEXBROWSER_DATA_LINKS) {
     $link=$bibentry->getLink('data');
     if ($link != '') { $links[] = $link; };
  }

  return '<span class="bibmenu">'.implode(" ",$links).'</span>';
}

?>
