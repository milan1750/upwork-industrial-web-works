function cronexecution_schedule_changed(){	
    if($('.cronexecution_schedule:checked').val() == 'custom_days'){
		$('#custom_day').prop("disabled",false);
	}else{
		$('#custom_day').val('0');
		$('#custom_day').prop("disabled",true);
	}
}

jQuery(document).ready(function($){
    /*
	$('.lp-search-btn-submit').on('click',function(e){
        e.preventDefault();
        // var occupation = encodeURIComponent($('#select').val());
        // var districtsearch = encodeURIComponent($('#district-search').val());
        
        var listing_id = $('#occupation').find(":selected").val();
        var occupation = $('#occupation').find(":selected").text();
        var guid_description_url = $('#guid_description_url').val();
        if(listing_id==''){
            alert('Please Select Occupation!');
            return false; 
        }
        // return false;
        //console.log(occupation);return false;
        jQuery.ajax({
            url: export_all_ajax_object.ajaxurl,
            type: 'get',
            data: "action=show_description_listing&listing_id=" +listing_id+'&occupation='+occupation+'&guid_description_url='+guid_description_url,
            success: function(result) {
                var results = JSON.parse(result);
                if(results.success == 1){
                    page_url    = results.link;
                    id          = results.id;
                    occupation  = results.occupation;
                    region_current_url  = region_current_url;
                    window.location.href = page_url+'?id='+id+'&occupation='+occupation+'&region_current_url='+region_current_url;
                }
            }
        });
    });   
    */
	// District Filter
    $("#district-search").on('change', function(){    // 2nd (A)
        page = 1;
        var district = $(this).find(":selected").text();
        var district_val = $(this).find(":selected").val();
        $('#district-select-id').val(district);
        jQuery('.listing_deal_content_tr').remove('');
        jQuery('#load_genesisprd_data').html('');
        jQuery('#load_genesisprd_message').html("<button type='button' class='btn-warning' id='pleasewait'>Please Wait...</button>");
        jQuery.ajax({
            url: export_all_ajax_object.ajaxurl,
            type: 'get',
            data: "action=show_district_listing&district="+district+'&district_val='+district_val,
            success: function(result) {
                var results = JSON.parse(result);
                if(results.success == 1){
                    jQuery('#listing_deal_content_body').append(results.html);
                    jQuery('#load_genesisprd_message').html("");
                }
            }
        });
    });
    
    // Reset District
    $("#reset_district_action").on('click', function(e){    // 2nd (A)
        e.preventDefault();
        location.reload();
    });
});

jQuery(document).ready(function($){
    jQuery(".genesisprd_dialog").dialog({
        modal: true,
        autoOpen: false,
        title: "Workforce Education",
        width: 300,
        height: 500,
        draggable: false
    }); 
    jQuery(".genesisprd_contact_dialog").dialog({
        modal: true,
        autoOpen: false,
        title: "Contact Form",
        width: 300,
        height: 500,
        draggable: false
    });
    // jQuery('.js-open-modal').on('click',function(){
    jQuery(document).on("click", ".js-open-modal", function () {
        jQuery('.genesisprd_dialog p.desc').html('');
        jQuery('.genesisprd_dialog').attr('title', '');
        
        var dataid = jQuery(this).attr('data-id');
        var desc = jQuery('#description-'+dataid).val();
        var ktitle = jQuery('#ktitle-'+dataid).val();
        console.log(ktitle);
        //alert(ktitle);
        if(ktitle!=''){
            jQuery('.genesisprd_dialog').attr('title', ktitle);
            jQuery('.ui-dialog-title').html(ktitle);
        }
        if(desc!=""){
            jQuery('.genesisprd_dialog p.desc').html(desc);
        }
        jQuery( ".genesisprd_dialog" ).dialog('open');
    })
    // Enquiry Form
    jQuery(document).on("click", ".contact_form", function () {
        var $this = this;
        var occupation = $($this).attr('data-occupation');
        //console.log(occupation);return false;
        var program_name = $($this).attr('data-program_name');
        var listing_id = $($this).attr('data-listing_id');
        var district = $($this).attr('data-district');

        jQuery('.frm6 input:text').eq(2).val(occupation);
        jQuery('.frm6 input:text').eq(3).val(program_name);
        jQuery('.frm6 input:text').eq(4).val(listing_id);
        jQuery('.frm6 input:text').eq(5).val(district);
        jQuery( ".genesisprd_contact_dialog" ).dialog('open');
    })
});

var limit = 10;
var start = 10;
var action = 'inactive';
function loadResults(limit,start,page) {
    var district = jQuery('#district-select-id').val();
    if(district=='' || district==undefined){
        var data = "action=show_infinite_loop_listing&limit="+limit+"&start="+start+"&page="+page;
    }else{
        var data = "action=show_infinite_loop_listing&limit="+limit+"&start="+start+"&page="+page+'&district='+district;
    }
    jQuery('#load_genesisprd_message').html("<button type='button' class='btn-warning' id='pleasewait'>Please Wait...</button>");
    //jQuery("#genesisprd_loading").show();
    jQuery.ajax({
        url: export_all_ajax_object.ajaxurl,
        type: 'get',
        data: data,
        success: function(data) {
            var result = JSON.parse(data);
            //jQuery("#genesisprd_loading").hide();     
            jQuery('#listing_deal_content_body').append(result.html);
            jQuery('#no_results').remove();
            jQuery('#load_genesisprd_message').html("");
            if(page==undefined){

            }else if(result.html ==''){
                action ='active';
            }else if(result.success == 1){
                jQuery('#load_genesisprd_message').show();
                jQuery('#load_genesisprd_message').html("<button type='button' class='btn-warning' id='pleasewait'>Please Wait...</button>");
                // setTimeout(function(){
                    
                    // },2000);
                    
                    action ='inactive';
            }else if(result.success == 0){
                jQuery('#load_genesisprd_message').show();
                jQuery('#load_genesisprd_message').html("<button type='button' class='btn-info is_visible' id='no_more_records'>No More Records!</button>");
            }else{
            }
            action ='inactive';
        }
    });
};
if(action == 'inactive'){
    action = 'active';
    loadResults(limit,start,2);
}

jQuery(window).scroll(function($) {
	if( 'undefined' === typeof( page ) ) {
        page = 2;
    }
    if(jQuery(window).scrollTop() + jQuery(window).height() > jQuery("#load_genesisprd_data").height() && action == 'inactive'){
        action = 'active';
        start = start + limit;
        console.log(jQuery('#no_more_records').hasClass('is_visible'));
        // if(jQuery('#no_more_records').hasClass('is_visible')==true){
        //     console.log('no more records');
        // }else{
            
            setTimeout(function(){
                if( jQuery( document ).find( 'div#load_genesisprd_message button#no_more_records').length ===  0 ) {
                    loadResults(limit,start,page);
                }
            },1000);
        // }
        page++;
    }
});