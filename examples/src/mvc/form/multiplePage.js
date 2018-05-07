

$( function() {

    /***********************************
     *
     *
     *      T A B S
     *
     *
     ***********************************/

    /************************************
     * Init
     */

    var tabsQty = 3;
    $("#tabs").tabs({"disabled": [1,2]}); // 0 = first tab



    /************************************
     * Tabs style
     */


    function tab1(){

        /* Click on first tab */
        $("#tabs").tabs({ "disabled": [1,2] });
        $("[href='#tabs-1']").trigger( "click" );


        $("#ui-id-1")
            .removeClass("tabCurrent")
            .removeClass("tabHidden")
            .removeClass("tabOk")
            .addClass("tabCurrent");

        $("#ui-id-2")
            .removeClass("tabCurrent")
            .removeClass("tabHidden")
            .removeClass("tabOk")
            .addClass("tabHidden");
    }

    function tab2() {

        /* Click on second tab */
        $("#tabs").tabs({ "disabled": [0,2] });
        $("[href='#tabs-2']").trigger( "click" );


        $("#ui-id-1")
            .removeClass("tabCurrent")
            .removeClass("tabHidden")
            .removeClass("tabOk")
            .addClass("tabOk");


        $("#ui-id-2")
            .removeClass("tabCurrent")
            .removeClass("tabHidden")
            .removeClass("tabOk")
            .addClass("tabCurrent");

        $("#ui-id-3")
            .removeClass("tabCurrent")
            .removeClass("tabHidden")
            .removeClass("tabOk")
            .addClass("tabHidden");

    }


    function tab3() {

        /* Click on third */
        $("#tabs").tabs({ "disabled": [0,1] });
        $("[href='#tabs-3']").trigger( "click" );



        $("#ui-id-2")
            .removeClass("tabCurrent")
            .removeClass("tabHidden")
            .removeClass("tabOk")
            .addClass("tabOk");


        $("#ui-id-3")
            .removeClass("tabCurrent")
            .removeClass("tabHidden")
            .removeClass("tabOk")
            .addClass("tabCurrent");

    }


    /*******************
     * Next
     */

    $( "#nextPage2" ).click(function() {
        tab2();
    });


    $( "#nextPage3" ).click(function() {
        tab3();
    });


    /*******************
     * Back
     */

    $( "#prevPage1" ).click(function() {
        tab1();
    });

    $( "#prevPage2" ).click(function() {
        tab2();
    });


} );
