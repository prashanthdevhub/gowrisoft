/*global $, document, window
 */
$(document).ready(function ($) {
    'use strict';

    /*-------------------------------------
    Navbar Toggle for Mobile
    -------------------------------------*/
    function navbarCollapse() {
        if ($(window).width() < 992) {
            $(document).on('click', function (event) {
                var clickover = $(event.target);
                var _opened = $("#navbar-collapse").hasClass("in");
                if (_opened === true && !(clickover.is('.dropdown'))) {
                    $("button.navbar-toggle").trigger('click');
                }
            });

            $('.dropdown').unbind('click');
            $('.dropdown').on('click', function () {
                $(this).children('.dropdown-menu').slideToggle();
            });

            $('.dropdown *').on('click', function (e) {
                e.stopPropagation();
            });
        }
    }
    navbarCollapse();

    /*-------------------------------------
    Language Toggle
    -------------------------------------*/
    function languageTogg() {
        if ($('#languageToggle').length > 0) {
            $('#languageToggle').on('click', function () {
                $('#language-select').slideToggle();
            });

            $(document).on('click', function (event) {
                var clickover = $(event.target);
                var _opened = $("#language-select").is(':visible');
                if (_opened === true && !(clickover.is('#languageToggle'))) {
                    $("#languageToggle").trigger('click');
                }
            });

            $("#languageToggle *").on('click', function (e) {
                e.stopPropagation();
            });
        }
    }

    languageTogg();

    
    if ($('#banner-slider').length > 0) {
        $('#banner-slider').owlCarousel({
            singleItem: true,
            slideSpeed: 200,
            stopOnHover: true,
            autoPlay: 3000,
            navigation:true,
            navigationText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
            pagination: false,
        });
    }

    
    /*-----------------------------------
    Contact Form
    -----------------------------------*/
    // Function for email address validation
    function isValidEmail(emailAddress) {
        var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);

        return pattern.test(emailAddress);

    }
    $("#contactForm").on('submit', function (e) {
        e.preventDefault();
        var data = {
            name: $(".name").val(),
            email: $(".email").val(),
            phone: $(".phone").val(),
            message: $(".message").val(),
            subject: $("#subject").val(),
            company: $(".company").val()
        };

        if($("#termsCheck").prop("checked") == true){
            if (isValidEmail(data['email']) && (data['message'].length > 1) && (data['name'].length > 1) && (data['phone'].length > 1)) {
                $.ajax({
                    type: "POST",
                    url: "sendmail.php",
                    data: data,
                    success: function () {
                        $('#contactForm .input-success').delay(500).fadeIn(1000);
                        $('#contactForm .input-error').fadeOut(500);
                    }
                });
            } else {
                $('#contactForm .input-error').delay(500).fadeIn(1000);
                $('#contactForm .input-success').fadeOut(500);
            }
        }else{
            $("#termsError").show();
        }

        return false;
    });

    $("#footerContactForm").on('submit', function (e) {
        e.preventDefault();
        var data = {
            name: $("#name").val(),
            email: $("#email").val(),
            phone: $("#phone").val(),
            message: $("#message").val(),
            company: $("#company").val()
        };

        if (isValidEmail(data['email']) && (data['message'].length > 1) && (data['name'].length > 1) && (data['phone'].length > 1)) {
            $.ajax({
                type: "POST",
                url: "sendmail.php",
                data: data,
                success: function () {
                    $('#footerContactForm .input-success').delay(500).fadeIn(1000);
                    $('#footerContactForm .input-error').fadeOut(500);
                }
            });
        } else {
            $('#footerContactForm .input-error').delay(500).fadeIn(1000);
            $('#footerContactForm .input-success').fadeOut(500);
        }

        return false;
    });

    $('.careerSubmitbtn').on('click', function () {
        $('.careerSubmitbtn').attr('disabled', "disabled");
        var name = $("#profName").val();
        var email = $("#profemail").val();
        var phone = $("#profphone").val();
        var msg = $("#profmessage").val();
        var file_data = $('.resumeUpload').prop('files')[0];
        var ext = "";
        if (file_data != null || file_data != undefined) {
            var revFname = $('.resumeUpload').prop('files')[0].name.split(".").reverse().join(".");
            ext = revFname.split(".")[0];
        }
        var form_data = new FormData();
        form_data.append('file', file_data);
        if (file_data != null || file_data != undefined) {
            $.ajax({
                url: 'sendCareermail.php?name=' + name + '&email=' + email + '&phone=' + phone + '&ext=' + ext + '&msg=' + msg,
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function (response) {
                    var res = JSON.parse(response);
                    if(res.res == "success"){
                        $(".careerMsgSuccess").show();
                    }else{
                        $(".careerMsgFailure").show();
                    }
                    
                }
            });
        } else{
            $(".careerMsgFailure").show();
            $('.careerSubmitbtn').removeAttr('disabled');
        }
    });
    
});
