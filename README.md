# php-service-tools
## HMD - Horror Movies Database
PHP REST web-service project of Largest Horror Movies Database in Universe.
## centralbrainz.tv
Website is published to:
* https://centralbrainz.tv/php-service/
## Releases
### v1.0.0
API support the following methods:
` php-service/years/index/page/1/200/name/1: `

Default call for years list.

Example of response:

```json
{
   "count":7,
   "total":119,
   "result":[
      "(2011)",
      "(2012)",
      "(2013)",
      "(2014)",
      "(2016)",
      "(2017)",
      "(2019)"
   ]
}
```

` php-service/index/index/page/1/100/count/0: `

Default call for index.

Example of response:
```json
{
   "count":9444,
   "total":1,
   "result":[
      {
         "titleYear":"Yin yeung lo 16: Wui do mo hap si doi",
         "title":"(2002)",
         "name":"Yin yeung lo 16: Wui do mo hap si doi (2002)",
         "imdb":{
            "url":"https:\/\/www.imdb.com\/title\/tt0346060\/",
            "poster":"",
            "rating":"4.1",
            "count":"21",
            "genre":" Comedy, Fantasy, Horror",
            "arrayFullCredit":[
               {
                  "id":0,
                  "name":"Directed by",
                  "text":""
               }
            ],
            "arrayPlotSummary":[
               {
                  "id":0,
                  "name":"Summaries",
                  "text":"The 16th film in the Troublesome Night series brings back regulars Simon Loui, Lan Law, Kai Fai Tong and Ho Lung Cheung in a story that takes place in the Sung Dynasty. There, they encounter the characters of the famous Chinese Story &quot;The Water Margin,&quot; a story that takes place about a thousand years ago in ancient China involving a cruel government, con artists, a beautiful but crafty village girl and a sword-wielding martial artist trying to overcome all the adversaries.",
                  "author":"OliverChu"
               }
            ],
            "arraySynopsis":[
               {
                  "id":0,
                  "name":"Synopsis",
                  "text":"",
                  "author":"OliverChu"
               }
            ],
            "keywords":[

            ],
            "arrayTaglines":[
               {
                  "id":0,
                  "name":"Taglines",
                  "text":""
               }
            ],
            "arrayParentalGuide":[
               {
                  "id":0,
                  "name":"Certification",
                  "text":[
                     ""
                  ]
               }
            ],
            "arrayReleaseInfo":[
               {
                  "id":0,
                  "name":"Release Dates",
                  "text":""
               }
            ],
            "locations":{
               "id":0,
               "name":"Filming Locations",
               "text":""
            },
            "dates":{
               "id":0,
               "name":"Filming Dates",
               "text":""
            },
            "technical":{
               "id":0,
               "name":"Technical Specifications",
               "text":""
            },
            "arrayFAQ":[
               {
                  "id":0,
                  "name":"Spoilers",
                  "text":[]
               }
            ],
            "arrayAwards":[

            ],
            "soundtracks":{
               "id":0,
               "name":"Soundtrack Credits",
               "text":""
            },
            "trivias":{
               "id":0,
               "name":"Trivia",
               "text":""
            },
            "quotes":{
               "id":0,
               "name":"Quotes",
               "text":""
            },
            "goofs":{
               "id":0,
               "name":"Goofs",
               "text":""
            },
            "CC":{
               "id":0,
               "name":"Crazy Credits",
               "text":""
            },
            "AV":{
               "id":0,
               "name":"Alternate Versions",
               "text":""
            },
            "MC":{
               "id":0,
               "name":"Crazy Credits",
               "text":[

               ]
            }
         }
      }
   ]
}
```

` php-service/index/index/page/{page}/{count}/{sortBy}/{sortDesc}: `

Returns list of movies not filtered by any parameter.

` php-service/movie/{movie-name}/page/{page}/{count}/{sortBy}/{sortDesc}: `

Returns movies list for specified movie name parameter.

` php-service/genre/{genre-filter}/page/{page}/{count}/{sortBy}/{sortDesc}: `

Returns movies list for specified genre filter parameter.

` php-service/rating/{rating-filter}/page/{page}/{count}/{sortBy}/{sortDesc}: `

Returns movies list for specified rating filter parameter.

` php-service/fulltext/{fulltext-search}/page/{page}/{count}/{sortBy}/{sortDesc}: `

Returns movies list for specified fulltext search filter parameter.

` php-service/search/{search-text}/page/{page}/{count}/{sortBy}/{sortDesc}: `

Returns movies list for specified fast text search filter parameter.

` php-service/year/{year-filter}/page/{page}/{count}/{sortBy}/{sortDesc}: `

Returns movies list for specified year filter parameter.

* {**page**} - current page for pagination default 1.
* {**count**} - count of items to return, default 20.
* {**sortBy**} - sort output by [rating, count, name, year].
* {**sortDesc**} - sort order, 0 - descending, 1 - ascending.

Returns movies list for specified year filter parameter.

https://github.com/centralbrainz-tv/php-service-tools/releases/tag/1.0.0
## Repositories
* https://github.com/centralbrainz-tv/vue-brainz - Vue project.
* https://github.com/centralbrainz-tv/php-service-tools - PHP service and supporting tools.
## License
**centralbrainz-tv/php-service-tools** is licensed under the GNU General Public License v3.0.

More information is here: https://www.gnu.org/licenses/gpl-3.0.en.html.
