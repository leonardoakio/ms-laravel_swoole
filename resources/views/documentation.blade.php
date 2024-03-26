<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>API Documentation</title>

    <!-- css -->
    <link rel="stylesheet" type="text/css" href="/swagger/css/swagger-ui.css" />
    <link rel="icon"type="image/png" href="/swagger/favicon-32x32.png" sizes="32x32" />
    <link rel="icon"type="image/png" href="/swagger/favicon-16x16.png" sizes="16x16" />

    <!-- Styles -->
    <style>
        html
        {
            box-sizing: border-box;
            overflow: -moz-scrollbars-vertical;
            overflow-y: scroll;
        }

        *,
        *:before,
        *:after
        {
            box-sizing: inherit;
        }

        body
        {
            margin:0;
            backgroud: #fafafa;
        }
    </style>
</head>

<body>
<div id="swagger-ui"></div>

<script src="/swagger/js/swagger-ui-bundle.js" charset="UTF-8"> </script>
<script src="/swagger/js/swagger-ui-standalone-preset.js" charset="UTF-8"> </script>

<script>
    window.onload = function() {
        // Begin Swagger UI call region
        const ui = SwaggerUIBundle({
            urls: [
                {url: "/api/documentation/v1.yaml", name: "api/v1"},
                {url: "/api/documentation/v2.yaml", name: "api/v2"},
            ],
            "urls.primaryName": "api/v1",
            dom_id: '#swagger-ui',
            deepLinking: true,
            presets: [
                SwaggerUIBundle.presets.apis,
                SwaggerUIStandalonePreset
            ],
            plugins: [
                SwaggerUIBundle.plugins.DownloadUrl
            ],
            layout: "StandaloneLayout",
        });
        // End Swagger UI call region

        window.ui = ui;
    }
</script>
</body>
</html>
