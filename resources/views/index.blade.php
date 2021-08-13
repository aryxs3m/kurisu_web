<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Kurisu Warwalking</title>

    <link rel="stylesheet" href="https://unpkg.com/98.css">
    <link rel="stylesheet" href="/css/kurisu.css">
</head>
<body>

    <div id="desktop">
        <div class="desktop-icon" ondblclick="openWindow('id1', wifiWindowScript)">
            <img src="/icons/channels-4.png" alt="WiFi">
            <p>WiFi List</p>
        </div>
        <div class="desktop-icon" ondblclick="openWindow('id2', diagWindowScript)">
            <img src="/icons/computer_taskmgr-0.png" alt="Diagnostics">
            <p>Diagnostics</p>
        </div>
    </div>

    <div class="window" style="width: 300px" id="id1" hidden>
        <div class="title-bar" id="id1header">
            <div class="title-bar-text">WiFi List</div>
            <div class="title-bar-controls">
                <button aria-label="Close" class="window-close-btn"></button>
            </div>
        </div>
        <div class="window-body">
            <ul class="tree-view" id="wifilist-treeview"></ul>
        </div>
    </div>

    <div class="window" style="width: 300px" id="id2" hidden>
        <div class="title-bar" id="id2header">
            <div class="title-bar-text">Diagnostics</div>
            <div class="title-bar-controls">
                <button aria-label="Close" class="window-close-btn"></button>
            </div>
        </div>
        <div class="window-body">
            <p>Kurisu Device Diagnostics</p>
            <hr>
            <p>Last sync: <span id="diag-last-sync"></span></p>
            <p>Last battery level: <span id="diag-last-battery"></span></p>
            <progress max="100" value="0" id="diag-last-battery-percent" style="width: 100%"></progress>

            <div style="margin-top:10px">
                <button onclick="diagWindowScript()">Refresh</button>
                <button onclick="openWindow('id3', diagHistoricalWindowScript)">Historical Data</button>
            </div>
        </div>
    </div>

    <div class="window" style="width: 300px" id="id3" hidden>
        <div class="title-bar" id="id3header">
            <div class="title-bar-text">Historical Diagnostics Data</div>
            <div class="title-bar-controls">
                <button aria-label="Close" class="window-close-btn"></button>
            </div>
        </div>
        <div class="window-body">
            <ul class="tree-view" id="historical-treeview" style="height: 300px"></ul>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>

        $(document).ready(function(){
            $(".window-close-btn").on('click', function (){
                $(this).closest('.window').prop('hidden', true);
            })

            $(".window").each(function (i){
                dragElement($(this)[0]);
            })
        })

        function openWindow(windowId, customScript = null)
        {
            document.body.style.cursor = "wait";
            setTimeout(function(){
                if (customScript) {
                    customScript();
                }
                document.getElementById(windowId).hidden = false;
                document.body.style.cursor = "auto";
            }, 200);
        }

        function closeWindow(windowId)
        {
            document.getElementById(windowId).hidden = true;
        }

        function wifiWindowScript()
        {
            $("#wifilist-treeview").empty();
            $.ajax({
                url: '/api/wifi-list',
                method: 'GET',
                success: function (data)
                {
                    data.forEach(function (row) {
                        //$("#wifilist-treeview").append(`<li><img src="/icons/key_padlock-1.png"> ${row.ssid}</li>`);
                        let icon;
                        if (row.encryption == 7)
                        {
                            icon = "open.png";
                        }
                        else
                        {
                            icon = "key_padlock-1.png";
                        }
                        $("#wifilist-treeview").append(`<li><a href="#"><img src="/icons/${icon}">&nbsp;&nbsp;&nbsp;${row.bssid}&nbsp;&nbsp;&nbsp;${row.ssid}</a></li>`);
                    })
                }
            })
        }

        function diagHistoricalWindowScript()
        {
            $("#historical-treeview").empty();
            $.ajax({
                url: '/api/diag-history',
                method: 'GET',
                success: function (data)
                {
                    data.forEach(function (row) {
                        $("#historical-treeview").append(`<li>${row.created_at} ${row.battery_level} V</li>`);
                    })
                }
            })
        }

        function diagWindowScript()
        {
            $.ajax({
                url: '/api/diag-data',
                method: 'GET',
                success: function (data)
                {
                    $("#diag-last-sync").html(data.lastsync);
                    $("#diag-last-battery").html(data.lastbattery + " V");
                    $("#diag-last-battery-percent").val(data.lastbattery / 4.2 * 100);
                }
            })
        }

        function dragElement(elmnt) {
            var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
            if (document.getElementById(elmnt.id + "header")) {
                // if present, the header is where you move the DIV from:
                document.getElementById(elmnt.id + "header").onmousedown = dragMouseDown;
            } else {
                // otherwise, move the DIV from anywhere inside the DIV:
                elmnt.onmousedown = dragMouseDown;
            }

            function dragMouseDown(e) {
                e = e || window.event;
                e.preventDefault();
                // get the mouse cursor position at startup:
                pos3 = e.clientX;
                pos4 = e.clientY;
                document.onmouseup = closeDragElement;
                // call a function whenever the cursor moves:
                document.onmousemove = elementDrag;
            }

            function elementDrag(e) {
                e = e || window.event;
                e.preventDefault();
                // calculate the new cursor position:
                pos1 = pos3 - e.clientX;
                pos2 = pos4 - e.clientY;
                pos3 = e.clientX;
                pos4 = e.clientY;
                // set the element's new position:
                elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
                elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
            }

            function closeDragElement() {
                // stop moving when mouse button is released:
                document.onmouseup = null;
                document.onmousemove = null;
            }
        }
    </script>

</body>
</html>