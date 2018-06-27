<!doctype html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>IndieAuth</title>
  <link rel="stylesheet" href="/assets/styles.css">
</head>
<body>

<article class="h-entry">

<div class="header-bar">
  <section>
    <h1 class="p-name">IndieAuth</h1>
  </section>
</div>

<div class="featured">
  <section>
    <p class="p-summary">IndieAuth enables you to use your domain name as your online identity</p>
  </section>
</div>

<section>
  <p>IndieAuth is a decentralized identity protocol built on top of OAuth 2.0.</p>

</section>

<section class="">
  <h2>Read the Spec</h2>

  <p>The latest version of the IndieAuth spec is available at:</p>

  <p><a href="https://indieauth.spec.indieweb.org/">indieauth.spec.indieweb.org</a></p>

  <p>The January 2018 published version is also available at:</p>

  <p><a href="https://www.w3.org/TR/indieauth/">w3.org/TR/indieauth</a></p>
</section>

<section class="hang-left border-top">
  <img src="/assets/web-signin-splash.jpg">

  <h2>Logging in with IndieAuth</h2>

  <p>You can use IndieAuth to have your users authenticate with their own URL. Logging in to an app with IndieAuth tells the app who has logged in, where the user ID returned is a URL controlled by the user.</p>
  <p><a href="https://indieauth.spec.indieweb.org/#authentication">Logging in with IndieAuth</a></p>
</section>

<section class="border-top">
  <h2>Obtaining an OAuth 2.0 Access Token with IndieAuth</h2>

  <p>If you're building an application that wants to access or modify a user's data, you'll need an OAuth 2.0 access token to use in API requests.</p>
  <p>You can use IndieAuth to obtain an access token from the user's own token endpoint, while identifying them in the process.</p>
  <p><a href="https://indieauth.spec.indieweb.org/#authorization">Obtaining an Access Token</a></p>
</section>

<section class="border-top">
  <h2>Choosing an IndieAuth Provider</h2>

  <p>In order to log in to apps that use IndieAuth, you'll need to tell these apps where your IndieAuth endpoints live. You can either delegate your domain to an external IndieAuth provider, run an IndieAuth provider yourself, or your IndieAuth provider may already be part of the same software that runs your website.</p>

  <h3>Public IndieAuth Providers</h3>
  <ul>
    <li><a href="https://indieauth.com/">indieauth.com</a></li>
  </ul>

  <h3>Self-Hosted IndieAuth Providers</h3>
  <ul>
    <li><a href="https://github.com/Inklings-io/selfauth">selfauth</a></li>
    <li><a href="https://github.com/cweiske/indieauth-openid">indieauth-openid</a> - proxies IndieAuth requests to your own OpenID provider</li>
    <li><a href="https://github.com/barryf/acquiescence">Acquiescence</a> - an IndieAuth server written in Ruby which uses GitHub to authenticate you</li>
    <li><a href="https://indieweb.org/Wordpress_IndieAuth_Plugin">WordPress IndieAuth Plugin</a> - adds an IndieAuth endpoint to your WordPress site</li>
  </ul>

  <h3>Software with a Built-In IndieAuth Provider</h3>
  <ul>
    <li><a href="https://withknown.com/">Known</a></li>
    <li><a href="http://dobrado.net">Dobrado</a></li>
  </ul>

  <h3>Services with Built-In IndieAuth Support</h3>
  <ul>
    <li><a href="https://micro.blog">Micro.blog</a></li>
  </ul>
</section>


<section class="border-top">
  <h2>Frequently Asked Questions</h2>

  <h3 id="differences-from-openid-connect">How is IndieAuth different from OpenID Connect?</h3>
  <p>See <a href="https://indieweb.org/How_is_IndieAuth_different_from_OpenID_Connect">indieweb.org/How_is_IndieAuth_different_from_OpenID_Connect</a></p>

</section>


</article>

<footer>
  <section>
    <h3>More Resources</h3>
    <ul>
      <li><a href="https://indieweb.org/IndieAuth">IndieAuth on indieweb.org</a></li>
      <li><a href="https://indieweb.org/Category:IndieAuth">IndieAuth details and tutorials</a></li>
      <li><a href="https://oauth2simplified.com/">OAuth 2.0 Simplified</a></li>
      <li><a href="https://micropub.net/">Micropub</a></li>
    </ul>
  </section>
</footer>


</body>
</html>
