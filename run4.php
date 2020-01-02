<?php
include 'simple_html_dom.php';

# Use the Curl extension to query Google and get back a page of results
$broken = [
        21, 652
    ];

function compress_htmlcode($codedata)
{
    $searchdata = array(
        '/\>[^\S ]+/s', // remove whitespaces after tags
        '/[^\S ]+\</s', // remove whitespaces before tags
        '/(\s)+/s' // remove multiple whitespace sequences
    );
    $replacedata = array('>','<','\\1');
    $codedata = preg_replace($searchdata, $replacedata, $codedata);
    return $codedata;
}

function trim_whitespace($str) {
    return preg_replace('/\s+/', ' ',$str);
}

for ($index = 336738; $index <= 400001; $index++) {
    if (!in_array($index, $broken)) {
        $zeroes = str_pad(strval($index), 6, '0', STR_PAD_LEFT);
        $url = "https://www.imdb.com/title/tt0" . $zeroes . "/";
        $html = file_get_html($url);
        $doc = $html ? str_get_html($html) : null;
        if ($doc) {
            $titleYear = $doc->getElementById("titleYear") ? $doc->getElementById("titleYear")->plaintext : '';
            if ($doc->getElementById("titleYear")) {$doc->getElementById("titleYear")->outertext = '';}
            $title = str_replace("&nbsp;", "", $doc->find('h1')[0]->innertext);
            $title = str_replace("      ", "", $title);

            $poster = "";
            if (count($doc->find('.poster')) > 0 && count($doc->find('.poster')[0]->find('a')) > 0) {
                $poster = $doc->find('.poster')[0]->find('a')[0]->find('img')[0]->src;
            }

            $ratingValue = '0';
            $ratingCount = '0';
            if (count($doc->find('.ratingValue')) > 0) {
                $ratingValue = $doc->find('.ratingValue')[0]->find('strong')[0]->find('span')[0]->innertext;
                $ratingCount = $doc->find('.imdbRating')[0]->find('a')[0]->find('span')[0]->innertext;
                $ratingCount = str_replace(",", "", $ratingCount);
            }

            $urlFullCredit = $url . 'fullcredits/';
            
            $htmlFullCredit = file_get_html($urlFullCredit);
            $docFullCredit = $htmlFullCredit ? str_get_html($htmlFullCredit) : null;
            $h4FullCredit = $docFullCredit ? $docFullCredit->find('#fullcredits_content')[0]->find('h4') : '';
            $tableFullCredit = $docFullCredit ? $docFullCredit->find('#fullcredits_content')[0]->find('table') : [];
            $arrayFullCredit = [];
            if (count($h4FullCredit) !== count($tableFullCredit)) {
                echo 'error not matching counts h4 vs table';
            }

            for ($i = 0; $i < count($h4FullCredit); $i++) {
                $h4 = $h4FullCredit[$i];
                $table = $tableFullCredit[$i];

                $name = str_replace('&nbsp;', '', $h4->innertext);
                $name = trim_whitespace($name);
                $text = compress_htmlcode($table->outertext);
                $arrayFullCredit[$i] = array('id' => $i, 'name' => $name, 'text' => $text);
            }

            $urlPlotSummary = $url . 'plotsummary/';
            $htmlPlotSummary = file_get_html($urlPlotSummary);
            $docPlotSummary = $htmlPlotSummary ? str_get_html($htmlPlotSummary) : null;
            $plotSummaryName = $docPlotSummary && count($docPlotSummary->find('#summaries')) > 0 ? $docPlotSummary->find('#summaries')[0]->innertext : "Summaries";

            $plotSummaryUl = $docPlotSummary && count($docPlotSummary->find('#plot-summaries-content')) > 0 ? $docPlotSummary->find('#plot-summaries-content')[0]->find('li') : [];

            $arrayPlotSummary = [];
            for ($i = 0; $i < count($plotSummaryUl); $i++) {
                $li = $plotSummaryUl[$i];

                $name = str_replace('&nbsp;', '', $plotSummaryName);
                $text = str_replace('   ', '', $li->find('p')[0]->innertext);
                $text = str_replace(' <p>', '<p>', $text);
                $text = str_replace('</p> ', '<p>', $text);
                $text = str_replace('"', '\\"', $text);
                $author = "";
                if (count($li->find('div')) > 0) {
                    $author = str_replace(' ', '', $li->find('div')[0]->find('em')[0]->find('a')[0]->innertext);
                }

                $arrayPlotSummary[$i] = array('id' => $i, 'name' => $name, 'text' => $text, 'author' => $author);
            }

            $synopsisName = $docPlotSummary && count($docPlotSummary->find('#synopsis')) > 0 ? $docPlotSummary->find('#synopsis')[0]->innertext : "Summaries";
            $synopsisUl = $docPlotSummary && count($docPlotSummary->find('#plot-synopsis-content')) > 0 ? $docPlotSummary->find('#plot-synopsis-content')[0]->find('li') : [];

            $arraySynopsis = [];
            for ($i = 0; $i < count($synopsisUl); $i++) {
                $li = $synopsisUl[$i];

                $name = str_replace('&nbsp;', '', $synopsisName);
                $text = str_replace('   ', '', $li->innertext);
                $text = str_replace('  <p>', '<p>', $text);
                $text = str_replace('</p> ', '<p>', $text);
                $text = str_replace('"', '\\"', $text);

                $arraySynopsis[$i] = array('id' => $i, 'name' => $name, 'text' => $text, 'author' => $author);
            }

            $urlKeywords = $url . 'keywords/';
            $htmlKeywords = file_get_html($urlKeywords);
            $docKeywords = $htmlKeywords ? str_get_html($htmlKeywords) : null;

            $keywords = [];
            if ($docKeywords && count($docKeywords->find('.dataTable.evenWidthTable2Col')) > 0) {
                $tableKeywords = $docKeywords->find('.dataTable.evenWidthTable2Col')[0];
                for ($k = 0; $k < count($docKeywords->find('.dataTable.evenWidthTable2Col')[0]->find('.did-you-know-actions')); $k++) {
                    $docKeywords->find('.dataTable.evenWidthTable2Col')[0]->find('.did-you-know-actions')[$k]->outertext = '';
                }
                $tableKeywords = str_replace("   ", "", $tableKeywords);
                $keywords = array('id' => 0, 'name' => 'Keywords', 'text' => $tableKeywords);
            }

            $urlTaglines = $url . 'taglines/';
            $htmlTaglines = file_get_html($urlTaglines);
            $docTaglines = $htmlTaglines ? str_get_html($htmlTaglines) : null;
            
            $arrayTaglines = [];
            if ($docTaglines && count($docTaglines->find('.soda')) > 0) {
                $taglineDivs = $docTaglines->find('.soda');
                for ($i = 0; $i < count($taglineDivs); $i++) {
                    $div = $taglineDivs[$i];
                    $text = str_replace('   ', '', $div->innertext);
                    if (strpos($text, 'It looks like') !== false) {
                        $text = '';
                    } else {
                        $text = str_replace('  ', '', $text);
                    }

                    $arrayTaglines[$i] = array('id' => $i, 'name' => 'Taglines', 'text' => $text);
                }
            }

            $wraps = $doc->find('.see-more.inline.canwrap');
            $aGenres = '';
            for ($i = 0; $i < count($wraps); $i++) {
                $h4Txt = $wraps[$i]->find('h4')[0]->innertext;

                if ($h4Txt === 'Genres:') {
                    $aGenres = $aGenres . $wraps[$i]->find('a')[0]->innertext;
                    for ($j = 0; $j < count($wraps[$i]->find('a')); $j++) {
                        if ($j > 0) {
                            $aGenres = $aGenres . ',' . $wraps[$i]->find('a')[$j]->innertext;
                        }
                    }
                }
            }

            if ($aGenres === '') {
                $aGenres = 'Horror';
            }

            $urlParentalGuide = $url . 'parentalguide/';
            $htmlParentalGuide = file_get_html($urlParentalGuide);
            $docParentalGuide = $htmlParentalGuide ? str_get_html($htmlParentalGuide) : null;
            
            $arrayParentalGuide = [];
            $sections = $docParentalGuide ? $docParentalGuide->find('section.article.listo.content-advisories-index')[0]->find('section') : [];

            for ($i = 0; $i < count($sections); $i++) {

                $section = $sections[$i];

                if ($i === 0) {
                    $name = $section->find('header')[0]->find('h4')[0]->innertext;
                    $text = '';
                    if (count($section->find('table')) > 0) {
                        $text = compress_htmlcode($section->find('table')[0]->outertex);
                    }

                    $arrayParentalGuide[$i] = array('id' => $i, 'name' => $name, 'text' => [$text]);
                } else {
                    $name = $section->find('h4')[0]->innertext;
                    $texts = $section->find('li.ipl-zebra-list__item');
                    $textsArr = [];
                    for ($j = 0; $j < count($texts); $j++) {
                        for ($k = 0; $k < count($texts[$j]->find('.ipl-hideable-container.ipl-hideable-container--hidden.ipl-zebra-list__action-row')); $k++) {
                            $texts[$j]->find('.ipl-hideable-container.ipl-hideable-container--hidden.ipl-zebra-list__action-row')[$k]->outertext = '';
                        }
                        $text = $texts[$j]->innertext;
                        $text = str_replace('                         ', '', $text);
                        $text = str_replace('.                           ', '.', $text);
                        $text = str_replace('.  ', '.', $text);
                        $textsArr[$j] = $text;
                    }
                    $arrayParentalGuide[$i] = array('id' => $i, 'name' => $name, 'text' => $textsArr);
                }
            }


            $urlReleaseInfo = $url . 'releaseinfo/';
            $htmlReleaseInfo = file_get_html($urlReleaseInfo);
            $docReleaseInfo = $htmlReleaseInfo ? str_get_html($htmlReleaseInfo) : null;
            
            $arrayReleaseInfo = [];

            $h4Arr = $docReleaseInfo && count($docReleaseInfo->find('#releaseinfo_content')) > 0 ? $docReleaseInfo->find('#releaseinfo_content')[0]->find('h4') : [];
            $tableArr = $docReleaseInfo && count($docReleaseInfo->find('#releaseinfo_content')) > 0 ? $docReleaseInfo->find('#releaseinfo_content')[0]->find('table') : [];

            if (count($h4Arr) !== count($tableArr)) {
                echo 'error not matching counts h4 vs table';
            }

            for ($i = 0; $i < count($h4Arr); $i++) {
                $h4 = $h4Arr[$i];
                $table = $tableArr[$i];

                $name = str_replace('&nbsp;', '', $h4->innertext);
                $name = str_replace('     ', '', $h4->innertext);
                $text = compress_htmlcode($table->outertext);
                $arrayReleaseInfo[$i] = array('id' => $i, 'name' => $name, 'text' => $text);
            }

            $urlLocations = $url . 'locations/';
            $htmlLocations = file_get_html($urlLocations);
            $docLocations = $htmlLocations ? str_get_html($htmlLocations) : null;
            
            $locations = array('id' => 0, 'name' => 'Filming Locations', 'text' => '');

            if ($docLocations && count($docLocations->find('h4.ipl-header__content.ipl-list-title')) > 0) {
                $nameLocations = $docLocations->find('h4.ipl-header__content.ipl-list-title')[0]->innertext;

                $sodaDivs = $docLocations->find('div.soda.sodavote');
                $texts = [];
                for ($i = 0; $i < count($sodaDivs); $i++) {
                    $soda = $sodaDivs[$i];
                    $text = $soda->find('dt')[0]->find('a')[0]->innertext;
                    $texts[$i] = $text;
                }

                $locations = array('id' => 0, 'name' => $nameLocations, 'text' => $texts);
            }

            $dates = array('id' => 0, 'name' => 'Filming Dates', 'text' => '');
            if ($docLocations && count($docLocations->find('#filming_dates')) > 0) {
                $nameDates = $docLocations->find('#filming_dates')[0]->find('h4')[0]->innertext;

                $lis = $docLocations->find('#filming_dates')[0]->find('ul')[0]->find('li.ipl-zebra-list__item');
                $texts = [];
                for ($i = 0; $i < count($lis); $i++) {
                    $li = $lis[$i];
                    $text = $li->innertext;
                    $text = str_replace('                     ', '', $text);
                    $text = str_replace('    ', '', $text);
                    $texts[$i] = $text;
                }

                $dates = array('id' => 0, 'name' => $nameDates, 'text' => $texts);
            }

            $urlTechnical = $url . 'technical/';
            $htmlTechnical = file_get_html($urlTechnical);
            $docTechnical = $htmlTechnical ? str_get_html($htmlTechnical) : null;
            
            $technical = array('id' => 0, 'name' => 'Technical Specifications', 'text' => '');

            if ($docTechnical && count($docTechnical->find('table.dataTable.labelValueTable')) > 0) {
                $text = compress_htmlcode($docTechnical->find('table.dataTable.labelValueTable')[0]->outertext);

                $technical = array('id' => 0, 'name' => 'Technical Specifications', 'text' => $text);
            }

            $urlFAQ = $url . 'faq/';
            $htmlFAQ = file_get_html($urlFAQ);
            $docFAQ = $htmlFAQ ? str_get_html($htmlFAQ) : null;
            
            $faqHeads = $docFAQ ? $docFAQ->find('.ipl-header__content.ipl-list-title') : [];
            $faqUls = $docFAQ ? $docFAQ->find('ul.ipl-zebra-list') : [];

            $arrayFAQ = [];

            for ($j = 0; $j < count($faqHeads); $j++) {
                $name = $faqHeads[$j]->innertext;
                $faqTexts = [];
                for ($i = 0; $i < count($faqUls[$j]->find('div.faq-question-text')); $i++) {
                    $text = $faqUls[$j]->find('div.faq-question-text')[$i]->innertext;
                    $faqTexts[$i] = $text;
                }
                $arrayFAQ[$i] = array('id' => $i, 'name' => $name, 'text' => $faqTexts);
            }

            $urlAwards = $url . 'awards/';
            $htmlAwards = file_get_html($urlAwards);
            $docAwards = $htmlAwards ? str_get_html($htmlAwards) : null;
            
            $awardsHeads = $docAwards && count($docAwards->find('.article.listo')) > 0 ? $docAwards->find('.article.listo')[0]->find('h3') : "Awards and nominations";
            $awardsTables = $docAwards ? $docAwards->find('table.awards') : [];

            $arrayAwards = [];

            for ($j = 0; $j < count($awardsTables); $j++) {
                $arrayAwards[$j] = array('id' => $j, 'name' => trim_whitespace($awardsHeads[$j]->plaintext), 'text' => compress_htmlcode($awardsTables[$j]->outertext));
            }

            $urlSoundtrack = $url . 'soundtrack/';
            $htmlSoundtrack = file_get_html($urlSoundtrack);
            $docSoundtrack = $htmlSoundtrack ? str_get_html($htmlSoundtrack) : null;

            $soundtracks = array('id' => 0, 'name' => 'Soundtrack Credits', 'text' => '');

            if ($docSoundtrack && count($docSoundtrack->find('#soundtracks_content')) > 0 && count($docSoundtrack->find('#soundtracks_content')[0]->find('h4')) > 0 && count($docSoundtrack->find('#no_content')) <= 0) {
                $nameSoundtrack = $docSoundtrack->find('#soundtracks_content')[0]->find('h4')[0]->innertext;
                $nameSoundtrack = str_replace("&nbsp;", "", $nameSoundtrack);
                $sodaDivs = $docSoundtrack->find('.soundTrack.soda');
                $texts = [];
                for ($i = 0; $i < count($sodaDivs); $i++) {
                    $soda = $sodaDivs[$i];
                    $text = $soda->plaintext;
                    $texts[$i] = trim_whitespace($text);
                }

                $soundtracks = array('id' => 0, 'name' => $nameSoundtrack, 'text' => $texts);
            }

            $urlTrivia = $url . 'trivia/';
            $htmlTrivia = file_get_html($urlTrivia);
            $docTrivia = $htmlTrivia ? str_get_html($htmlTrivia) : null;
            
            $trivias = array('id' => 0, 'name' => 'Trivia', 'text' => '');

            if ($docTrivia && count($docTrivia->find('#no_content')) <= 0) {
                $sodaDivs = $docTrivia->find('.sodatext');
                $texts = [];
                for ($i = 0; $i < count($sodaDivs); $i++) {
                    $soda = $sodaDivs[$i];
                    $text = $soda->plaintext;
                    $texts[$i] = trim_whitespace($text);
                }

                $trivias = array('id' => 0, 'name' => 'Trivia', 'text' => $texts);
            }

            $urlQuotes = $url . 'quotes/';
            $htmlQuotes = file_get_html($urlQuotes);
            $docQuotes = $htmlQuotes ? str_get_html($htmlQuotes) : null;
            
            $quotes = array('id' => 0, 'name' => 'Quotes', 'text' => '');

            if ($docQuotes && count($docQuotes->find('#no_content')) <= 0) {
                $sodaDivs = $docQuotes->find('.sodatext');
                $texts = [];
                for ($i = 0; $i < count($sodaDivs); $i++) {
                    $soda = $sodaDivs[$i];
                    $text = $soda->plaintext;
                    $texts[$i] = trim_whitespace($text);
                }

                $quotes = array('id' => 0, 'name' => 'Quotes', 'text' => $texts);
            }

            $urlGoofs = $url . 'goofs/';
            $htmlGoofs = file_get_html($urlGoofs);
            $docGoofs = $htmlGoofs ? str_get_html($htmlGoofs) : null;
            
            $goofs = array('id' => 0, 'name' => 'Goofs', 'text' => '');

            if ($docGoofs && count($docGoofs->find('#no_content')) <= 0) {
                $sodaDivs = $docGoofs->find('.sodatext');
                $texts = [];
                for ($i = 0; $i < count($sodaDivs); $i++) {
                    $soda = $sodaDivs[$i];
                    $text = $soda->plaintext;
                    $texts[$i] = trim_whitespace($text);
                }

                $goofs = array('id' => 0, 'name' => 'Goofs', 'text' => $texts);
            }

            $urlCC = $url . 'crazycredits/';
            $htmlCC = file_get_html($urlCC);
            $docCC = $htmlCC ? str_get_html($htmlCC) : null;
            
            $CC = array('id' => 0, 'name' => 'Crazy Credits', 'text' => '');

            if ($docCC && count($docCC->find('#no_content')) <= 0) {
                $sodaDivs = $docCC->find('.sodatext');
                $texts = [];
                for ($i = 0; $i < count($sodaDivs); $i++) {
                    $soda = $sodaDivs[$i];
                    $text = $soda->plaintext;
                    $texts[$i] = trim_whitespace($text);
                }

                $CC = array('id' => 0, 'name' => 'Crazy Credits', 'text' => $texts);
            }

            $urlAV = $url . 'alternateversions/';
            $htmlAV = file_get_html($urlAV);
            $docAV = $htmlAV ? str_get_html($htmlAV) : null;
            
            $AV = array('id' => 0, 'name' => 'Alternate Versions', 'text' => '');

            if ($docAV && count($docAV->find('#no_content')) <= 0) {
                $sodaDivs = $docAV->find('.sodatext');
                $texts = [];
                for ($i = 0; $i < count($sodaDivs); $i++) {
                    $soda = $sodaDivs[$i];
                    $text = $soda->plaintext;
                    $texts[$i] = trim_whitespace($text);
                }

                $AV = array('id' => 0, 'name' => 'Crazy Credits', 'text' => $texts);
            }

            $urlMC = $url . 'movieconnections/';
            $htmlMC = file_get_html($urlMC);
            $docMC = $htmlMC ? str_get_html($htmlMC) : null;
            
            $MC = array('id' => 0, 'name' => 'Connections', 'text' => '');

            if ($docMC && count($docMC->find('#no_content')) <= 0) {
                $sodaDivs = $docMC->find('.sodatext');
                $texts = [];
                for ($i = 0; $i < count($sodaDivs); $i++) {
                    $soda = $sodaDivs[$i];
                    $text = $soda->plaintext;
                    $texts[$i] = trim_whitespace($text);
                }

                $MC = array('id' => 0, 'name' => 'Crazy Credits', 'text' => $texts);
            }

            echo $url . "\n";
            /*echo $title.' '.$titleYear . "\n";
            echo $poster . "\n";
            echo $ratingValue . "\n";
            echo $ratingCount . "\n";
            var_dump($arrayFullCredit);
            var_dump($arrayPlotSummary);
            var_dump($arraySynopsis);
            var_dump($keywords);
            var_dump($arrayTaglines);
            echo $aGenres . "\n";
            var_dump($arrayParentalGuide);
            var_dump($arrayReleaseInfo);
            var_dump($locations);
            var_dump($dates);
            var_dump($technical);
            var_dump($arrayFAQ);
            var_dump($arrayAwards);
            var_dump($soundtracks);
            var_dump($trivias);
            var_dump($quotes);
            var_dump($goofs);
            var_dump($CC);
            var_dump($AV);
            var_dump($MC);*/

            $post_data = json_encode(
                array(
                    'titleYear' => $title,
                    'title' => $titleYear,
                    'name' => $title . ' ' . $titleYear,
                    'imdb' => array(
                        'url' => $url,
                        'poster' => $poster,
                        'rating' => $ratingValue,
                        'count' => $ratingCount,
                        'genre' => $aGenres,
                        'arrayFullCredit' => $arrayFullCredit,
                        'arrayPlotSummary' => $arrayPlotSummary,
                        'arraySynopsis' => $arraySynopsis,
                        'keywords' => $keywords,
                        'arrayTaglines' => $arrayTaglines,
                        'arrayParentalGuide' => $arrayParentalGuide,
                        'arrayReleaseInfo' => $arrayReleaseInfo,
                        'locations' => $locations,
                        'dates' => $dates,
                        'technical' => $technical,
                        'arrayFAQ' => $arrayFAQ,
                        'arrayAwards' => $arrayAwards,
                        'soundtracks' => $soundtracks,
                        'trivias' => $trivias,
                        'quotes' => $quotes,
                        'goofs' => $goofs,
                        'CC' => $CC,
                        'AV' => $AV,
                        'MC' => $MC
                    )
                )
            );
            file_put_contents('out.json', $post_data . ",", FILE_APPEND | LOCK_EX);
        }
    }
}
?>
