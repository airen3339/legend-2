<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
       Remove this if you use the .htaccess -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="initial-scale=1.0, target-densitydpi=device-dpi" /><!-- this is for mobile (Android) Chrome -->
    <meta name="viewport" content="initial-scale=1.0, width=device-height"><!--  mobile Safari, FireFox, Opera Mobile  -->
    <script type="text/javascript" src="/jSignature/libs/flashcanvas.js"></script>
    <style type="text/css">

        div {
            margin-top:1em;
            margin-bottom:1em;
        }
        input {
            padding: .5em;
            margin: .5em;
        }
        select {
            padding: .5em;
            margin: .5em;
        }

        #signatureparent {
            color:darkblue;
            background-color:darkgrey;
            /*max-width:600px;*/
            padding:20px;
        }

        /*This is the div within which the signature canvas is fitted*/
        #signature {
            border: 2px dotted black;
            background-color:lightgrey;
        }

        /* Drawing the 'gripper' for touch-enabled devices */
        html.touch #content {
            float:left;
            width:92%;
        }
        html.touch #scrollgrabber {
            float:right;
            width:4%;
            margin-right:2%;
            background-image:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAAFCAAAAACh79lDAAAAAXNSR0IArs4c6QAAABJJREFUCB1jmMmQxjCT4T/DfwAPLgOXlrt3IwAAAABJRU5ErkJggg==)
        }
        html.borderradius #scrollgrabber {
            border-radius: 1em;
        }

    </style>
</head>
<body>
<div>
    <div id="content">
        <div id="signatureparent">
            <div style="height: 50px;width: 200px;" id="signature"></div>
        </div>
        <div id="tools"></div>
        <input id="rest" type="button" value="Reset">
        <input id="btnSave" type="button" value="save">
        <div><p>Display Area:</p><div id="displayarea"></div></div>
    </div>
    <script src="/js/jquery-1.7.2.js"></script>
    <script src="/jSignature/libs/jSignature.min.noconflict.js"></script>
    <script>
        (function($){

            $(document).ready(function() {

                // This is the part where jSignature is initialized.
                var $sigdiv = $("#signature").jSignature({'UndoButton':false})

                // All the code below is just code driving the demo.
                    , $tools = $('#tools')
                    , $extraarea = $('#displayarea')
                    , pubsubprefix = 'jSignature.demo.'


                $('#rest').bind('click', function(e){
                    $sigdiv.jSignature('reset')
                }).appendTo($tools)
                $("#btnSave").on("click",function(){
                    //可选格式：native,image,base30,image/jsignature;base30,svg,image/svg+xml,svgbase64,image/svg+xml;base64
                    var basedata = "data:"+$sigdiv.jSignature('getData', "image");
//						console.log("basedata:"+basedata);
//						$(".signResult").html(data);
                    //server是我自定义的，值为http://www.abc.com/index.php?g=user&m=jk
                    //这样就能拼接完整的请求路径了。
                    var weburl='http://words.cc/tests/create-image';
                    var jsdata={imgbase:basedata};
                    $.ajax({
                        type:"post",
                        url:weburl,
                        async:true,
                        data:jsdata,
                        dataType:"json",
                        success:function(data){
                            console.log(JSON.stringify(data));
                            if(data.success){
                                console.log(data.msg);
                            }
                            else{
                                console.log(data.msg);
                            }
                        }
                    });
                });

            })
        })(jQuery)
    </script>
</body>
</html>
