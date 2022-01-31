"use strict";

jQuery(function () {
  $ = jQuery;

  document.getElementById("search-icon").addEventListener("click", function () {
    const srch = document.getElementById("autocomplete");
    if (srch) {
      srch.value = "";
      srch.focus();
    }
  });

  // autocomplete.js
  var xhr;
  new autoComplete({
    selector: "#autocomplete",
    cache: false,
    threshhold: 3,
    source: function (term, response) {
      console.log("Searching: " + term);
      try {
        xhr.abort();
      } catch (e) {
        console.log(e);
      }
      var ajaxdata = { action: "elastic_search", term: term };
      // xhr = $.post(wpAjaxUrl, ajaxdata, function (data) {
      xhr = $.post(
        "https://today.stage.williams.edu/elasticproxy/index.php",
        ajaxdata,
        function (data) {
          // console.log(data);
          var returned = JSON.parse(JSON.parse(data));
          console.log(returned.hits);
          let hits = returned.hits.hits;
          var result = hits.map(function (a) {
            return { url: a._source.permalink, title: a._source.post_title };
          });

          response(result);
        }
      );
    },

    renderItem: function (item, search) {
      return `<a href="${item.url}" class="autocomplete-suggestion" data-url="${item.url}">${item.title}</a>`;
    },
    onSelect: function (e, term, item) {
      window.location.href = item.getAttribute("data-url");
    },
  });
});
