<!DOCTYPE html>
<html>
  <head>
    <title>IndieAuth</title>
    <meta charset='utf-8'>
    <script src='https://www.w3.org/Tools/respec/respec-w3c-common' async class='remove'></script>
    <script src='add-paragraph-ids.js' class='remove'></script>
    <script src='indieweb-cleanup.js' class='remove'></script>
    <script class='remove'>
      var respecConfig = {
          useExperimentalStyles: true,
          publishDate: "2020-09-26",
          specStatus: "NOTE", /* for loading w3c CSS */
          previousPublishDate: "2020-08-09",
          previousMaturity: "LS",
          previousVersionURL: "https://indieauth.spec.indieweb.org/20200809/",
          shortName:  "indieauth",
          lsURI: "https://indieauth.spec.indieweb.org/",
          testSuiteURI: "https://indieauth.rocks/",
          editors: [
                {   name:       "Aaron Parecki",
                    url:        "https://aaronparecki.com/",
                    w3cid:      "59996" }
          ],
          wg:           "Social Web Working Group",
          wgURI:        "https://www.w3.org/Social/WG",
          wgPublicList: "public-socialweb",
          wgPatentURI:  "https://www.w3.org/2004/01/pp-impl/72531/status",
          errata: "https://indieauth.net/errata",
          license: "w3c-software-doc",
          postProcess: [addParagraphIDs, indiewebCleanup],
          maxTocLevel: 3,
          otherLinks: [{
            key: 'Repository',
            data: [
              {
                value: 'Github',
                href: 'https://github.com/indieweb/indieauth'
              },
              {
                value: 'Issues',
                href: 'https://github.com/indieweb/indieauth/issues'
              },
              {
                value: 'Commits',
                href: 'https://github.com/indieweb/indieauth/commits/master'
              }
            ]
          }],
          localBiblio:  {
            "microformats2-parsing": {
              title: "Microformats2 Parsing",
              href: "https://microformats.org/wiki/microformats2-parsing",
              authors: [
                "Tantek Çelik"
              ],
              status:   "Living Specification",
              publisher:  "microformats.org"
            },
            "h-entry": {
              title: "h-entry",
              href: "https://microformats.org/wiki/h-entry",
              authors: [
                "Tantek Çelik"
              ],
              status:   "Living Specification",
              publisher:  "microformats.org"
            },
            "h-app": {
              title: "h-app",
              href: "https://microformats.org/wiki/h-app",
              authors: [
                "Aaron Parecki"
              ],
              status:   "Living Specification",
              publisher:  "microformats.org"
            },
            "RelMeAuth": {
              title: "RelMeAuth",
              href: "https://microformats.org/wiki/RelMeAuth",
              authors: [
                "Tantek Çelik"
              ],
              status:   "Living Specification",
              publisher:  "microformats.org"
            },
            "XFN11": {
              title: "XFN 1.1",
              href: "https://gmpg.org/xfn/11",
              authors: [
                "Tantek Çelik",
                "Matthew Mullenweg",
                "Eric Meyer"
              ],
              status: "Stable",
              publisher: "Global Multimedia Protocols Group"
            },
            "URL": {
              title: "URL Standard",
              href: "https://url.spec.whatwg.org/",
              authors: ["Anne van Kesteren"],
              status: "Living Standard",
              publisher: "WHATWG"
            }
          }
      };
    </script>
    <link rel="pingback" href="https://indieauth.net/pingback">
    <link rel="webmention" href="https://indieauth.net/webmention">
  </head>
  <body>
    <section id='abstract'>
      <p>
        IndieAuth is an identity layer on top of OAuth 2.0 [[RFC6749]], primarily used to obtain
        an OAuth 2.0 Bearer Token [[RFC6750]] for use by [[?Micropub]] clients. End-Users
        and Clients are all represented by URLs. IndieAuth enables Clients to
        verify the identity of an End-User, as well as to obtain an access
        token that can be used to access resources under the control of the End-User.
      </p>

      <section id="authorsnote" class="informative">
        <h2>Author's Note</h2>
        <p>This specification was contributed to the W3C from the
          <a href="https://indieweb.org/">IndieWeb</a> community. More
          history and evolution of IndieAuth can be found on the
          <a href="https://indieweb.org/IndieAuth-spec">IndieWeb wiki</a>.</p>
      </section>
    </section>

    <section id='sotd'>
    </section>

    <section>
      <h2>Introduction</h2>

      <section class="informative">
        <h3>Background</h3>

        <p>The IndieAuth spec began as a way to obtain an OAuth 2.0 access token for use by Micropub clients. It can be used to both obtain an access token, as well as authenticate users signing to any application. It is built on top of the OAuth 2.0 framework, and while this document should provide enough guidance for implementers, referring to the core OAuth 2.0 spec can help answer any remaining questions. More information can be found <a href="https://indieweb.org/IndieAuth-spec">on the IndieWeb wiki</a>.</p>
      </section>

      <section class="normative">
        <h3>OAuth 2.0 Extension</h3>

        <p>IndieAuth builds upon the OAuth 2.0 [[!RFC6749]] Framework as follows</p>

        <ul>
          <li>Specifies a format for user identifiers (a resolvable URL)</li>
          <li>Specifies a method of discovering the authorization and token endpoints given a profile URL</li>
          <li>Specifies a format for the Client ID (a resolvable URL)</li>
          <li>All clients are public clients (no <code>client_secret</code> is used)</li>
          <li>Client registration at the authorization endpoint is not necessary, since client IDs are resolvable URLs</li>
          <li>Redirect URL registration happens by verifying data fetched at the Client ID URL</li>
          <li>Specifies a mechanism for returning the user identifier for the user who authorized a request</li>
          <li>Specifies a mechanism for verifying authorization codes</li>
          <li>Specifies a mechanism for a token endpoint and authorization endpoint to communicate</li>
        </ul>

        <p>Additionally, the parameters defined by OAuth 2.0 (in particular <code>state</code>, <code>code</code>, and <code>scope</code>) follow the same syntax requirements as defined by Appendix A of OAuth 2.0 [[!RFC6749]].</p>
      </section>
    </section>

    <section class="normative">
      <h2>Conformance</h2>

      <p>The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT",
      "SHOULD", "SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this
      document are to be interpreted as described in [[!RFC2119]].</p>

      <section>
        <h3>Conformance Classes</h3>

        <p>An IndieAuth implementation can implement one or more of the roles of an IndieAuth server or client. This section describes the conformance criteria for each role.</p>

        <p>Listed below are known types of IndieAuth implementations.</p>

        <section>
          <h4>Authorization Endpoint</h4>
          <p>An IndieAuth Authorization Endpoint is responsible for obtaining authentication or authorization consent from the end user and generating and verifying authorization codes.</p>
        </section>

        <section>
          <h4>Token Endpoint</h4>
          <p>An IndieAuth Token Endpoint is responsible for generating and verifying OAuth 2.0 Bearer Tokens.</p>
        </section>

        <section>
          <h4>Micropub Client</h4>
          <p>A Micropub client will attempt to obtain an OAuth 2.0 Bearer Token given an IndieAuth profile URL, and will use the token when making Micropub requests.</p>
        </section>

        <section>
          <h4>IndieAuth Client</h4>
          <p>An IndieAuth client is a client that is attempting to authenticate a user given their profile URL, but does not need an OAuth 2.0 Bearer Token.</p>
        </section>

      </section>
    </section>

    <section class="normative">
      <h2>Identifiers</h2>

      <section>
        <h3>User Profile URL</h3>

        <p>Users are identified by a [[!URL]]. Profile URLs MUST have either an <code>https</code> or <code>http</code> scheme, MUST contain a path component (<code>/</code> is a valid path), MUST NOT contain single-dot or double-dot path segments, MAY contain a query string component, MUST NOT contain a fragment component, MUST NOT contain a username or password component, and MUST NOT contain a port. Additionally, hostnames MUST be domain names and MUST NOT be ipv4 or ipv6 addresses.</p>

        <p>Some examples of valid profile URLs are:</p>

        <ul>
          <li><code>https://example.com/</code></li>
          <li><code>https://example.com/username</code></li>
          <li><code>https://example.com/users?id=100</code></li>
        </ul>

        <p>Some examples of invalid profile URLs are:</p>
        <ul>
          <li><s><code>example.com</code></s> - missing scheme</li>
          <li><s><code>mailto:user@example.com</code></s> - invalid scheme</li>
          <li><s><code>https://example.com/foo/../bar</code></s> - contains a double-dot path segment</li>
          <li><s><code>https://example.com/#me</code></s> - contains a fragment</li>
          <li><s><code>https://user:pass@example.com/</code></s> - contains a username and password</li>
          <li><s><code>https://example.com:8443/</code></s> - contains a port</li>
          <li><s><code>https://172.28.92.51/</code></s> - host is an IP address</li>
        </ul>
      </section>

      <section>
        <h3>Client Identifier</h3>

        <p>Clients are identified by a [[!URL]]. Client identifier URLs MUST have either an <code>https</code> or <code>http</code> scheme, MUST contain a path component, MUST NOT contain single-dot or double-dot path segments, MAY contain a query string component, MUST NOT contain a fragment component, MUST NOT contain a username or password component, and MAY contain a port. Additionally, hostnames MUST be domain names or a loopback interface and MUST NOT be IPv4 or IPv6 addresses except for IPv4 <code>127.0.0.1</code> or IPv6 <code>[::1]</code>.</p>
      </section>

      <section>
        <h3>URL Canonicalization</h3>

        <p>Since IndieAuth uses https/http URLs which fall under what [[!URL]] calls "<a href="https://url.spec.whatwg.org/#special-scheme">Special URLs</a>", a string with no path component is not a valid [[!URL]]. As such, if a URL with no path component is ever encountered, it MUST be treated as if it had the path <code>/</code>. For example, if a user enters <code>https://example.com</code> as their profile URL, the client MUST transform it to <code>https://example.com/</code> when using it and comparing it.</p>

        <p>Since domain names are case insensitive, the hostname component of the URL MUST be compared case insensitively. Implementations SHOULD convert the hostname to lowercase when storing and using URLs.</p>

        <p>For ease of use, clients MAY allow users to enter just a hostname part of the URL, in which case the client MUST turn that into a valid URL before beginning the IndieAuth flow, by prepending a either an <code>http</code> or <code>https</code> scheme and appending the path <code>/</code>. For example, if the user enters <code>example.com</code>, the client transforms it into <code>http://example.com/</code> before beginning discovery.</p>
      </section>

    </section>

    <section class="normative">
      <h2>Discovery</h2>

      <p>This specification uses the link rel registry as defined by [[!HTML]]
        for both HTML and HTTP link relations.</p>

      <section>
        <h3>Discovery by Clients</h3>

        <p>Clients need to discover a few pieces of information when a user signs in. The client needs to discover the user's <code>authorization_endpoint</code>, and optionally <code>token_endpoint</code> if the client needs an access token. When using the Authorization flow to obtain an access token for use at a [[?Micropub]] endpoint, the client will also discover the <code>micropub</code> endpoint.</p>

        <p>Clients MUST start by making a GET or HEAD request to [[!Fetch]] the user's profile URL to discover the necessary values. Clients MUST follow HTTP redirects (up to a self-imposed limit). If one or more successive HTTP permanent redirects (HTTP 301 or 308) are encountered starting with the very first request, the client MUST use the final permanent redirection's target URL as the canonical profile URL. If any other redirection (such as HTTP 302, 303, or 307) is encountered, it must still be resolved for endpoint discovery, but this redirection does not modify the canonical profile URL, nor do subsequent permanent redirects.</p>

        <p>Clients MUST check for an HTTP <code>Link</code> header [[!RFC8288]] with the appropriate <code>rel</code> value. If the content type of the document is HTML, then the client MUST check for an HTML <code>&lt;link&gt;</code> element with the appropriate <code>rel</code> value. If more than one of these is present, the first HTTP <code>Link</code> header takes precedence, followed by the first <code>&lt;link&gt;</code> element in document order.</p>

        <p>The endpoints discovered MAY be relative URLs, in which case the client MUST resolve them relative to the profile URL according to [[!URL]].</p>

        <p>Clients MAY initially make an HTTP HEAD request [[!RFC7231]] to follow redirects and check for the <code>Link</code> header before making a GET request.</p>

        <section>
          <h4>Redirect Examples</h4>

          <section>
            <h5>http to https</h5>
            <p>In this example, the user enters <code>example.com</code> in the sign-in form, so the client initially transforms that to <code>http://example.com/</code> to perform discovery. The URL <code>http://example.com/</code> returns an HTTP 301 permanent redirect to <code>https://example.com/</code>, so the client updates the initial profile URL to <code>https://example.com/</code>, and looks at the contents of that page to find the authorization endpoint.</p>
          </section>

          <section>
            <h5>www to no-www</h5>
            <p>In this example, the user enters <code>www.example.com</code> in the sign-in form, so the client initially transforms that to <code>http://www.example.com/</code> to perform discovery. The URL <code>http://www.example.com/</code> returns an HTTP 301 permanent redirect to <code>https://example.com/</code>, so the client updates the initial profile URL to <code>https://example.com/</code>, and looks at the contents of that page to find the authorization endpoint.</p>
          </section>

          <section>
            <h5>Temporary Redirect</h5>
            <p>In this example, the user enters <code>example.com</code> in the sign-in form, so the client initially transforms that to <code>http://example.com/</code> to perform discovery. The URL <code>http://example.com/</code> returns an HTTP 301 permanent redirect to <code>https://example.com/</code>, and <code>https://example.com/</code> returns an HTTP 302 temporary redirect to <code>https://example.com/username</code>. The client stores the last 301 permanent redirect as the profile URL, <code>https://example.com/</code>, and uses the contents of <code>https://example.com/username</code> to find the authorization endpoint.</p>
          </section>

          <section>
            <h5>Permanent Redirect to a Different Domain</h5>
            <p>In this example, the user enters <code>username.example</code> in the sign-in form, so the client initially transforms that to <code>http://username.example/</code> to perform discovery. However, the user does not host any content there, and instead that page is a redirect to their profile elsewhere. The URL <code>http://username.example/</code> returns an HTTP 301 permanent redirect to <code>https://example.com/username</code>, so the client updates the initial profile URL to <code>https://example.com/username</code> when setting the <code>me</code> parameter in the initial request. At the end of the flow, the authorization endpoint will return a <code>me</code> value of <code>https://example.com/username</code>, which is not on the same domain as what the user entered, but the client can accept it because of the HTTP 301 redirect encountered during discovery.</p>
          </section>

          <section>
            <h5>Temporary Redirect to a Different Domain</h5>
            <p>In this example, the user enters <code>username.example</code> in the sign-in form, so the client initially transforms that to <code>http://username.example/</code> to perform discovery. However, the user does not host any content there, and instead that page is a temporary redirect to their profile elsewhere. The URL <code>http://username.example/</code> returns an HTTP 302 temporary redirect to <code>https://example.com/username</code>, so the client discovers the authorization endpoint at that URL. Since the redirect is temporary, the client still uses the user-entered <code>http://username.example/</code> when setting the <code>me</code> parameter in the initial request. At the end of the flow, the authorization endpoint will return a <code>me</code> value of <code>https://username.example/</code>, which is not on the same domain as the authorization endpoint, but is the same domain as the user entered. This allows users to still use a profile URL under their control while delegating the authentication or authorization flow to an external account.</p>
          </section>
        </section>
      </section>

      <section>
        <h3>Client Information Discovery</h3>

        <p>When an authorization server presents its <a href="https://www.oauth.com/oauth2-servers/authorization/the-authorization-interface/">authorization interface</a>, it will often want to display some additional information about the client beyond just the <code>client_id</code> URL, in order to better inform the user about the request being made. Additionally, the authorization server needs to know the list of redirect URLs that the client is allowed to redirect to.</p>

        <p>Since client identifiers are URLs, the authorization server SHOULD [[!Fetch]] the URL to find more information about the client.</p>

        <section>
          <h4>Application Information</h4>

          <p>Clients SHOULD have a web page at their <code>client_id</code> URL with basic information about the application, at least the application's name and icon. This page serves as a good landing page for human visitors, but can also serve as the place to include machine-readable information about the application. The HTML on the <code>client_id</code> URL SHOULD be marked up with [[!h-app]] Microformat to indicate the name and icon of the application. Authorization servers SHOULD support parsing the [[!h-app]] Microformat from the <code>client_id</code>, and if there is an [[!h-app]] with a <code>url</code> property matching the <code>client_id</code> URL, then it should use the name and icon and display them on the authorization prompt.</p>

          <pre class="example"><?= htmlspecialchars(
'<div class="h-app">
  <img src="/logo.png" class="u-logo">
  <a href="/" class="u-url p-name">Example App</a>
</div>') ?></pre>

          <p>This can be parsed with a <a href="http://microformats.org/wiki/microformats2#Parsers">Microformats2 parser</a>, which will result in the following JSON structure.</p>

          <pre class="example">{
  "type": [
    "h-app"
  ],
  "properties": {
    "name": ["Example App"],
    "logo": ["https://app.example.com/logo.png"],
    "url": ["https://app.example.com/"]
  }
}</pre>
        </section>

        <section>
          <h4>Redirect URL</h4>

          <p>If a client wishes to use a redirect URL that is on a different domain than their <code>client_id</code>, or if the redirect URL uses a custom scheme (such as when the client is a native application), then the client will need to whitelist those redirect URLs so that authorization endpoints can be sure it is safe to redirect users there. The client SHOULD publish one or more <code>&lt;link&gt;</code> tags or <code>Link</code> HTTP headers with a <code>rel</code> attribute of <code>redirect_uri</code> at the <code>client_id</code> URL.</p>

          <p>Authorization endpoints verifying that a <code>redirect_uri</code> is allowed for use by a client MUST look for an exact match of the given <code>redirect_uri</code> in the request against the list of <code>redirect_uri</code>s discovered after resolving any relative URLs.</p>

          <pre class="example"><?= htmlspecialchars('GET / HTTP/1.1
Host: app.example.com

HTTP/1.1 200 Ok
Content-type: text/html; charset=utf-8
Link: <https://app.example.com/redirect>; rel="redirect_uri"

<!doctype html>
<html>
  <head>
    <link rel="redirect_uri" href="/redirect">
  </head>
  ...
</html>') ?></pre>
        </section>

      </section>

    </section>

    <section class="normative">
      <h2>Authorization</h2>

      <p>This section describes how to authenticate users and optionally obtain an access token using the OAuth 2.0 Authorization Code Flow with IndieAuth.</p>

<?php /*
---
title IndieAuth Authorization Flow Diagram

Browser->Client: User enters their profile URL
Client->User URL: Client fetches URL to discover\n**rel=authorization_endpoint**\nand **rel=token_endpoint**
Browser<--Client: Client builds authorization request and\nredirects to **authorization_endpoint**
Browser->Authorization Endpoint: User visits their authorization endpoint and sees the authorization request
Authorization Endpoint->Client: Authorization endpoint fetches client information (name, icon)
Browser<--Authorization Endpoint: User authenticates, and approves the request.\nAuthorization endpoint issues code, builds redirect back to client.
Browser->Client: User's browser is redirected to\nclient with an **authorization code**
Client->Token Endpoint: Client exchanges authorization code for an \naccess token by making a POST request\nto the token_endpoint
Client<--Token Endpoint: Token endpoint verifies code and returns\ncanonical user profile URL with an access token
Browser<--Client: Client initiates login session\nand the user is logged in
---

https://sequencediagram.org/index.html?initialData=C4S2BsFMAIEkDsAmJIEECuwAW0PYPYBOIAXgIaj7zQBi4+A7tACIhkDmhZAtgFC8AhQowDOkQgFoAfAGFwKeMABc0AKpjC0SIvEjo2SCE0AHYQDMQUNQCUAMrzkLg09eJu2Vj7cGhnIwAGMsSD1VO318aGQRAPwAN3EAHXgAKhTCSHAAXjJMLCJSChAqAH1tRGN8EEU05LIkaDSM7OB8AGttMqRK6uA0wWEGDQAeCQkvRU95b2gAI3RLRD1cgmJySmoMgEd0EJ96xGSM5AyA4D1WxpSV-LWi0vKempSB0XFpPFvCjegAUW6qpM1BpoHEQCIwBdgkZoDcCutitRHoD9g0xCF9MFYXl4fdNpAdnteJ9cT9-hUUdIJspcDi7j9kb1fP4ghiAtNFNBqmYiNw8dAABTwHiQAA0XNi8AAlK8huJRhISfTEX8Ab0VK5NDdvCAAhQQuKDrDjKZ4hiDNBtrsRMAAHTJJXfFWMzngkTW6CxRBiuYLcBLS2QE6QM5zMgBNoRT0cu2yjRUmMajQAcj0s0GIPBgeDZyDEWS7Kc0AYYBw9SucOVVE9+G9-Wp0gAKu1tKqKeroNStAAPIL1dgYytO6temA8rXUOoBAIhC4t6izACe0D5bWq7Fh0AACgB5ADKjcDhJtyUuFtaHXgXXbigcMYVzcvbaeNMfrZdPgSxAsbNrMCNGTAOghDwCIBb1FQupkOA0DoCCpoWFYYS2MWpawtQ4YziIc6XnG8pjNSUxFtUYBsMAGL0Ow1TQGI2GInUDQWnBbhZpRA6IFy8C8EAA

Note: Change width/height to e.g.
viewbox="0 0 906 716" style="width: 100%; height: auto;"
*/ ?>

      <?= file_get_contents('authorization-flow-diagram.svg') ?>

      <ul>
        <li>The End-User enters their profile URL in the login form of the client and clicks "Sign in"</li>
        <li>The client discovers the End-User's authorization endpoint and token endpoint by fetching the profile URL and looking for the <code>rel=authorization_endpoint</code> and <code>rel=token_endpoint</code> values</li>
        <li>The client builds the authorization request including its client identifier, requested scope, local state, and a redirect URI, and redirects the browser to the authorization endpoint</li>
        <li>The authorization endpoint fetches the client information from the client identifier URL in order to have an application name and icon to display to the user</li>
        <li>The authorization endpoint verifies the End-User, e.g. by logging in, and establishes whether the End-User grants or denies the client's request</li>
        <li>The authorization endpoint generates an authorization code and redirects the browser back to the client, including an authorization code in the URL</li>
        <li>The client exchanges the authorization code for an access token by making a POST request to the token endpoint. The token endpoint validates the authorization code, and responds with the End-User's canonical profile URL and an access token</li>
      </ul>

      <p>Note: If the client is only trying to learn who the user is and does not need an access token, the client exchanges the authorization code for the user profile information at the Authorization Endpoint instead.</p>

      <section>
        <h3>Discovery</h3>

        <p>After obtaining the End-User's profile URL, the client fetches the URL and looks for the <code>authorization_endpoint</code> and <code>token_endpoint</code> rel values in the HTTP <code>Link</code> headers and HTML <code>&lt;link&gt;</code> tags as described in <a href="#discovery-by-clients"></a>.</p>

        <pre class="example nohighlight"><?= htmlspecialchars(
'Link: <https://example.org/auth>; rel="authorization_endpoint"
Link: <https://example.org/token>; rel="token_endpoint"

<link rel="authorization_endpoint" href="https://example.org/auth">
<link rel="token_endpoint" href="https://example.org/token">') ?></pre>
      </section>

      <section>
        <h3>Authorization Request</h3>

          <p>The client builds the authorization request URL by starting with the discovered <code>authorization_endpoint</code> URL and adding parameters to the query component.</p>

          <p>All IndieAuth clients MUST use PKCE ([[!RFC7636]]) to protect against authorization code injection and CSRF attacks. A non-canonical description of the PKCE mechanism is described below, but implementers should refer to [[RFC7636]] for details.</p>

          <p>Clients use a unique secret per authorization request to protect against authorization code injection and CSRF attacks. The client first generates this secret, which it can later use along with the authorization code to prove that the application using the authorization code is the same application that requested it.</p>

          <p>The client first creates a code verifier for each authorization request by generating a random string using the characters <code>[A-Z] / [a-z] / [0-9] / - / . / _ / ~</code> with a minimum length of 43 characters and maximum length of 128 characters. This value is stored on the client and will be used in the authorization code exchange step later.</p>

          <p>The client then creates the code challenge derived from the code verifier by calculating the SHA256 hash of the code verifier and Base64-URL-encoding the result.</p>

          <code>code_challenge = BASE64URL-ENCODE(SHA256(ASCII(code_verifier)))</code>

          <p>For backwards compatibility, authorization endpoints MAY accept authorization requests without a code challenge if the authorization server wishes to support older clients.</p>

          <ul>
            <li><code>response_type=code</code> - Indicates to the authorization server that an authorization code should be returned as the response</li>
            <li><code>client_id</code> - The client URL</li>
            <li><code>redirect_uri</code> - The redirect URL indicating where the user should be redirected to after approving the request</li>
            <li><code>state</code> - A parameter set by the client which will be included when the user is redirected back to the client. This is used to prevent CSRF attacks. The authorization server MUST return the unmodified state value back to the client.</li>
            <li><code>code_challenge</code> - The code challenge as previously described.</li>
            <li><code>code_challenge_method</code> - The hashing method used to calculate the code challenge, e.g. "S256"</li>
            <li><code>scope</code> - (optional) A space-separated list of scopes the client is requesting, e.g. "profile", or "profile create". If the client omits this value, the authorization server MUST NOT issue an access token for this authorization code. Only the user's profile URL may be returned without any scope requested. See <a href="#profile-information">Profile Information</a> for details about which scopes to request to return user profile information.</li>
            <li><code>me</code> - (optional) The profile URL that the user entered</li>
          </ul>

          <pre class="example nohighlight"><?= htmlspecialchars(
'https://example.org/auth?response_type=code&
                          client_id=https://app.example.com/&
                          redirect_uri=https://app.example.com/redirect&
                          state=1234567890&
                          code_challenge=OfYAxt8zU2dAPDWQxTAUIteRzMsoj9QBdMIVEDOErUo&
                          code_challenge_method=S256&
                          scope=profile+create+update+delete&
                          me=https://user.example.net/') ?></pre>

          <p>The client SHOULD provide the <code>me</code> query string parameter to the authorization endpoint, either the exact value the user entered, or the canonical profile URL resulting from the discovery step.</p>

          <p>The authorization endpoint SHOULD fetch the <code>client_id</code> URL to retrieve application information and the client's registered redirect URLs, see <a href="#client-information-discovery">Client Information Discovery</a> for more information.</p>

          <p>If the URL scheme, host or port of the <code>redirect_uri</code> in the request do not match that of the <code>client_id</code>, then the authorization endpoint SHOULD verify that the requested <code>redirect_uri</code> matches one of the <a href="#redirect-url">redirect URLs</a> published by the client, and SHOULD block the request from proceeding if not.</p>

          <p>It is up to the authorization endpoint how to authenticate the user. This step is out of scope of OAuth 2.0, and is highly dependent on the particular implementation. Some authorization servers use typical username/password authentication, and others use alternative forms of authentication such as [[?RelMeAuth]], or delegate to other identity providers.</p>

          <p>The authorization endpoint MAY use the provided <code>me</code> query component as a hint of which user is attempting to sign in, and to indicate which profile URL the client is expecting in the resulting profile URL response or access token response. This is specifically helpful for authorization endpoints where users have multiple supported profile URLs, so the authorization endpoint can make an informed decision as to which profile URL the user meant to identify as. Note that from the authorization endpoint's view, this value as provided by the client is unverified external data and MUST NOT be assumed to be valid data at this stage. If the logged-in user doesn't match the provided <code>me</code> parameter by the client, the authorization endpoint MAY either ignore the <code>me</code> parameter completely or display an error, at the authorization endpoint's discretion.</p>

          <p>Once the user is authenticated, the authorization endpoint presents the authorization request to the user. The prompt MUST indicate which application the user is signing in to, and SHOULD provide as much detail as possible about the request, such as information about the requested scopes.</p>

        <section>
          <h4>Authorization Response</h4>

          <p>If the user approves the request, the authorization endpoint generates an authorization code and builds the redirect back to the client.</p>

          <p>The redirect is built by starting with the <code>redirect_uri</code> in the request, and adding the following parameters to the query component of the redirect URL:</p>

          <ul>
            <li><code>code</code> - The authorization code generated by the authorization endpoint. The code MUST expire shortly after it is issued to mitigate the risk of leaks, and MUST be valid for only one use. A maximum lifetime of 10 minutes is recommended. See <a href="https://tools.ietf.org/html/rfc6749#section-4.1.2">OAuth 2.0 Section 4.1.2</a> for additional requirements on the authorization code.</li>
            <li><code>state</code> - The state parameter MUST be set to the exact value that the client set in the request.</li>
          </ul>

          <pre class="example nohighlight"><?= htmlspecialchars(
  'HTTP/1.1 302 Found
  Location: https://app.example.com/redirect?code=xxxxxxxx
                                             state=1234567890') ?></pre>

          <p>Upon the redirect back to the client, the client MUST verify that the state parameter in the request is valid and matches the state parameter that it initially created, in order to prevent CSRF attacks. The state value can also store session information to enable development of clients that cannot store data themselves.</p>

          <p>See OAuth 2.0 [[!RFC6749]] <a href="https://tools.ietf.org/html/rfc6749#section-4.1.2.1">Section 4.1.2.1</a> for how to indicate errors and other failures to the user and client.</p>
        </section>
      </section>

      <section>
        <h3>Redeeming the Authorization Code</h3>

        <p>Once the client has obtained an authorization code, it can redeem it for an access token or the user's final profile URL.</p>

        <section>
          <h4>Request</h4>

          <p>If the client needs an access token in order to make requests to a resource server such as a [[?Micropub]] endpoint, it can exchange the authorization code for an access token and the user's profile URL at the <b>token endpoint</b>.</p>

          <p>If the client only needs to know the user who logged in and does not need to make requests to resource servers with an access token, the client exchanges the authorization code for the user's profile URL at the <b>authorization endpoint</b>.</p>

          <p>After the client validates the state parameter, the client makes a POST request to the token endpoint or authorization endpoint to exchange the authorization code for the final user profile URL and/or access token. The POST request contains the following parameters:</p>

          <ul>
            <li><code>grant_type=authorization_code</code></li>
            <li><code>code</code> - The authorization code received from the authorization endpoint in the redirect.</li>
            <li><code>client_id</code> - The client's URL, which MUST match the client_id used in the authentication request.</li>
            <li><code>redirect_uri</code> - The client's redirect URL, which MUST match the initial authentication request.</li>
            <li><code>code_verifier</code> - The original plaintext random string generated before starting the authorization request.</li>
          </ul>

          <b>Example request to authorization endpoint</b>
          <pre class="example nohighlight"><?= htmlspecialchars(
  'POST https://example.org/auth
  Content-type: application/x-www-form-urlencoded
  Accept: application/json

  grant_type=authorization_code
  &code=xxxxxxxx
  &client_id=https://app.example.com/
  &redirect_uri=https://app.example.com/redirect
  &code_verifier=a6128783714cfda1d388e2e98b6ae8221ac31aca31959e59512c59f5
  ') ?></pre>

          <b>Example request to token endpoint</b>
          <pre class="example nohighlight"><?= htmlspecialchars(
'POST https://example.org/token
Content-type: application/x-www-form-urlencoded
Accept: application/json

grant_type=authorization_code
&code=xxxxxxxx
&client_id=https://app.example.com/
&redirect_uri=https://app.example.com/redirect
&code_verifier=a6128783714cfda1d388e2e98b6ae8221ac31aca31959e59512c59f5
') ?></pre>
        </section>

          <p>Note that for backwards compatibility, the authorization endpoint MAY allow requests without the <code>code_verifier</code>. If an authorization code was issued with no <code>code_challenge</code> present, then the authorization code exchange MUST NOT include a <code>code_verifier</code>, and similarly, if an authorization code was issued with a <code>code_challenge</code> present, then the authorization code exchange MUST include a <code>code_verifier</code>.</p>

        <section>
          <h4>Profile URL Response</h4>

          <p>When the client receives an authorization code that was requested with either no scope or only profile scopes (<a href="#profile-information">defined below</a>), the client will exchange the authorization code at the <b>authorization endpoint</b>, and only the canonical user profile URL and possibly profile information is returned.</p>

          <p>The authorization endpoint verifies that the authorization code is valid, has not yet been used, and that it was issued for the matching <code>client_id</code> and <code>redirect_uri</code>, and checks that the provided <code>code_verifier</code> hashes to the same value as given in the <code>code_challenge</code> in the original authorization request. If the request is valid, then the endpoint responds with a JSON [[!RFC7159]] object containing the property <code>me</code>, with the canonical user profile URL for the user who signed in, and optionally the property <code>profile</code> with the user's profile information as defined in <a href="#profile-information">Profile Information</a>.</p>

          <pre class="example nohighlight"><?= htmlspecialchars(
  'HTTP/1.1 200 OK
  Content-Type: application/json

  {
    "me": "https://user.example.net/"
  }') ?></pre>

          <p>The resulting profile URL MAY be different from what the user initially entered, but MUST be on the same domain. This gives the authorization endpoint an opportunity to canonicalize the user's URL, such as correcting <code>http</code> to <code>https</code>, or adding a path if required. See <a href="#redirect-examples">Redirect Examples</a> for an example of how a service can allow a user to enter a URL on a domain different from their resulting <code>me</code> profile URL.</p>

          <p>See OAuth 2.0 [[!RFC6749]] <a href="https://tools.ietf.org/html/rfc6749#section-5.2">Section 5.2</a> for how to respond in the case of errors or other failures.</p>
        </section>

        <section>
          <h4>Access Token Response</h4>

          <p>When the client receives an authorization code that was requested with one or more scopes that will result in an access token being returned, the client will exchange the authorization code at the <b>token endpoint</b>.</p>

          <p>The token endpoint needs to verify that the authorization code is valid, and that it was issued for the matching <code>client_id</code> and <code>redirect_uri</code>, contains at least one <code>scope</code>, and checks that the provided <code>code_verifier</code> hashes to the same value as given in the <code>code_challenge</code> in the original authorization request.. If the authorization code was issued with no <code>scope</code>, the token endpoint MUST NOT issue an access token, as empty scopes are invalid per Section 3.3 of OAuth 2.0 [[!RFC6749]].</p>

          <p>The specifics of how the token endpoint verifies the authorization code are out of scope of this document, as typically the authorization endpoint and token endpoint are part of the same system and can share storage or another private communication mechanism.</p>

          <p>If the request is valid, then the token endpoint can generate an access token and return the appropriate response. The token response is a JSON [[!RFC7159]] object containing the OAuth 2.0 Bearer Token [[!RFC6750]], as well as a property <code>me</code>, containing the canonical user profile URL for the user this access token corresponds to, and optionally the property <code>profile</code> with the user's profile information as defined in <a href="#profile-information">Profile Information</a>. For example:</p>

          <pre class="example nohighlight">HTTP/1.1 200 OK
Content-Type: application/json

{
  "access_token": "XXXXXX",
  "token_type": "Bearer",
  "scope": "create update delete",
  "me": "https://user.example.net/"
}</pre>

          <p>The resulting profile URL MAY be different from what the user initially entered, but MUST be on the same domain. This provides the opportunity to canonicalize the user's URL, such as correcting <code>http</code> to <code>https</code>, or adding a path if required. See <a href="#redirect-examples">Redirect Examples</a> for an example of how a service can allow a user to enter a URL on a domain different from their resulting <code>me</code> profile URL.</p>

          <p>See OAuth 2.0 [[!RFC6749]] <a href="https://tools.ietf.org/html/rfc6749#section-5.2">Section 5.2</a> for how to respond in the case of errors or other failures.</p>
        </section>

        <section>
          <h4>Profile Information</h4>

          <h5>Requesting Profile Information</h5>

          <p>If the client would like to request the user's profile information in addition to confirming their profile URL, the client can include one or more scopes in the initial authorization request. The following <code>scope</code> values are defined by this specification to request profile information about the user:</p>

          <ul>
            <li><code>profile</code> (required) - This scope requests access to the user's default profile information which include the following properties: <code>name</code>, <code>photo</code>, <code>url</code>.</li>
            <li><code>email</code> - This scope requests access to the user's email address in the following property: <code>email</code>.</li>
          </ul>

          <p>Note that because the <code>profile</code> scope is required when requesting profile information, the <code>email</code> scope cannot be requested on its own and must be requested along with the <code>profile</code> scope if desired.<p>

          <p>When an authorization code is issued with any of the scopes defined above, then the response when exchanging the authorization code MAY include a new property, <code>profile</code>, alongside the <code>me</code> property in the response from the authorization endpoint or the token endpoint. The <code>profile</code> property is defined as a JSON [[!RFC7159]] object with the properties defined by each scope above.</p>

          <p>For example, a complete response to a request with the scopes <code>profile email create</code>, including an access token and profile information, may look like the following:</p>

          <pre class="example nohighlight">HTTP/1.1 200 OK
Content-Type: application/json

{
  "access_token": "XXXXXX",
  "token_type": "Bearer",
  "scope": "profile email create",
  "me": "https://user.example.net/",
  "profile": {
    "name": "Example User",
    "url": "https://user.example.net/",
    "photo": "https://user.example.net/photo.jpg",
    "email": "user@example.net"
  }
}</pre>

          <p>As is always the case with OAuth 2.0, there is no guarantee that the scopes the client requests will be granted by the authorization server or the user. The client should not rely on the presence of profile information even when requesting the profile scope. As such, implementing support for returning profile information from the authorization server is entirely optional.</p>

          <p>The information returned in the <code>profile</code> object is informational, and there is no guarantee that this information is "real" or "verified". The information provided is only what the user has chosen to share with the client, and may even vary depending on which client is requesting this data.</p>

          <p>The client MUST NOT treat the information in the <code>profile</code> object as canonical or authoritative, and MUST NOT make any authentication or identification decisions based on this information.</p>

          <p>For example, attempting to use the <code>email</code> returned in the profile object as a user identifier will lead to security holes, as any user can create an authorization endpoint that returns any email address in the profile response. A client using the email address returned here should treat it the same as if it had been hand-entered in the client application and go through its own verification process before using it.</p>

          <p>Similarly, the <code>url</code> returned in the <code>profile</code> object is not guaranteed to match the <code>me</code> URL, and may even be on a different domain. For example, a multi-author website may use the website's URL as the <code>me</code> URL, but return each specific author's own personal website in the profile data.</p>

        </section>

      </section>

    </section>

    <section class="normative">
      <h3>Access Token Verification</h3>

      <p>In OAuth 2.0, access tokens are opaque to clients, so clients do not need to know anything about the contents or structure of the token itself. Additionally, endpoints that clients make requests to, such as [[?Micropub]] endpoints, may not even understand how to interpret tokens if they were issued by a standalone token endpoint. If the token endpoint is not tightly integrated with the resource server the client is interacting with, then the resource server needs a way to verify access tokens that it receives from clients. If the token endpoint and Micropub endpoint are tightly coupled, then they can of course use an internal mechanism to verify access tokens.</p>

      <p>Token endpoints that intend to interoperate with other endpoints MUST use the mechanism described below to allow resource servers such as Micropub endpoints to verify access tokens.</p>

      <section>
        <h4>Access Token Verification Request</h4>

        <p>If a resource server needs to verify that an access token is valid, it MUST make a GET request to the token endpoint containing an HTTP <code>Authorization</code> header with the Bearer Token according to [[!RFC6750]]. Note that the request to the endpoint will not contain any user-identifying information, so the resource server (e.g. Micropub endpoint) will need to know via out-of-band methods which token endpoint is in use.</p>

        <pre class="example nohighlight">GET https://example.org/token
  Authorization: Bearer xxxxxxxx
  Accept: application/json</pre>
      </section>

      <section>
        <h4>Access Token Verification Response</h4>

        <p>The token endpoint verifies the access token using (how this verification is done is up to the implementation), and returns information about the token:</p>

        <ul>
          <li><code>me</code> - The profile URL of the user corresponding to this token</li>
          <li><code>client_id</code> - The client ID associated with this token</li>
          <li><code>scope</code> - A space-separated list of scopes associated with this token</li>
        </ul>

        <pre class="example nohighlight">HTTP/1.1 200 OK
  Content-Type: application/json

  {
  "me": "https://user.example.net/",
  "client_id": https://app.example.com/",
  "scope": "create update delete"
  }</pre>

        <p>Specific implementations MAY include additional parameters as top-level JSON properties. Clients SHOULD ignore parameters they don't recognize.</p>

        <p>If the token is not valid, the endpoint MUST return an appropriate HTTP 400, 401 or 403 response. The response body is not significant.</p>
      </section>
    </section>

    <section class="normative">
      <h3>Token Revocation</h3>

      <p>A client may wish to explicitly disable an access token that it has obtained, such as when the user signs out of the client. IndieAuth extends OAuth 2.0 Token Revocation [[!RFC7009]] by defining the following:</p>

      <ul>
        <li>The revocation endpoint is the same as the token endpoint.</li>
        <li>The revocation request includes an additional parameter, <code>action=revoke</code>.</li>
      </ul>

      <section>
        <h4>Token Revocation Request</h4>

        <p>An example revocation request is below.</p>

        <pre class="example nohighlight">POST https://example.org/token HTTP/1.1
  Content-Type: application/x-www-form-urlencoded
  Accept: application/json

  action=revoke
  &token=xxxxxxxx</pre>

        <p>As described in [[!RFC7009]], the revocation endpoint responds with HTTP 200 for both the case where the token was successfully revoked, or if the submitted token was invalid.</p>
      </section>
    </section>


    <section>
      <h2>Security Considerations</h2>

      <p>In addition to the security considerations in OAuth 2.0 Core [[RFC6749]] and OAuth 2.0 Threat Model and Security Considerations [[RFC6819]], the additional considerations apply.</p>

      <section>
        <h3>Differing User Profile URLs</h3>

        <p>Clients will initially prompt the user for their profile URL in order to discover the necessary endpoints to perform authentication or authorization. However, there may be slight differences between the URL that the user initially enters vs what the system considers the user's canonical profile URL.</p>

        <p>For example, a user might enter <code>user.example.net</code> in a login interface, and the client may assume a default scheme of <code>http</code>, providing an initial profile URL of <code>http://user.example.net</code>. Once the authentication or authorization flow is complete, the response in the <code>me</code> parameter might be the canonical <code>https://user.example.net/</code>. In some cases, user profile URLs have a full path component such as <code>https://example.net/username</code>, but users may enter just <code>example.net</code> in the login interface.</p>

        <p>Upon validation, clients MUST check the <code>me</code> value from the <a href="#profile-url-response">profile URL response</a> or <a href="#access-token-response">access token response</a>, and take the following validation steps:</p>

        <ol>
          <li>It MUST follow any permanent redirections from this URL to discover the canonical profile URL, in the same manner as <a href="#discovery-by-clients">initial profile URL discovery</a>.</li>
          <li>It MUST verify that the canonical profile URL is on the same domain as the initially-entered profile URL.</li>
          <li>It MUST verify that the canonical profile URL declares the same <code>authorization_endpoint</code> as the initially-entered profile URL.</li>
        </ol>

        <p>These steps ensure that an authorization endpoint is not able to issue valid responses for arbitrary profile URLs, and that users on a shared domain cannot forge authorization on behalf of other users of that domain.</p>

      </section>

      <section>
        <h3>Preventing Phishing and Redirect Attacks</h3>

        <p>Authorization servers SHOULD fetch the <code>client_id</code> provided in the authentication or authorization request in order to provide users with additional information about the request, such as the application name and logo. If the server does not fetch the client information, then it SHOULD take additional measures to ensure the user is provided with as much information as possible about the request.</p>

        <p>The authorization server SHOULD display the full <code>client_id</code> on the authorization interface, in addition to displaying the fetched application information if any. Displaying the <code>client_id</code> helps users know that they are authorizing the expected application.</p>

        <p>Since all IndieAuth clients are public clients, and no client authentication is used, the only measure available to protect against some attacks described in [[RFC6819]] is strong verification of the client's <code>redirect_uri</code>. If the <code>redirect_uri</code> scheme, host or port differ from that of the <code>client_id</code>, then the authorization server MUST either verify the redirect URL as described in <a href="#redirect-url">Redirect URL</a>, or display the redirect URL to the user so they can inspect it manually.</p>
      </section>

    </section>

    <section>
      <h2>IANA Considerations</h2>

      <p>The link relation types below are documented to be registered by IANA per Section 6.2.1 of [[!RFC8288]]:</p>

      <dl>
        <dt>Relation Name:</dt>
        <dd>authorization_endpoint</dd>

        <dt>Description:</dt>
        <dd>Used for discovery of the OAuth 2.0 authorization endpoint given an IndieAuth profile URL.</dd>

        <dt>Reference:</dt>
        <dd><a href="https://indieauth.spec.indieweb.org/">IndieAuth Specification (https://indieauth.spec.indieweb.org/)</a></dd>
      </dl>

      <dl>
        <dt>Relation Name:</dt>
        <dd>token_endpoint</dd>

        <dt>Description:</dt>
        <dd>Used for discovery of the OAuth 2.0 token endpoint given an IndieAuth profile URL.</dd>

        <dt>Reference:</dt>
        <dd><a href="https://indieauth.spec.indieweb.org/">IndieAuth Specification (https://indieauth.spec.indieweb.org/)</a></dd>
      </dl>

      <dl>
        <dt>Relation Name:</dt>
        <dd>redirect_uri</dd>

        <dt>Description:</dt>
        <dd>Used for discovery of the OAuth 2.0 redirect URI given an IndieAuth client ID.</dd>

        <dt>Reference:</dt>
        <dd><a href="https://indieauth.spec.indieweb.org/">IndieAuth Specification (https://indieauth.spec.indieweb.org/)</a></dd>
      </dl>
    </section>

    <!--
    <section class="appendix informative">
      <h2>Extensions</h2>

      <p>The following Webmention Extension Specifications have 2+ interoperable implementations live on the web and are thus listed here:</p>

    </section>
  -->

    <section class="appendix informative">
      <h2>Resources</h2>

      <p>
        <ul>
          <!-- <li><a href="https://indieauth.rocks">Test Suite and Debug Tool</a></li> -->
          <li><a href="https://indieweb.org/Category:IndieAuth">More IndieAuth resources</a></li>
          <li><a href="https://indieweb.org/obtaining-an-access-token">Implementation guide for obtaining an access token using IndieAuth</a></li>
          <li><a href="https://indieweb.org/indieauth-for-login">Implementation guide for authenticating users without obtaining an access token</a></li>
        </ul>
      </p>

      <section class="appendix informative">
        <h3>Articles</h3>

        <p>You can find a list of <a href="https://indieweb.org/IndieAuth#Articles">articles about IndieAuth</a> on the IndieWeb wiki.</p>
      </section>

      <section class="appendix informative">
        <h3>Implementations</h3>

        <p>You can find a list of <a href="https://indieauth.net/implementations">IndieAuth implementations</a> on indieauth.net</p>
      </section>

    </section>

    <section class="appendix">
      <h2>Acknowledgements</h2>

      <p>The editor wishes to thank the <a href="https://indieweb.org/">IndieWeb</a>
        community and other implementers for their support, encouragement and enthusiasm,
        including but not limited to: Amy Guy, Barnaby Walters, Benjamin Roberts, Bret Comnes, Christian Weiske, Dmitri Shuralyov, François Kooman, Jeena Paradies, Martijn van der Ven, Sebastiaan Andeweg, Sven Knebel, and Tantek Çelik.</p>
    </section>

    <section class="appendix informative">
      <h2>Change Log</h2>

      <section>
        <h3>Changes from 09 August 2020 to this version</h3>
        <ul>
          <li>Make the <code>me</code> parameter optional (but recommended) in the authorization request</li>
          <li>Add the option of returning profile information in the response as well as defining profile scopes</li>
          <li>Incorporate PKCE into the spec</li>
        </ul>
      </section>

      <section>
        <h3>Changes from 25 January 2020 to 09 August 2020</h3>
        <ul>
          <li>Drop the <code>me</code> parameter from the token endpoint request</li>
          <li>Consolidate the authentication and authorization sections into a single section, describing only the difference which is the response returned.</li>
          <li>Drop the section describing communication between token endpoints and authorization endpoints as it was underused</li>
          <li>Editorial changes and rearranging sections</li>
        </ul>
      </section>

      <section>
        <h3>Changes from 03 March 2019 to 25 January 2020</h3>
        <ul>
          <li>Use "authentication" and "authorization" more consistently in paragraphs and diagrams</li>
          <li>Clarify that "Authorization Code Flow" is "OAuth 2.0 Authorization Code Flow"</li>
          <li>Minor typo fixes</li>
        </ul>
      </section>

      <section>
        <h3>Changes from 07 July 2018 to 03 March 2019</h3>
        <ul>
          <li>Updates the spec header to note that it is a living standard</li>
          <li>Minor typo fixes</li>
        </ul>
      </section>

      <section>
        <h3>Changes from <a href="https://www.w3.org/TR/2018/NOTE-indieauth-20180123/">23 January 2018</a> to 07 July 2018</h3>
        <ul>
          <li>Replaced references to RFC 5988 with RFC 8288 (<a href="https://github.com/indieweb/indieauth/commit/69d1d51e9541d8b76233793099b359de898a7154">diff</a>)</li>
          <li>Added <code>Accept: application/json</code> header to example requests (<a href="https://github.com/indieweb/indieauth/commit/bfa39332ea3a38ff31efb7101db5b20f2ecbaff7">diff</a>)</li>
        </ul>
      </section>

      <section>
        <h3>Changes from 07 July 2018 to 03 March 2019</h3>

        <ul>
          <li>Added missing ampersand in HTTP redirect example (<a href="https://github.com/indieweb/indieauth/commit/ffaf128e01712ca38f3a7dd412749c2bf2f1c99a">diff</a>)</li>
          <li>Fixed broken references section (<a href="https://github.com/indieweb/indieauth/commit/7f90282274d2703363265d0914b90ce7be8ac0b6">diff</a>)</li>
          <li>Fixed internal link to redirect examples (<a href="https://github.com/indieweb/indieauth/commit/e3eef35c2d16e4a4a00767c8da4ac55ad02d7bc0">diff</a>)</li>
        </ul>
      </section>
    </section>

    <script>
    // After text is selected (mouseup), find the closest element that has an ID
    // attribute, and update the browser location bar to include that as the fragment
    document.body.onmouseup = function(){
      var selection;
      if(selection=window.getSelection()) {
        var range = selection.getRangeAt(0);
        var el = range.startContainer;
        while((el=el.parentElement) && !el.attributes["id"]);
        var hash = el.attributes["id"].value;
        if(history.replaceState) {
          history.replaceState(null, null, "#"+hash);
        }
      }
    };
    </script>
  </body>
</html>
