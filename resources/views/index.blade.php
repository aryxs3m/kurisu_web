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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.3.1/build/ol.js"></script>

    <script>

        var encTypes = ['Open', 'Unknown (1)', 'WPA TKIP', 'Unknown (3)', 'WPA CCMP', 'WEP', 'Unknown (6)', 'Open', 'Auto'];

        $(document).ready(function(){
            $(".window-close-btn").on('click', function (){
                $(this).closest('.window').prop('hidden', true);
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