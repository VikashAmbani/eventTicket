<head>
    <title><?= $title ?></title>
</head>
<?php
echo '&#8377; &#x20b9; Testing :' . ($name);
?>
<body>
    <script>



        window.fbAsyncInit = function () {
            FB.init({
                appId: '{API-KEY}',
                xfbml: true,
                version: 'v2.5'
            });
            FB.getLoginStatus(function (response) {
                if (response.status === 'connected') {
                    document.getElementById('status').innerHTML = 'we are connected';
                } else if (response.status === 'not_authorized') {
                    document.getElementById('status').innerHTML = 'we are not logged in.'
                } else {
                    document.getElementById('status').innerHTML = 'you are not logged in to Facebook';
                }
            });
            // FB.AppEvents.logPageView();
        };

        (function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {
                return;
            }
            js = d.createElement(s);
            js.id = id;
            js.src = "//connect.facebook.net/en_US/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));

        function login() {
            FB.login(function (response) {
                if (response.status === 'connected') {
                    document.getElementById('status').innerHTML = 'we are connected';
                } else if (response.status === 'not_authorized') {
                    document.getElementById('status').innerHTML = 'we are not logged in.'
                } else {
                    document.getElementById('status').innerHTML = 'you are not logged in to Facebook';
                }

            }, {scope: 'read_custom_friendlists,basic_info,user_friends,public_profile,email,user_likes'});
        }
        // get user basic info

        function getInfo() {
            FB.api('/me', 'GET', {fields: 'email,first_name,last_name,name,id'}, function (response) {
                console.log(response);
                document.getElementById('status').innerHTML = response.id;
                var img = {1: 'large', 2: 'normal', 3: 'small', 4: 'square'};
                for (var i in img) {
                    console.log(i, img[i]);
                    document.getElementById('img' + i).src = 'https://graph.facebook.com/' + response.id + '/picture?type=' + img[i];
                }
                getFrdlist();
            });
            FB.api('/me?fields=friends{first_name,birthday}',
                    'POST', {},
                    function (response) {
                        console.log('posopst', response);
                    }
            );
        }
        function getFrdlist() {
            var ids = document.getElementById('status').innerHTML;
            FB.api('/' + ids + '/friends', 'GET', {fields: 'name,id'}, function (response) {
                console.log(response);
            });
            FB.api('/me', 'GET', {},
                    function (response) {
                        console.log(response);
                    }
            );
            FB.api('/' + ids + '/friendlists', 'GET', {fields: 'name,id'}, function (response) {
                console.log(response);

            });
        }
//        function getFrdlist() {
//            var ids=document.getElementById('status').innerHTML;
//            FB.api('/'+ids+'/friendlists', 'GET', {fields: 'name,id'}, function (response) {
//                console.log(response);
//                
//            });
//        }
        function logout() {
            FB.logout(function (response) {
                document.location.reload();
            });
        }

    </script>
    <div id="status"></div>
    <img id="img1">
    <img id="img2">
    <img id="img3">
    <img id="img4">
    <button onclick="getInfo()">Get Info</button> 
    <button onclick="login()">login</button>
    <button onclick="logout()">logout</button>
    <div id="content">
        This is the content
    </div>
    <button type="button" id="addContent">Add Content</button>
    <script>
        function processAjaxData(response, urlPath) {
            document.getElementById("content").innerHTML = response.html;
            document.title = response.pageTitle;
            window.history.pushState({"html": response.html, "pageTitle": response.pageTitle}, "", urlPath);
        }
        window.onpopstate = function (e) {
            if (e.state) {
                document.getElementById("content").innerHTML = e.state.html;
                document.title = e.state.pageTitle;
            }
        };
        /* DOM content change detect */
        window.Virus = {
            starts: function () {
                 
                if (typeof document.body.addEventListener != 'undefined') {
                   document.body.addEventListener('DOMSubtreeModified', function () {
                        console.log('Content Cahnge on virus', new Date());
                    }, false);
                } else if (typeof document.body.attachEvent != 'undefined') {
                    document.body.attachEvent('DOMSubtreeModified', function () {
                        document.title = 'Changed at ' + new Date();
                        console.log('Content Cahnge on virus', new Date());
                    }, false);
                }
                var customEvent = new Event("infected");
                var content = document.getElementById('content');
                var btn = document.getElementById('addContent');
                btn.addEventListener('click', function () {
                    content.innerHTML ='click on blog add contemt button';
                    content.dispatchEvent(customEvent);
                });
                content.addEventListener('infected', function () {
                    console.log('Virus infected')
                });
            }
        };
        Virus.starts();
        MutationObserver = window.MutationObserver || window.WebKitMutationObserver;

        var observer = new MutationObserver(function (mutations, observer) {
            console.log(mutations, observer);
        });

        observer.observe(document, {
            subtree: true,
            attributes: true
        });
    </script>
</body>
