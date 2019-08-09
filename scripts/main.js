$(document).ready(function () {
    //datepicker
    $(function() {
        $.datepicker.setDefaults( $.datepicker.regional[ "fr" ] );
        $("#date_arrivee").datepicker( $.datepicker.regional[ "fr" ] );
        $("#date_depart").datepicker( $.datepicker.regional[ "fr" ] );
        $("#date_rech").datepicker( $.datepicker.regional[ "fr" ] );
    });

    // $("#myTopnav a").click(function () {
    //     var page = $(this).attr("href");
    //     $.ajax({
    //         url: page,
    //         cache:false,
    //         success:function (html) {
    //             afficher(html);
    //         },
    //         error:function(XMLHttpRequest, textStatus, errorThrown){
    //             alert(textStatus);
    //         }
    //
    //     });
    //     return false;
    // });

});

// function afficher(data) {
//  $("section").fadeOut(500,function () {
//         $("section").empty();
//         $("section").append(data);
//         $("section").fadeIn(1000);
//     })
// }