var Trestian_WPM;

jQuery(document).ready(function($) {
    var genericError = function(msg){
        var error = 'An error has occurred.  Please <a href="/contact">contact support</a>.';
        if(msg){
            error += ' Error details: ' + msg;
        }

        return error;
    };

    var hideAlerts = function(){
      $('.twpm-alerts').text('').hide();
    };

    var disableFields = function(form){
      form.find('input, button').prop('disabled', true);
    };

    var enableFields = function(form){
        form.find('input, button').prop('disabled', false);
    };

    var showLoading = function(){
        $('body').append('<div class="twpm-loading"></div>');
    };

    var hideLoading = function(){
        $('.twpm-loading').hide();
    };

    var success = function(response){
        if(!response.hasOwnProperty('success')){
            $('#twpm-alert-error').html(genericError('No success in AJAX response')).fadeIn();
            return false;
        }
        if(!response.success){
            // Operation was not successful.  Display error message or default
            msg = response.message ? response.message : genericError('No error message returned');
            $('#twpm-alert-error').html(msg).fadeIn();
            return false;
        }

        // Display any response messages
        if(response.message){
            $('#twpm-alert-success').html(response.message).fadeIn();
        }

        // Redirect to page
        if(response.redirect){
            location.href = response.redirect;
        }

        return true;
    };

    var beforeSubmit = function(form){
        hideAlerts();
        disableFields(form);
        showLoading();
    };

    var complete = function(form){
        enableFields(form);
        hideLoading();
    };

    var error = function(errorThrown){
        $('#twpm-alert-error').text('An error has occurred.  Please contact support.  Error details: "' + errorThrown + '"').fadeIn();
    };

    var ajaxForm = function(form){
        form.prepend('<div id="twpm-alert-success" class="twpm-alert twpm-alert-success"></div><div id="twpm-alert-error" class="twpm-alert twpm-alert-error"></div>');

        form.ajaxForm({
            url: ajaxurl,
            type: 'POST',
            beforeSubmit: function(arr, f, options){
                beforeSubmit(form);
            },
            clearForm: false, // clear form after posting
            dataType: 'json',
            complete: function(){
                complete(form);
            },
            error: function(xrh, textStatus, errorThrown){
                error(errorThrown);
            },
            success: function(response ){
                success(response);
            }
        });
    };


    var ajax = {
        beforeSubmit: beforeSubmit,
        complete: complete,
        error: error,
        hideAlerts: hideAlerts,
        showLoading: showLoading,
        hideLoading: hideLoading,
        success: success,
        genericError: genericError,
        ajaxForm: ajaxForm
    };

    Trestian_WPM = {
        ajax: ajax
    };
});
