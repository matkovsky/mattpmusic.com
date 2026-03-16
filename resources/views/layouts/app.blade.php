<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="shortcut icon" href="/img/favicon.ico">
    <link rel="apple-touch-icon-precomposed" href="/img/logo.jpg">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:image" content="/img/logo.jpg">
    <meta property="fb:app_id" content="119078858144619">
    <meta property="fb:admins" content="654857984">
    <meta name="description" content="@yield('meta_description', 'The Matt P Music Podcast — A deep, dark, underground blend of house, techno, trance, and progressive.')">
    <meta name="author" content="Peter Matkovsky | matkovsky.com">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', config('app.name'))</title>
    @vite(['resources/sass/main.scss', 'resources/js/app.js'])
</head>
<body>

    <section>

        <header id="pageHeader">
            <a href="/" class="logo"><img src="/img/logo.jpg" alt="{{ config('app.name') }}"></a>
            <section id="about" class="sideSection">
                <h3>About</h3>
                <p>The Matt P Music Podcast — A deep, dark, underground blend of house, techno, trance, and progressive. Presented by <a href="https://matkovsky.com">Peter Matkovsky</a>.</p>
            </section>
        </header>

        <section id="subscriptions" class="sideSection">
            <h3>Subscribe</h3>
            <a href="https://podcast.mattpmusic.com/feed/enhanced" class="btnWthIcon enhanced"><strong>Apple Podcasts</strong> Listen to the Matt P Music Podcast on your Apple device.</a>
            <a href="https://podcast.mattpmusic.com/feed/standard" class="btnWthIcon mp3"><strong>Direct RSS Feed</strong> Subscribe through the RSS feed in your podcast application of choice.</a>
        </section>

        @yield('content')

        <section id="extras" class="sideSection">
            <h3>Extra</h3>
            <a href="https://open.spotify.com/user/11124146557/playlist/5NMAKTa9zbeNeyu3HqtjmZ" class="btnWthIcon spotify"><strong>Spotify</strong> Follow our exclusive playlist of full length tracks from the podcast.</a>
            <a href="https://soundcloud.com/matkovsky/tracks" class="btnWthIcon soundcloud"><strong>SoundCloud</strong> Listen to exclusive Matt P edits and mashups featured in this podcast.</a>
            <a href="https://podcast.mattpmusic.com/feed/tracklist" class="btnWthIcon tracklist"><strong>Tracklist feed</strong> Subscribe for the tracklist of this podcast in your RSS feed reader.</a>
        </section>

        <section id="contact" class="sideSection">
            <h3>Contact</h3>
            <p>To contact Peter regarding the podcast, or to send him your own tracks to feature in this podcast, use this email address:</p>
            <a href="mailto:&#109;&#097;&#116;&#116;&#112;&#064;&#109;&#097;&#116;&#116;&#112;&#109;&#117;&#115;&#105;&#099;&#046;&#099;&#111;&#109;" class="btn email">&#109;&#097;&#116;&#116;&#112;&#064;&#109;&#097;&#116;&#116;&#112;&#109;&#117;&#115;&#105;&#099;&#046;&#099;&#111;&#109;</a>
        </section>

    </section>

    <footer id="pageFooter">
        <section>
            <a href="https://matkovsky.com" class="mpLogo"><b>Made by Peter Matkovsky with care.</b></a>
            <p>Copyright &copy; Peter Matkovsky.</p>
            <p class="disclaimer">All rights to published audio, video, graphic and text materials belong to their respective owners. All music featured in this podcast is for promotional purposes only. If you like the music, please support the artists by buying their music.</p>
        </section>
    </footer>

    <div id="fb-root"></div>
    <script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=119078858144619";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>

    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-8004992-14"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'UA-8004992-14');
    </script>

</body>
</html>
