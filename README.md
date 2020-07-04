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
{"count":7,"total":119,"result":["(2011)","(2012)","(2013)","(2014)","(2016)","(2017)","(2019)"]}
```

` php-service/index/index/page/1/100/count/0: `

Default call for index.

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
