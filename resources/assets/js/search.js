/**
 * Top bar Autocomplete method
 * Common for all pages
 */
autocomplete('input[name="q"]', {hint: true}, [
    {
        source: autocomplete.sources.hits(index, {hitsPerPage: 5}),
        displayKey: 'title',
        templates: {
            header: '<h5>Search Results</h5>',
            suggestion: function (suggestion) {
                return '<div class="picture"><img src="' + suggestion.img_src + '" /></div><span class="name">' + suggestion._highlightResult.title.value + '<span>';
            },
            footer: '<div class="branding">Powered by <img src="https://www.algolia.com/assets/algolia128x40.png" style="height:14px;"  /></div>'
        }
    }
]).on('autocomplete:selected', function (event, suggestion, dataset) {
    console.log(suggestion, dataset);
});