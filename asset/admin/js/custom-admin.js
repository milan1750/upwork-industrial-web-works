jQuery(document).ready(function ($) {
  $(document).on("click", ".wpscraper-run", function ( e ) {
    $el = $(this);
    e.preventDefault();

    $el.prop("disabled", true);

    // Add action intend for ajax_form_submission endpoint.
    var data = [];

    data.push({
      name: "action",
      value: "wpscraper_ajax_submission",
    });

    data.push({
      name: "wpretail_nonce",
      value: wpscraperSettingsParams.nonce,
    });

    data.push({
      name: "event",
      value: "run_crawler",
    });

    // Fire the ajax request.
    $.ajax({
      url: wpscraperSettingsParams.ajax_url,
      type: "POST",
      data: data,
    })
      .done(function (xhr, textStatus, errorThrown) {
        if (true === xhr.success) {
          console.log(xhr.data);
        } else {
          console.log(xhr.data);
        }
      })
      .always(function () {
        $el.prop("disabled", false);
      });
  });
});
