<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Kurisu Warwalking</title>

    <link rel="stylesheet" href="https://unpkg.com/98.css">
    <link href="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.3.1/css/ol.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/kurisu.css">
</head>
<body>

    <div id="desktop">
        <div class="desktop-icon" ondblclick="openWindow('id1', wifiWindowScript)">
            <img src="/icons/channels-4.png" alt="WiFi">
            <p>WiFi List</p>
        </div>
        <div class="desktop-icon" ondblclick="openWindow('id4', gpsWindowScript)">
            <img src="/icons/gps-1.png" alt="GPS">
            <p>GPS Map</p>
        </div>
        <div class="desktop-icon" ondblclick="openWindow('id2', diagWindowScript)">
            <img src="/icons/computer_taskmgr-0.png" alt="Diagnostics">
            <p>Diagnostics</p>
        </div>
        <div class="desktop-icon" ondblclick="openWindow('id5', settingsWindowScript)">
            <img src="/icons/directory_control_panel-4.png" alt="Settings">
            <p>Settings</p>
        </div>
        <div class="desktop-icon" ondblclick="openWindow('id6', exportsWindowScript)">
            <img src="/icons/directory_closed-3.png" alt="Exports">
            <p>Exports</p>
        </div>
    </div>

    <div class="window" style="width: 800px;" id="id1" hidden>
        <div class="title-bar" id="id1header">
            <div class="title-bar-text"><img src="/icons/channels-5.png" alt="WiFi list"> WiFi List</div>
            <div class="title-bar-controls">
                <button aria-label="Close" class="window-close-btn"></button>
            </div>
        </div>
        <div class="window-body">
            <div class="row">
                <div class="column">
                    <ul class="tree-view" style="height: 300px;overflow-y: auto;" id="wifilist-treeview"></ul>
                </div>
                <div class="column">
                    <div style="padding-left:10px">
                        <fieldset>
                            <legend>Encryption filter</legend>
                            <div class="field-row">
                                <input id="wifi-rb-all" type="radio" class="wifilist-filter" name="wifi-filter" value="0" checked>
                                <label for="wifi-rb-all">All</label>
                            </div>
                            <div class="field-row">
                                <input id="wifi-rb-enc" type="radio" class="wifilist-filter" name="wifi-filter" value="1">
                                <label for="wifi-rb-enc">Only encrypted</label>
                            </div>
                            <div class="field-row">
                                <input id="wifi-rb-open" type="radio" class="wifilist-filter" name="wifi-filter" value="2">
                                <label for="wifi-rb-open">Only open</label>
                            </div>
                        </fieldset>

                        <fieldset>
                            <legend>SSID filter</legend>
                            <div class="field-row-stacked">
                                <input id="wifi-ssid-filter" type="text" />
                            </div>
                            <div class="field-row-stacked">
                                <button onclick="wifiWindowScript()">Refresh</button>
                            </div>
                        </fieldset>

                        <fieldset>
                            <legend>Details</legend>
                            <div class="field-row">
                                <span>BSSID: </span><span id="wifilist-details-bssid"></span>
                            </div>
                            <div class="field-row">
                                <span>SSID: </span><span id="wifilist-details-ssid"></span>
                            </div>
                            <div class="field-row">
                                <span>Encryption: </span><span id="wifilist-details-enc"></span>
                            </div>
                            <div class="field-row">
                                <span>GPS Latitude: </span><span id="wifilist-details-lat"></span>
                            </div>
                            <div class="field-row">
                                <span>GPS Longitude: </span><span id="wifilist-details-lon"></span>
                            </div>
                        </fieldset>
                        <div style="margin-top:10px">
                            <button onclick="wifiExportCSV()">Export CSV</button>
                            <button onclick="wifiExportKML()">Export KML</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="window" style="width: 300px" id="id2" hidden>
        <div class="title-bar" id="id2header">
            <div class="title-bar-text"><img src="/icons/computer_taskmgr-1.png" alt="Diagnostics"> Diagnostics</div>
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

    <div class="window" style="width: 600px;" id="id4" hidden>
        <div class="title-bar" id="id4header">
            <div class="title-bar-text"><img src="/icons/gps-0.png" alt="GPS"> GPS map</div>
            <div class="title-bar-controls">
                <button aria-label="Close" class="window-close-btn"></button>
            </div>
        </div>
        <div class="window-body">
            <div class="map-wrapper" style="width: 100%;height:500px;"></div>
        </div>
        <div class="status-bar">
            <p class="status-bar-field">Map by OpenStreetMap</p>
        </div>
    </div>

    <div class="window" style="width: 300px;" id="id5" hidden>
        <div class="title-bar" id="id5header">
            <div class="title-bar-text"><img src="/icons/directory_control_panel-5.png" alt="GPS"> Settings</div>
            <div class="title-bar-controls">
                <button aria-label="Close" class="window-close-btn"></button>
            </div>
        </div>
        <div class="window-body">
            <div class="field-row">
                <input checked type="checkbox" id="settings-publicip">
                <label for="settings-publicip">Get WAN IP Address from Open Networks</label>
            </div>
            <div class="field-row">
                <input checked type="checkbox" id="settings-autosync">
                <label for="settings-autosync">Enable Autosync</label>
            </div>
            <div class="field-row-stacked">
                <label for="settings-autosync-interval">Autosync Interval</label>
                <select id="settings-autosync-interval">
                    <option value="0">Every 5 seconds</option>
                    <option value="12">Every minute</option>
                    <option value="60">Every 5 minutes</option>
                    <option value="120">Every 10 minutes</option>
                </select>
            </div>
            <div class="field-row">
                <button onclick="saveSettings()">Save</button>
            </div>
        </div>
    </div>

    <div class="window" id="id7" hidden>
        <div class="title-bar" id="id7header">
            <div class="title-bar-text"><img src="/icons/notepad_file-1.png" alt="Notepad"> Notepad</div>
            <div class="title-bar-controls">
                <button aria-label="Close" class="window-close-btn"></button>
            </div>
        </div>
        <div class="window-body">
            <textarea id="notepad-textarea" cols="60" rows="20"></textarea>
        </div>
    </div>

    <div class="window window-msgbox" style="width: 300px;" id="idInfo" hidden>
        <div class="title-bar" id="idInfoheader">
            <div class="title-bar-text">Information</div>
            <div class="title-bar-controls">
                <button aria-label="Close" class="window-close-btn"></button>
            </div>
        </div>
        <div class="window-body">
            <img src="/icons/msg_information-0.png" alt="Information">
            <p>Settings saved. Don't forget to sync!</p>
            <button onclick="closeWindow('idInfo')">OK</button>
        </div>
    </div>

    <div class="window" id="id6" hidden>
        <div class="title-bar" id="id6header">
            <div class="title-bar-text"><img src="/icons/directory_closed-1.png" alt="GPS"> Exports</div>
            <div class="title-bar-controls">
                <button aria-label="Close" class="window-close-btn"></button>
            </div>
        </div>
        <div class="window-body">
            <div class="explorer" id="exports-explorer" style="width: 700px; height: 300px">
                <div class="desktop-icon" ondblclick="openWindow('id7', exportsReadmeWindow)">
                    <img src="/icons/notepad_file-0.png" alt="Readme">
                    <p>README.txt</p>
                </div>
            </div>
        </div>
    </div>

    <audio src="/audio/ding.mp3" id="win-sound-ding"></audio>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.3.1/build/ol.js"></script>

    <script>

        var encTypes = ['Open', 'Unknown (1)', 'WPA TKIP', 'Unknown (3)', 'WPA CCMP', 'WEP', 'Unknown (6)', 'Open', 'Auto'];

        $(document).ready(function(){
            if (localStorage.getItem("filesystem") == null)
            {
                localStorage.setItem("filesystem", JSON.stringify([{
                    filename: "README.txt",
                    data: "When you make a CSV or KML export, the file contents are saved tou your browser's local storage.\n" +
                        "You can re-download these from here."
                }]));
            }

            $(".window-close-btn").on('click', function (){
                $(this).closest('.window').prop('hidden', true);
            })
            $(".window").on('mousedown', function(){
                $(".window").css('z-index', 1);
                $(this).css('z-index', 2);
            })
            $(".window").each(function (i){
                dragElement($(this)[0]);
            })
            $("#wifilist-treeview").on('click', '.wifilist-wifi-item', function(){
                let thisItem = $(this);
                $("#wifilist-details-bssid").html(thisItem.data('bssid'));
                $("#wifilist-details-ssid").html(thisItem.data('ssid'));
                $("#wifilist-details-enc").html(encTypes[thisItem.data('enc')]);
                $("#wifilist-details-lat").html(thisItem.data('lat'));
                $("#wifilist-details-lon").html(thisItem.data('lon'));
            })
            $(".wifilist-filter").on("click", function(){
                wifiWindowScript();
            });
            $("#exports-explorer").on('dblclick', ".explorer-icon", function(){
                let thisIcon = $(this);
                switch (thisIcon.data('action'))
                {
                    case "notepad":
                        openWindow('id7');
                        $("#notepad-textarea").val(getFileContents(thisIcon.data('filename')));
                        break;

                    case "download":
                        download(thisIcon.data('filename'), getFileContents(thisIcon.data('filename')), true);
                        break;
                }
            });
        })

        function openWindow(windowId, customScript = null)
        {
            document.body.style.cursor = "wait";
            setTimeout(function(){
                if (customScript) {
                    customScript();
                }

                let left = ($(window).width() / 2) - ($("#" + windowId).width() / 2);
                let top = ($(window).height() / 2) - ($("#" + windowId).height() / 2);
                $(".window").css('z-index', 1);
                $("#" + windowId)
                    .css('left', left)
                    .css('top', top)
                    .css('z-index', 2);;

                document.getElementById(windowId).hidden = false;
                document.body.style.cursor = "auto";
            }, 200);
        }

        function closeWindow(windowId)
        {
            document.getElementById(windowId).hidden = true;
        }

        function playDing()
        {
            document.getElementById("win-sound-ding").play();
        }

        function getFileContents(filename)
        {
            let data = localStorage.getItem('filesystem');
            if (data == null) {
                return "";
            }

            let contents = null;

            JSON.parse(data).forEach(function(file){
                if (file.filename == filename)
                {
                    contents = file.data;
                }
            })

            return contents;
        }

        function exportsWindowScript()
        {
            let explorer = $("#exports-explorer");
            explorer.empty();
            let data = localStorage.getItem('filesystem');
            if (data != null)
            {
                JSON.parse(data).forEach(function(file){
                    let icon = "file_windows-0.png";
                    let action = "download";

                    if (file.filename.endsWith(".csv")) {
                        icon = "excel.png";
                    }
                    else if (file.filename.endsWith(".txt")) {
                        icon = "notepad_file-0.png";
                        action = "notepad";
                    }

                    explorer.append(`<div class="desktop-icon explorer-icon" data-action="${action}" data-filename="${file.filename}">
                                        <img src="/icons/${icon}" alt="${action}">
                                        <p>${file.filename}</p>
                                    </div>`);
                })
            }
        }

        function exportsReadmeWindow()
        {
            $("#notepad-textarea")
                .val("When you make a CSV or KML export, the file contents are saved tou your browser's local storage.\n" +
                    "You can re-download these from here.");
        }

        function settingsWindowScript()
        {
            $.ajax({
                url: '/api/config-get',
                method: 'GET',
                success: function (data)
                {
                    $("#settings-publicip").prop('checked', data.publicip);
                    $("#settings-autosync").prop('checked', data.autosync);
                    $("#settings-autosync-interval").val(data.autosync_interval);
                }
            })
        }

        function saveSettings()
        {
            let publicip = $("#settings-publicip").prop('checked') ? 1 : 0;
            let autosync = $("#settings-autosync").prop('checked') ? 1 : 0;
            let autosync_interval = $("#settings-autosync-interval").val();

            $.ajax({
                url: '/api/config-save',
                method: 'POST',
                data: {
                    publicip,
                    autosync,
                    autosync_interval
                },
                success: function (data)
                {
                    closeWindow('id5')
                    openWindow("idInfo", playDing);
                }
            })
        }

        function gpsWindowScript()
        {
            $("#id4 .window-body").html(`<div class="map-wrapper" style="width: 100%;height:500px;"></div>`);
            setTimeout(function (){
                var baseMapLayer = new ol.layer.Tile({
                    source: new ol.source.OSM()
                });
                let map = new ol.Map({
                    target: $(".map-wrapper")[0],
                    layers: [ baseMapLayer ],
                    view: new ol.View({
                        center: ol.proj.fromLonLat([19.679,46.904]),
                        zoom: 6.5
                    }),
                    controls : ol.control.defaults({
                        attribution : false,
                        zoom : false,
                    }),
                });

                var features = [];

                $.ajax({
                    url: '/api/wifi-list',
                    method: 'GET',
                    success: function (data)
                    {
                        data.forEach(function(row){
                            let marker = new ol.Feature({
                                geometry: new ol.geom.Point(
                                    ol.proj.fromLonLat([row.longitude,row.latitude])
                                ), function(feature){
                                    console.log('na', feature);
                                }
                            });
                            marker.setStyle([
                                new ol.style.Style({
                                    image: new ol.style.Icon(({
                                        anchor: [0.5, 1],
                                        src: "/icons/gps-0.png",
                                        scale: 1
                                    }))
                                }),
                                new ol.style.Style({
                                    text: new ol.style.Text({
                                        text: row.ssid,
                                        offsetY: 10,
                                        scale: 1.5,
                                        fill: new ol.style.Fill({
                                            color: '#fff',
                                        }),
                                        stroke: new ol.style.Stroke({
                                            color: '#000',
                                            width: 3
                                        })
                                    })
                                })
                            ]);
                            features.push(marker);
                        })

                        let vectorSource = new ol.source.Vector({
                            features: features
                        });
                        let markerVectorLayer = new ol.layer.Vector({
                            source: vectorSource
                        });
                        map.addLayer(markerVectorLayer);
                    }
                });
            }, 1000)
        }

        function wifiExportCSV()
        {
            $.ajax({
                url: '/api/wifi-list',
                method: 'GET',
                success: function (data)
                {
                    let lines = "";
                    let firstLine = true;
                    data.forEach(function(row){
                        if (firstLine)
                        {
                            let lineData = [];
                            for (const [key, value] of Object.entries(row)) {
                                lineData.push(key);
                            }
                            lines += lineData.join(";") + "\n";
                            firstLine = false;
                        }

                        let lineData = [];
                        for (const [key, value] of Object.entries(row)) {
                            lineData.push(value);
                        }
                        lines += lineData.join(";") + "\n";
                    });
                    download("kurisu_csv_export.csv", lines);
                }
            })
        }

        function wifiExportKML()
        {
            $.ajax({
                url: '/api/wifi-list',
                method: 'GET',
                success: function (data)
                {
                    let lines = '<\?xml version="1.0" encoding="UTF-8"?><kml xmlns="http://www.opengis.net/kml/2.2"><Document>';
                    let firstLine = true;
                    data.forEach(function(row){
                        lines += `<Placemark>
                                        <name>${row.ssid}</name>
                                        <description>BSSID: ${row.bssid}</description>
                                        <Point>
                                          <coordinates>${row.longitude},${row.latitude}</coordinates>
                                        </Point>
                                      </Placemark>` + "\n";
                    });
                    lines += "</Document></kml>";
                    download("kurisu_kml_export.kml", lines);
                }
            })
        }

        function download(filename, text, redownload = false) {
            var element = document.createElement('a');
            element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
            element.setAttribute('download', filename);

            element.style.display = 'none';
            document.body.appendChild(element);

            element.click();

            document.body.removeChild(element);

            if (!redownload) {
                saveLocalStorage(filename, text);
            }
        }

        function saveLocalStorage(filename, data)
        {
            if (localStorage.getItem('filesystem') == null)
            {
                localStorage.setItem('filesystem', JSON.stringify([{
                    filename,
                    data
                }]));
            }
            else
            {
                let prevData = JSON.parse(localStorage.getItem('filesystem'));
                prevData.push({
                    filename,
                    data
                });
                localStorage.setItem('filesystem', JSON.stringify(prevData));
            }
        }

        function wifiWindowScript()
        {
            $("#wifilist-treeview").empty();
            $.ajax({
                url: '/api/wifi-list',
                method: 'GET',
                success: function (data)
                {
                    filterMode = $('input[name=wifi-filter]:checked').val();
                    ssidFilter = $("#wifi-ssid-filter").val();

                    data.forEach(function (row) {
                        let skip = false;

                        switch (filterMode)
                        {
                            case '1':
                                if (row.encryption == 7 || row.encryption == 0) { skip = true; }
                                break;

                            case '2':
                                if (row.encryption != 7 && row.encryption != 0) { skip = true; }
                                break;
                        }

                        if (ssidFilter != '' && !row.ssid.includes(ssidFilter))
                        {
                            skip = true;
                        }

                        if (!skip)
                        {
                            let icon;
                            if (row.encryption == 7 || row.encryption == 0)
                            {
                                icon = "globe_map-1.png";
                            }
                            else
                            {
                                icon = "key_padlock-1.png";
                            }
                            $("#wifilist-treeview").append(`<li><a href="#" class="wifilist-wifi-item" data-ssid="${row.ssid}" data-bssid="${row.bssid}" data-enc="${row.encryption}" data-lat="${row.latitude}" data-lon="${row.longitude}"><img src="/icons/${icon}">&nbsp;&nbsp;&nbsp;${row.bssid}&nbsp;&nbsp;&nbsp;${row.ssid}</a></li>`);
                        }
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

                    let max = 4.2;
                    let min = 2.9; // last measured voltage before the device got offline. TODO: needs more precise test
                    let range = max - min;
                    let correctedStartValue = data.lastbattery - min;
                    let percentage = (correctedStartValue * 100) / range;

                    $("#diag-last-battery-percent").val(percentage);
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