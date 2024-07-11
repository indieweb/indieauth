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
          publishDate: "2024-07-11",
          specStatus: "NOTE", /* for loading w3c CSS */
          previousPublishDate: "2022-02-12",
          previousMaturity: "LS",
          previousVersionURL: "https://indieauth.spec.indieweb.org/20220212/",
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
                href: 'https://github.com/indieweb/indieauth/commits/main'
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
            },
            "ClientIDMetadataDocument": {
              title: "OAuth Client ID Metadata Document",
              href: "https://datatracker.ietf.org/doc/html/draft-parecki-oauth-client-id-metadata-document",
              authors: ["Aaron Parecki", "Emelia Smith"],
              status: "Internet-Draft",
              publisher: "IETF"
            },
            "ClientMetadata": {
              title: "OAuth Dynamic Client Registration Metadata",
              href: "https://www.iana.org/assignments/oauth-parameters/oauth-parameters.xhtml#client-metadata",
              authors: ["IETF"],
              status: "Registry",
              publisher: "IANA"
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
          <li>Specifies a method of discovering the IndieAuth metadata (containing the authorization and token endpoints) given a user's profile URL</li>
          <li>Specifies a format for the Client ID (a resolvable URL containing client metadata)</li>
          <li>Client registration at the authorization endpoint is not necessary, since client IDs are resolvable URLs</li>
          <li>Redirect URL registration happens by verifying data fetched at the Client ID URL</li>
          <li>Specifies a mechanism for returning the user identifier and profile information for the user who authorized a request</li>
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
          <p>A Micropub client will attempt to obtain an OAuth 2.0 Bearer Token given a URL that allows the discovery of an Authorization Endpoint, and will use the token when making Micropub requests.</p>
        </section>

        <section>
          <h4>IndieAuth Client</h4>
          <p>An IndieAuth client is a client that is attempting to authenticate a user given a URL that allows the discovery of an Authorization Endpoint, but does not need an OAuth 2.0 Bearer Token.</p>
        </section>

      </section>
    </section>

    <section class="normative">
      <h2>Identifiers</h2>

      <section>
        <h3>Issuer Identifier</h3>

        <p>The issuer identifier of the authorization server, which is primarily used to prevent authorization server mix-up attacks. The issuer identifier is a URL that uses the "https" scheme and has no query or fragment components as defined in [[!RFC9207]], however, the identifier MUST also be a prefix of the <code>indieauth-metadata</code> URL. For example, for an <code>indieauth-metadata</code> endpoint <code>https://example.com/.well-known/oauth-authorization-server</code>, the issuer URL could be <code>https://example.com/</code>, or for a metadata URL of <code>https://example.com/wp-json/indieauth/1.0/metadata</code>, the issuer URL could be <code>https://example.com/wp-json/indieauth/1.0</code></p>

      </section>
      <section>
        <h3>User Profile URL</h3>

        <p>Users are identified by a [[!URL]]. Profile URLs MUST have either an <code>https</code> or <code>http</code> scheme, MUST contain a path component (<code>/</code> is a valid path), MUST NOT contain single-dot or double-dot path segments, MAY contain a query string component, MUST NOT contain a fragment component, MUST NOT contain a username or password component, and MUST NOT contain a port. Additionally, host names MUST be domain names and MUST NOT be ipv4 or ipv6 addresses.</p>

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

        <p>Clients are identified by a [[!URL]]. Client identifier URLs MUST have either an <code>https</code> or <code>http</code> scheme, MUST contain a path component, MUST NOT contain single-dot or double-dot path segments, MAY contain a query string component, MUST NOT contain a fragment component, MUST NOT contain a username or password component, and MAY contain a port. Additionally, host names MUST be domain names or a loopback interface and MUST NOT be IPv4 or IPv6 addresses except for IPv4 <code>127.0.0.1</code> or IPv6 <code>[::1]</code>.</p>
      </section>

      <section>
        <h3>URL Canonicalization</h3>

        <p>Since IndieAuth uses https/http URLs which fall under what [[!URL]] calls "<a href="https://url.spec.whatwg.org/#special-scheme">Special URLs</a>", a string with no path component is not a valid [[!URL]]. As such, if a URL with no path component is ever encountered, it MUST be treated as if it had the path <code>/</code>. For example, if a user provides <code>https://example.com</code> for Discovery, the client MUST transform it to <code>https://example.com/</code> when using it and comparing it.</p>

        <p>Since domain names are case insensitive, the host component of the URL MUST be compared case insensitively. Implementations SHOULD convert the host to lowercase when storing and using URLs.</p>

        <p>For ease of use, clients MAY allow users to enter just the host part of the URL, in which case the client MUST turn that into a valid URL before beginning the IndieAuth flow, by prepending either an <code>http</code> or <code>https</code> scheme and appending the path <code>/</code>. For example, if the user enters <code>example.com</code>, the client transforms it into <code>http://example.com/</code> before beginning discovery.</p>
      </section>

    </section>

    <section class="normative">
      <h2>Discovery</h2>

      <p>This specification uses the link rel registry as defined by [[!HTML]]
        for both HTML and HTTP link relations.</p>

      <section>
        <h3>Discovery by Clients</h3>

        <p>Clients need to discover a few pieces of information when a user signs in. The client needs to discover the user's <code>indieauth-metadata</code> endpoint, which provides the location of the IndieAuth server's authorization endpoint and token endpoint, as well as other relevant information for the client. Clients MUST start by making a GET or HEAD request to [[!Fetch]] the user provided URL to discover the necessary values. Clients MUST follow HTTP redirects (up to a self-imposed limit). When using the Authorization flow to obtain an access token for use at another endpoint, such as a [[?Micropub]] endpoint, the client will also discover the <code>micropub</code> endpoint.</p>

        <p>Clients MUST check for an HTTP <code>Link</code> header [[!RFC8288]] with a <code>rel</code> value of <code>indieauth-metadata</code>. If the content type of the document is HTML, then the client MUST check for an HTML <code>&lt;link&gt;</code> element with a <code>rel</code> value of <code>indieauth-metadata</code>. If more than one of these is present, the first HTTP <code>Link</code> header takes precedence, followed by the first <code>&lt;link&gt;</code> element in document order.</p>

        <p>The URLs discovered MAY be relative URLs, in which case the client MUST resolve them relative to the current document URL according to [[!URL]].</p>

        <p>Clients MAY initially make an HTTP HEAD request [[!RFC7231]] to follow redirects and check for the <code>Link</code> header before making a GET request.</p>

        <p>In the event there is no <code>indieauth-metadata</code> URL provided, for compatibility with previous revisions of IndieAuth, the client SHOULD look for an HTTP <code>Link</code> header and HTML <code>&lt;link&gt;</code> element with a <code>rel</code> value of <code>authorization_endpoint</code> (and optionally <code>token_endpoint</code>) following the same order of predence as described above.</p>

        <p class="advisement">
          Note that the recommendation of looking for the <code>rel=authorization_endpoint</code> and <code>rel=token_endpoint</code> are included for backwards compatibility with previous IndieAuth profiles and may be removed from this specification in the future after wide enough adoption of the newer <code>indieauth-metadata</code> discovery method.
        </p>

         <section>
            <h4>IndieAuth Server Metadata</h4>

            <p>IndieAuth metadata adopts OAuth 2.0 Authorization Server Metadata [[RFC8414]], with the notable difference that discovery of the URL happens via the IndieAuth link relation rather than the <code>.well-known</code> discovery method specified by RFC8414. For compatibility with other OAuth 2.0 implementations, use of the <code>.well-known</code> path as defined in RFC8414 is RECOMMENDED but optional.</p>

            <p>The metadata endpoint returns information about the server as a JSON object with the following properties:</p>

            <ul>
              <li><code>issuer</code> - The server's issuer identifier.</li>
              <li><code>authorization_endpoint</code> - The Authorization Endpoint</li>
              <li><code>token_endpoint</code> - The Token Endpoint</li>
              <li><code>introspection_endpoint</code> - The Introspection Endpoint</li>
              <li><code>introspection_endpoint_auth_methods_supported</code> (optional) - JSON array containing a list of client authentication methods supported by this introspection endpoint.
              <li><code>revocation_endpoint</code> (optional) - The Revocation Endpoint</li>
              <li><code>revocation_endpoint_auth_methods_supported</code> (optional) - JSON array containing the value <code>"none"</code>. If a revocation endpoint is provided, this property should also be provided with the value <code>["none"]</code>, since the omission of this value defaults to <code>client_secret_basic</code> according to [[RFC8414]].</li>
              <li><code>scopes_supported</code> (recommended) - JSON array containing scope values supported by the IndieAuth server. Servers MAY choose not to advertise some supported scope values even when this parameter is used.</li>
              <li><code>response_types_supported</code> (optional) - JSON array containing the response_type values supported. This differs from [RFC8414] in that this parameter is OPTIONAL and that, if omitted, the default is <code>code</code></li>
              <li><code>grant_types_supported</code> (optional) - JSON array containing grant type values supported. If omitted, the default value differs from [RFC8414] and is <code>authorization_code</code></li>
              <li><code>service_documentation</code> (optional) - URL of a page containing human-readable information that developers might need to know when using the server. This might be a link to the IndieAuth spec or something more personal to your implementation.
              <li><code>code_challenge_methods_supported</code> - JSON array containing the methods supported for PKCE. This parameter differs from [RFC8414] in that it is not optional as PKCE is REQUIRED.</li>
              <li><code>authorization_response_iss_parameter_supported</code> (optional) - Boolean parameter indicating whether the authorization server provides the <code>iss</code> parameter. If omitted, the default value is false. As the <code>iss</code> parameter is REQUIRED, this is provided for compatibility with OAuth 2.0 servers implementing the parameter.</li>
              <li><code>userinfo_endpoint</code> (optional) - The User Info Endpoint</li>
            </ul>

        <pre class="example nohighlight">HTTP/1.1 200 OK
Content-Type: application/json

{
  "issuer": "https://indieauth.example.com/",
  "authorization_endpoint": "https://indieauth.example.com/auth",
  "token_endpoint": "https://indieauth.example.com/token",
  "code_challenge_methods_supported": ["S256"]
}</pre>

         </section>

      </section>

      <section>
        <h3>Client Information Discovery</h3>

        <p>When an authorization server presents its <a href="https://www.oauth.com/oauth2-servers/authorization/the-authorization-interface/">authorization interface</a>, it will often want to display some additional information about the client beyond just the <code>client_id</code> URL, in order to better inform the user about the request being made. Additionally, the authorization server needs to know the list of redirect URLs that the client is allowed to redirect to.</p>

        <p>Clients SHOULD publish an OAuth Client ID Metadata Document [[!ClientIDMetadataDocument]] at the client identifier URL.</p>

        <p>The authorization server SHOULD [[!Fetch]] the URL to find more information about the client.</p>

        <p>If the <code>client_id</code> contains the permitted IPv4 and IPv6 addresses <code>127.0.0.1</code> or <code>[::1]</code>, or if the domain name resolves to these addresses, the authorization endpoint MUST NOT fetch the <code>client_id</code>.</p>

        <p>Note that the server may want to perform some additional checks on the <code>client_id</code> before fetching it to avoid SSRF attacks. In particular, the server may want to resolve the domain name first and avoid fetching the document if the IP address is within the loopback range defined by [[RFC5735]] or any other implementation-specific internal IP address.</p>

        <section>
          <h4>Client Metadata</h4>

          <p class="advisement">This section is based on [[!ClientIDMetadataDocument]], and is included here for convenience, but the normative requirements in [[!ClientIDMetadataDocument]] take precedence.</p>

          <p>Clients SHOULD have a JSON [[!RFC7159]] document at their <code>client_id</code> URL containing client metadata defined in [[!RFC7591]], the minimum properties for an IndieAuth client defined below. The authorization server MAY cache the client metadata it discovers at the client ID URL and SHOULD respect cache-control headers and set reasonable defaults if none are present.</p>

          <ul>
            <li><code>client_id</code> - the client identifier. The authorization server MUST verify that the <code>client_id</code> in the document matches the <code>client_id</code> of the URL where the document was retrieved. The <code>client_uri</code> MUST be a prefix of the <code>client_id</code>.</li>
            <li><code>client_name</code> - (optional) Human readable name of the client to be presented on the consent screen</li>
            <li><code>client_uri</code> - URL of a webpage providing information about the client</li>
            <li><code>logo_uri</code> - (optional) URL that references a logo or icon for the client</li>
            <li><code>redirect_uris</code> - (optional) An array of redirect URIs
          </ul>

          <p>Additional metadata properties defined in [[!ClientMetadata]] MAY be added, with the understanding that some authorization servers may not recognize them.</p>

          <p>Clients SHOULD have a web page at their <code>client_uri</code> URL with basic information about the application, at least the application's name and icon. Authorization servers SHOULD display this URL to the user during authorization, so that the user can learn more about the application. Authorization servers SHOULD warn the user if the hostname of the <code>client_uri</code> is different from the hostname of the <code>client_id</code>.</p>

          <p>Note: Earlier versions of this specification recommended an HTML document with <a href="https://microformats.org/wiki/h-app"><code>h-app</code></a> Microformats as the <code>client_id</code>, and authorization servers may wish to continue to support this for backwards compatibility.</p>

        </section>

        <section>
          <h4>Redirect URL</h4>

          <p>If a client wishes to use a redirect URL that has a different host than their <code>client_id</code>, or if the redirect URL uses a custom scheme (such as when the client is a native application), then the client will need to explicitly list those redirect URLs in the <code>redirect_uri</code> property of the client metadata so that authorization endpoints can be sure it is safe to redirect users there. Authorization endpoints verifying that a <code>redirect_uri</code> is allowed for use by a client MUST look for an exact match of the given <code>redirect_uri</code> in the request against the list of <code>redirect_uri</code>s after resolving any relative URLs.</p>
         <p>When a client chooses to serve a web page as its client_id, the client MAY publish one or more <code>&lt;link&gt;</code> tags or <code>Link</code> HTTP headers with a <code>rel</code> attribute of <code>redirect_uri</code> at the <code>client_id</code> URL to be used by the authorization server.</p>

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
title IndieAuth Flow Diagram

participantgroup #lightgrey ****
participant Browser
end

participantgroup #lightgrey ****
participant Client
end

participantgroup #lightgrey ****
participant Profile URL
end

participantgroup #lightgrey **Authorization Server**
participant Metadata URL
participant Authorization Endpoint
participant Token Endpoint
end

Browser->Client:<stroke:1.5:#white> User enters a URL, and the\nclient canonicalizes the URL
Client->Profile URL:<stroke:1.5:#white> Client fetches URL to discover\n**rel=indieauth-metadata**
Client->Metadata URL:<stroke:1.5:#white> Client fetches metadata URL to find\n**authorization_endpoint**\nand **token_endpoint**
Browser<--Client:<stroke:1.5:#white> Client builds authorization request and\nredirects to **authorization_endpoint**
Browser->Authorization Endpoint:<stroke:1.5:#white> User visits their authorization endpoint and sees the authorization request
Authorization Endpoint->Client:<stroke:1.5:#white> Authorization endpoint fetches client information (name, icon)
Browser<--Authorization Endpoint:<stroke:1.5:#white> User authenticates, and approves the request. Authorization\nendpoint issues authorization code, builds redirect back to client.
Browser->Client:<stroke:1.5:#white> User's browser is redirected to\nclient with an **authorization code**
Client->Token Endpoint:<stroke:1.5:#white> Client exchanges authorization code for an \naccess token by making a POST request\nto the token_endpoint
Client<--Token Endpoint:<stroke:1.5:#white> Token endpoint verifies code and returns\ncanonical user profile URL with an access token
Client->Profile URL:<stroke:1.5:#white> Client fetches the user URL\nif it differs from the original,\nand confirms the it declares\nthe same authorization server
Browser<--Client:<stroke:1.5:#white> Client initiates login session\nand the user is logged in
---

https://sequencediagram.org/index.html#initialData=C4S2BsFMAIEkDsAmJIEECuwAW0Bi4B7Ad2gBEQBDAcwCcKBbAKEYAcKbQBjEN+YWguhbQAxOBBUs-GpACe0AFRKFrdlx4U+0AEI1iAZ0g1GkJMzYcQ3XtMHCxEqbTmLlqy9c3BoAYXGngEzN3dRsBIVFxSWkXZRULUK9oAAU9ADMQKGgAVQAlABkgxGYQqw0+cPsopxl5JQxsAhoQAC8KUAJ4aABlIwA3IyVSzy0AWUhgCkR2ihyC4fLvBqwm1vaQTugAUSQWAhA+BZtoABUCAGtTbd39w9Nixl0DIwBaAD4-FD4ALgAefWAeku3wAjAA6ACs3xERCwYEgbxyhho0ACRn00FmeXyABpMUhoNhIAAdeCcfxaTiaTpWCjiFqQDFEuaFT4Bd6pAgZLLYv4AoGQUGQ6Gw+GItlaNITThYRkswkEaDIfScAgDGikpQycAAXgOyEgFEwWBe9AmUxmQwlwHe40m00mLL5gIugvBUJhcOACN8FO8UuAMrlZvtM3lwEVGSQmoURsazTaHXgAH17nsDsAlKTNIhXBHLim07dMyonkRkb8Xi9rc6BUKPaLveK-dAAEboTKIDFxlYJ9abGQAR3QjO8OdJMmQMk4wCZiqUPdWiY2hZuGaGZeR72WS-7XR2iHTP3+LuB7pFXp92WR0D6IH0YCZspAKMXfaTqLXWhz0EMcuZb5rB+Q4jgCjA7u+K7XIexbvDWJ51uenpitAEFAVBRYZtAAZBhi5JfN4BxpE09B7tAAAU8AMJAeJWJ0ACUjx6OWRiVi8aHLpsB5HsAtauvWF4odeRiYsaAS0t6+h4j+FAsCwegDE+MAgaOYKocau5JqSmFaPe+igaJ8boZsqqIDRbYduAXbQJOL6QDObYUJw5wKtA+EBGCTHPDQcF+nxZ7CshTZIkYADkGKtsxN73jZkBTvZ3q5hGpLuVoRBgDgmiuIBnFdKZkBWn67xnAW0E8f5bqBY2PrWqiAAeMqaFQco5WR+XYU0+LQNmnCcIyc6la28ikecBxUJiKQAPLdCcsXDqOpIRoSsoKgWqZfoE1psSVVzccWFUCUFPo7V0OneOqIAZHK7U-jIwDoDQ8D6Cl1LwLS4DQOgN7yVymQwNi0DpdgXVOX1+gDaYjDWhy6R-U6CH8Uh1XNgR2HSrKSmfTe2Kkpd0BgEql1SjQGJpHo9DLTAqxUAcdI4tmBKqvAGQ0PQmME2Z5LsIyi0rfo1GGb2xldMi6peSxNBsfB-KI1Vl4owE+NvaA7RyoQNMi-1K4M0lK1fSJMXq81uYHEAA

Note: Set a viewbox matching "0 0 width height" and have the image scale, e.g.
viewbox="0 0 1169 1010" style="width: 100%; height: auto;"
*/ ?>

      <?= file_get_contents('authorization-flow-diagram.svg') ?>

      <ul>
        <li>The End-User enters a URL in the login form of the client and clicks "Sign in". The client canonicalizes the URL.</li>
        <li>The client discovers the End-User's IndieAuth server metadata endpoint by fetching the provided URL and looking for the <code>rel=indieauth-metadata</code> value</li>
        <li>The client discovers the server's authorization endpoint and token endpoint by fetching the metadata URL and looking for the <code>authorization_endpoint</code> and <code>token_endpoint</code> values</li>
        <li>The client builds the authorization request including its client identifier, requested scope, local state, and a redirect URI, and redirects the browser to the authorization endpoint</li>
        <li>The authorization endpoint fetches the client information from the client identifier URL in order to have an application name and icon to display to the user</li>
        <li>The authorization endpoint verifies the End-User, e.g. by logging in, and establishes whether the End-User grants or denies the client's request</li>
        <li>The authorization endpoint generates an authorization code and redirects the browser back to the client, including an authorization code in the URL</li>
        <li>The client exchanges the authorization code for an access token by making a POST request to the token endpoint. The token endpoint validates the authorization code, and responds with the End-User's canonical profile URL and an access token</li>
        <li>The client confirms the returned profile URL declares the same authorization server and accepts the profile URL</li>
      </ul>

      <p>Note: If the client is only trying to learn who the user is and does not need an access token, the client exchanges the authorization code for the user profile information at the Authorization Endpoint instead.</p>

      <section>
        <h3>Discovery</h3>

        <p>After obtaining a URL from the End-User, and optionally applying <a href="url-canonicalization">URL Canonicalization</a> to it, the client fetches the URL and looks for the <code>indieauth-metadata</code> rel values in the HTTP <code>Link</code> headers and HTML <code>&lt;link&gt;</code> tags as described in <a href="#discovery-by-clients"></a>.</p>

        <pre class="example nohighlight"><?= htmlspecialchars(
'Link: <https://indieauth.example.com/.well-known/oauth-authorization-server>; rel="indieauth-metadata"

<link rel="indieauth-metadata" href="https://indieauth.example.com/.well-known/oauth-authorization-server">') ?></pre>

        <p>The client fetches the metadata document and finds the <code>authorization_endpoint</code> and <code>token_endpoint</code> in the JSON body.</p>

        <pre class="example nohighlight">{
  "issuer": "https://indieauth.example.com/",
  "authorization_endpoint": "https://indieauth.example.com/auth",
  "token_endpoint": "https://indieauth.example.com/token",
  "code_challenge_methods_supported": ["S256"]
}</pre>

      </section>

      <section>
        <h3>Authorization Request</h3>

          <p>The client builds the authorization request URL by starting with the discovered <code>authorization_endpoint</code> URL and adding parameters to the query component.</p>

          <p>All IndieAuth clients MUST use PKCE ([[!RFC7636]]) to protect against authorization code injection and CSRF attacks. A non-canonical description of the PKCE mechanism is described below, but implementers should refer to [[RFC7636]] for details.</p>

          <p>Clients use a unique secret per authorization request to protect against authorization code injection and CSRF attacks. The client first generates this secret, which it can later use along with the authorization code to prove that the application using the authorization code is the same application that requested it.</p>

          <p>The client first creates a code verifier for each authorization request by generating a random string using the characters <code>[A-Z] / [a-z] / [0-9] / - / . / _ / ~</code> with a minimum length of 43 characters and maximum length of 128 characters. This value is stored on the client and will be used in the authorization code exchange step later.</p>

          <p>The client then creates the code challenge derived from the code verifier by calculating the SHA256 hash of the code verifier and <a href="https://datatracker.ietf.org/doc/html/rfc7636#appendix-A">Base64-URL-encoding</a> the result.</p>

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
            <li><code>me</code> - (optional) The URL that the user entered</li>
          </ul>

          <pre class="example nohighlight"><?= htmlspecialchars('
https://example.org/auth?response_type=code&
                         client_id=https://app.example.com/&
                         redirect_uri=https://app.example.com/redirect&
                         state=1234567890&
                         code_challenge=OfYAxt8zU2dAPDWQxTAUIteRzMsoj9QBdMIVEDOErUo&
                         code_challenge_method=S256&
                         scope=profile+create+update+delete&
                         me=https://user.example.net/') ?></pre>

          <p>The client SHOULD provide the <code>me</code> query string parameter to the authorization endpoint, either the exact value the user entered, or the value after applying <a href="url-canonicalization">URL Canonicalization</a>.</p>

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
            <li><code>iss</code> - The issuer identifier for client validation.</li>
          </ul>

          <pre class="example nohighlight"><?= htmlspecialchars(
'HTTP/1.1 302 Found
Location: https://app.example.com/redirect?code=xxxxxxxx&
                                           state=1234567890&
                                           iss=https%3A%2F%2Findieauth.example.com') ?></pre>

          <p>Upon the redirect back to the client, the client MUST verify:</p>

	  <ul>
	    <li>That the <code>state</code> parameter in the request is valid and matches the state parameter that it initially created, in order to prevent CSRF attacks. The state value can also store session information to enable development of clients that cannot store data themselves.</li>
	    <li>That the <code>iss</code> parameter in the request is valid and matches the issuer parameter provided by the Server Metadata endpoint during Discovery as outlined in OAuth 2.0 Authorization Server Issuer Identification[[!RFC9207]]. Clients MUST compare the parameters using simple string comparison. If the value does not match the expected issuer identifier, clients MUST reject the authorization response and MUST NOT proceed with the authorization grant. For error responses, clients MUST NOT assume that the error originates from the intended authorization server. </li>
	  </ul>

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
'POST https://indieauth.example.com/auth
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
'POST https://indieauth.example.com/token
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

          <p>If the client only needs to know the user who logged in, the client will exchange the authorization code at the <b>authorization endpoint</b>, and only the canonical user profile URL and possibly profile information is returned.</p>

          <p>The authorization endpoint verifies that the authorization code is valid, has not yet been used, and that it was issued for the matching <code>client_id</code> and <code>redirect_uri</code>, and checks that the provided <code>code_verifier</code> hashes to the same value as given in the <code>code_challenge</code> in the original authorization request. If the request is valid, then the endpoint responds with a JSON [[!RFC7159]] object containing the property <code>me</code>, with the canonical user profile URL for the user who signed in, and optionally the property <code>profile</code> with the user's profile information as defined in <a href="#profile-information">Profile Information</a>.</p>

          <pre class="example nohighlight"><?= htmlspecialchars(
'HTTP/1.1 200 OK
Content-Type: application/json

{
  "me": "https://user.example.net/"
}') ?></pre>

          <p>The resulting profile URL MAY be different from the URL provided to the client for discovery. This gives the authorization server an opportunity to canonicalize the user's URL, such as correcting <code>http</code> to <code>https</code>, or adding a path if required. See <a href="#differing-user-profile-urls">Differing User Profile URLs</a> for security considerations client developers should be aware of.</p>

          <p>See OAuth 2.0 [[!RFC6749]] <a href="https://tools.ietf.org/html/rfc6749#section-5.2">Section 5.2</a> for how to respond in the case of errors or other failures.</p>
        </section>

        <section>
          <h4>Access Token Response</h4>

          <p>When the client receives an authorization code that was requested with one or more scopes that will result in an access token being returned, the client will exchange the authorization code at the <b>token endpoint</b>.</p>

          <p>The token endpoint needs to verify that the authorization code is valid, and that it was issued for the matching <code>client_id</code> and <code>redirect_uri</code>, contains at least one <code>scope</code>, and checks that the provided <code>code_verifier</code> hashes to the same value as given in the <code>code_challenge</code> in the original authorization request. If the authorization code was issued with no <code>scope</code>, the token endpoint MUST NOT issue an access token, as empty scopes are invalid per Section 3.3 of OAuth 2.0 [[!RFC6749]].</p>

          <p>The specifics of how the token endpoint verifies the authorization code are out of scope of this document, as typically the authorization endpoint and token endpoint are part of the same system and can share storage or another private communication mechanism.</p>

          <p>If the request is valid, then the token endpoint can generate an access token and return the appropriate response. The token response is a JSON [[!RFC7159]] object containing:</p>

          <ul>
            <li><code>access_token</code> (required) - the OAuth 2.0 Bearer Token [[!RFC6750]].</li>
            <li><code>me</code> (required) - the canonical user profile URL for the user this access token corresponds to.</li>
            <li><code>profile</code> (optional) - the user's profile information as defined in <a href="#profile-information">Profile Information</a>.</li>
            <li><code>expires_in</code> (recommended) - The lifetime in seconds of the access token.</li>
            <li><code>refresh_token</code> (optional) - The refresh token, which can be used to obtain new access tokens as defined in <a href="#refresh-tokens">Refresh Tokens</a>.</li>
          </ul>

          <p>For example:</p>

          <pre class="example nohighlight">HTTP/1.1 200 OK
Content-Type: application/json

{
  "access_token": "XXXXXX",
  "token_type": "Bearer",
  "scope": "create update delete",
  "me": "https://user.example.net/"
}</pre>

          <p>The resulting profile URL MAY be different from the URL provided to the client for discovery. This gives the authorization server an opportunity to canonicalize the user's URL, such as correcting <code>http</code> to <code>https</code>, or adding a path if required. See <a href="#differing-user-profile-urls">Differing User Profile URLs</a> for security considerations client developers should be aware of.</p>

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

          <ul>
            <li><code>name</code> - Name the user wishes to provide to the client. This is not to be considered by the client to be the full name of the user. Clients are expected to use this as a display name.</li>
            <li><code>url</code> - URL of the user's website. The <code>url</code> is not guaranteed to match the <code>me</code> URL, and may even have a different host. For example, a multi-author website may use the website's URL as the <code>me</code> URL, but return each specific author's own personal website in the profile data.</li>
            <li><code>photo</code> - A photo or image that the user wishes clients to use as a profile image.</li>
            <li><code>email</code> - (if email scope is requested) The email address a user wishes to provide to the client.</li>
          </ul>

          <p>The client MUST NOT treat the information in the <code>profile</code> object as canonical or authoritative, and MUST NOT make any authentication or identification decisions based on this information.</p>

          <p>For example, attempting to use the <code>email</code> returned in the profile object as a user identifier will lead to security holes, as any user can create an authorization endpoint that returns any email address in the profile response. A client using the email address returned here should treat it the same as if it had been hand-entered in the client application and go through its own verification process before using it.</p>


        </section>

      </section>

      <section>
        <h3>Authorization Server Confirmation</h3>
        <span id="differing-user-profile-urls"></span><!-- preserve old fragment identifier -->

        <p>Clients will initially prompt the user to enter a URL in order to discover the necessary endpoints to perform authentication or authorization. However, there may be differences between the URL that the user initially enters and the final resulting profile URL as returned by the authorization server. The differences may be anything from a differing scheme (http vs https), to even a URL with a different host.</p>

        <p>Upon receiving the <code>me</code> URL in the response from the authorization server (either in the <a href="#profile-url-response">profile URL response</a> or <a href="#access-token-response">access token response</a>) the client MUST verify the authorization server is authorized to make claims about the profile URL returned by confirming the returned profile URL declares the same authorization server.</p>

        <p>The client MUST perform <a href="#discovery-by-clients">endpoint discovery</a> on the returned <code>me</code> URL and verify that URL declares the same authorization endpoint as was discovered in the initial discovery step, <b>unless</b> the returned <code>me</code> URL is an exact match of the initially entered URL or any of the URLs encountered during the <a href="#discovery-by-clients">initial endpoint discovery</a>, either from a possible redirect chain or as the final value.</p>

        <p>Note that the step of checking for the existence of the returned profile URL in the initial endpoint discovery is an optional optimization step which may save the client from possibly needing to make another HTTP request. This step may be skipped for simplicity, as discovering the authorization server from the returned profile URL is sufficient to confirm the returned profile URL declares the same authorization server.</p>

        <p>This verification step ensures that an authorization endpoint is not able to issue valid responses for arbitrary profile URLs, and that users on a shared domain cannot forge authorization on behalf of other users of that domain.</p>

        <h4>Examples</h4>

        <p>The following are some non-normative examples of real-world scenarios in which the initial user-entered URL may be different from the final resulting profile URL returned by the authorization server.</p>

        <h5>Basic Redirect</h5>

        <p>The basic redirect example covers cases such as:
          <ul>
            <li>entering a domain with a www prefix and resolving it to the main domain</li>
            <li>entering a URL with no scheme or with http and resolving it to an https URL</li>
            <li>entering a short domain and resolving it to a different longer domain</li>
          </ul>
        </p>

        <p>Steps</p>

        <ol>
          <li>The user enters <code>www.example.com</code> into the client</li>
          <li>The client applies the steps from URL canonicalization to turn it into a URL: <code>http://www.example.com/</code></li>
          <li>The client makes a GET request to <code>http://www.example.com/</code></li>
          <li>The server returns a 301 redirect to <code>https://example.com/</code></li>
          <li>The client makes a GET request to <code>https://example.com/</code> and finds the authorization endpoint</li>
          <li>The client does the IndieAuth flow with that authorization endpoint. This results in the profile URL response with a <code>me</code> value of <code>https://example.com/</code> as the canonical Profile URL.</li>
          <li>The client sees that the canonical Profile URL matches the URL that the authorization endpoint was discovered at, and accepts the value <code>https://example.com/</code></li>
        </ol>

        <h5>Service Domain to Subdomain</h5>

        <ol>
          <li>The user enters <code>example.com</code> into the client</li>
          <li>The client applies the steps from URL canonicalization to turn it into a URL: <code>http://example.com/</code></li>
          <li>The client makes a GET request to <code>http://example.com/</code></li>
          <li>The server returns a 301 redirect to <code>https://example.com/</code></li>
          <li>The client makes a GET request to <code>https://example.com/</code> and finds the authorization endpoint, <code>https://login.example.com</code></li>
          <li>The client does the IndieAuth flow with <code>https://login.example.com</code>. This results in the profile URL response with a <code>me</code> value of <code>https://username.example.com/</code> as the canonical Profile URL.</li>
          <li>This is the first time the client has seen this URL, so must verify the relationship between this subdomain and the authorization server. It fetches <code>https://username.example.com/</code> and finds the same authorization endpoint <code>https://login.example.com</code></li>
          <li>The client accepts the <code>me</code> value of <code>https://username.example.com/</code></li>
        </ol>

        <h5>Service Domain to Path</h5>

        <ol>
          <li>The user enters <code>example.com</code> into the client</li>
          <li>The client applies the steps from URL canonicalization to turn it into a URL: <code>http://example.com/</code></li>
          <li>The client makes a GET request to <code>http://example.com/</code></li>
          <li>The server returns a 301 redirect to <code>https://example.com/</code></li>
          <li>The client makes a GET request to <code>https://example.com/</code> and finds the authorization endpoint, <code>https://login.example.com</code></li>
          <li>The client does the IndieAuth flow with <code>https://login.example.com</code>. This results in the profile URL response with a <code>me</code> value of <code>https://example.com/username</code> as the canonical Profile URL.</li>
          <li>This is the first time the client has seen this URL, so must verify the relationship between this subdomain and the authorization server. It fetches <code>https://example.com/username</code> and finds the same authorization endpoint <code>https://login.example.com</code></li>
          <li>The client accepts the <code>me</code> value of <code>https://example.com/username</code></li>
        </ol>

        <h5>Email-like Identifier</h5>

        <ol>
          <li>The user enters <code>user@example.com</code> into the client</li>
          <li>The client applies the steps from URL canonicalization to turn it into a URL: <code>http://user@example.com/</code></li>
          <li>The client makes a GET request to <code>http://example.com/</code> providing the HTTP Basic Auth username <code>user</code></li>
          <li>The server returns a 301 redirect to <code>https://example.com/</code></li>
          <li>The client makes a GET request to <code>https://example.com/</code> and finds the authorization endpoint, <code>https://login.example.com</code>
            <ul><li>Note: Alternatively the server can advertise the authorization endpoint in the response to the <code>http://user@example.com/</code> request directly instead of needing a separate redirect</li></ul>
          </li>
          <li>The client does the IndieAuth flow with <code>https://login.example.com</code>, providing the user-entered <code>user@example.com</code> in the request as a hint to the server. This results in the profile URL response with a <code>me</code> value of <code>https://example.com/username</code> as the canonical Profile URL.</li>
          <li>This is the first time the client has seen this URL, so must verify the relationship between this subdomain and the authorization server. It fetches <code>https://example.com/username</code> and finds the same authorization endpoint <code>https://login.example.com</code></li>
          <li>The client accepts the <code>me</code> value of <code>https://example.com/username</code></li>
        </ol>

      </section>

      <section>
        <h3>Refresh Tokens</h3>

        <p>Refresh tokens are issued to the client by the authorization server and MAY be used at any time to obtain a new access token, usually when the current access token becomes invalid or expires, or to obtain a new token with identical or narrower scope (access tokens may have a shorter lifetime and fewer permissions than authorized by the resource owner).</p>

        <p>Use of short-lived access tokens and the offering of refresh tokens is RECOMMENDED, however, issuing a refresh token is at the discretion of the authorization server, and may be issued based on properties of the client, properties of the request, policies within the authorization server, a choice by the user authorizing the request or any other criteria.  If the authorization server issues a refresh token, it is included in the return when issuing an access token. If the authorization server decides not to issue refresh tokens, or the refresh token expires, the client MAY obtain new access tokens by starting the authorization flow over.</p>

        <p>Authorization servers MAY revoke refresh tokens automatically in case of a security event, such as a password change or a logout at the authorization server, or when they are redeemed, in which case a new refresh token MAY be provided. Refresh tokens SHOULD expire if the client has been inactive for some time, i.e., the refresh token has not been used to obtain fresh access tokens for some time.  The expiration time is at the discretion of the authorization server.</p>

        <section>
          <h4>Refreshing an Access Token</h4>

          <p>To refresh an access token, the client makes a POST request to the token endpoint to exchange the refresh token for the new access token. The POST request contains the following parameters:</p>

          <ul>
            <li><code>grant_type=refresh_token</code></li>
            <li><code>refresh_token</code> - The refresh token previously offered to the client.</li>
            <li><code>client_id</code> - The client ID that was used when the refresh token was issued.</li>
            <li><code>scope</code> (optional) - The client may request a token with the same or fewer scopes than the original access token. If omitted, is treated as equal to the original scopes granted.</li>
          </ul>

          <p>For example:</p>

          <pre class="example nohighlight"><?= htmlspecialchars(
'POST https://example.org/token
Content-type: application/x-www-form-urlencoded
Accept: application/json

grant_type=refresh_token
&refresh_token=xxxxxxxx&client_id=https://app.example.com
') ?></pre>

          <p>If valid and authorized, the authorization server issues an access token as noted in <a href="#access-token-response">Access Token Response</a>. The authorization server MAY issue a new refresh token, in which case the client MUST discard the old refresh token and replace it with the new refresh token. The authorization server MAY revoke the old refresh token after issuing a new refresh token to the client.  If a new refresh token is issued, the refresh token scope MUST be identical to that of the refresh token included by the client in the request.</p>

          <p>Refresh tokens SHOULD expire if the client has not used the refresh token to obtain new access tokens for some time. The expiration time is at the discretion of the authorization server.</p>

        </section>
      </section>

    </section>

    <section class="normative">
      <h3>Access Token Verification</h3>

      <p>In OAuth 2.0, access tokens are opaque to clients, so clients do not need to know anything about the contents or structure of the token itself. Additionally, endpoints that clients make requests to, such as [[?Micropub]] endpoints, may not even understand how to interpret tokens if they were issued by a standalone token endpoint. If the token endpoint is not tightly integrated with the resource server the client is interacting with, then the resource server needs a way to verify access tokens that it receives from clients. If the token endpoint and Micropub endpoint are tightly coupled, then they can of course use an internal mechanism to verify access tokens.</p>

      <p>Token endpoints that intend to interoperate with other endpoints MUST use the mechanism described below to allow resource servers such as Micropub endpoints to verify access tokens.</p>

      <section>
        <h4>Access Token Verification Request</h4>

        <p>If a resource server needs to verify that an access token is valid, it may do so using Token Introspection. IndieAuth extends OAuth 2.0 Token Introspection [[!RFC7662]] by adding that the  introspection response MUST include an additional parameter, <code>me</code>.</p>
        <p>Note that the request to the endpoint will not contain any user-identifying information, so the resource server (e.g. Micropub endpoint) will need to know via out-of-band methods which token endpoint is in use.</p>
        <p>The resource server SHOULD make a POST request to the token endpoint containing the Bearer token in the <code>token</code> parameter, which will generate a token verification response. The endpoint MUST also require some form of authorization to access this endpoint and MAY identify that in the <code>introspection_endpoint_auth_methods_supported</code> parameter of the metadata response. If the authorization is insufficient for the request, the authorization server MUST respond with an HTTP 401 code.</p>

        <pre class="example nohighlight"><?= htmlspecialchars(
  'POST https://indieauth.example.com/introspect
Content-type: application/x-www-form-urlencoded
Accept: application/json
Authorization: Bearer xxxxxxxx

token=xxxxxxxx') ?></pre>
      </section>

      <section>
        <h4>Access Token Verification Response</h4>

        <p>The token endpoint verifies the access token (how this verification is done is up to the implementation), and returns information about the token:</p>

        <ul>
          <li><code>active</code> - (required) Boolean indicator of whether or not the presented token is currently active</li>
          <li><code>me</code> - (required) The profile URL of the user corresponding to this token</li>
          <li><code>client_id</code> - The client ID associated with this token</li>
          <li><code>scope</code> - A space-separated list of scopes associated with this token</li>
          <li><code>exp</code> - (optional) Integer timestamp, measured in the number of seconds since January 1 1970 UTC, indicating when this token will expire</li>
          <li><code>iat</code> - (optional) Integer timestamp, measured in the number of seconds since January 1 1970 UTC, indicating when this token was originally issued</li>
        </ul>

        <pre class="example nohighlight">HTTP/1.1 200 OK
Content-Type: application/json

{
  "active": "true",
  "me": "https://user.example.net/",
  "client_id": https://app.example.com/",
  "scope": "create update delete"
  "exp": "1632443647",
  "iat": "1632443147"
}</pre>

        <p>Specific implementations MAY include additional parameters as top-level JSON properties. Clients SHOULD ignore parameters they don't recognize.</p>

        <p>If the token is not valid, the endpoint still MUST return a 200 Response, with the only parameter being active(with its value set to "false"). The response SHOULD NOT include any additional information about an inactive token, including why the token is inactive.</p>

        <pre class="example nohighlight">HTTP/1.1 200 OK
Content-Type: application/json

{
  "active": "false",
}</pre>

      </section>
    </section>


    <section class="normative">
      <h3>Token Revocation</h3>

      <p>A client may wish to explicitly disable an access token that it has obtained, such as when the user signs out of the client. IndieAuth implements OAuth 2.0 Token Revocation [[!RFC7009]] using the revocation endpoint defined in the server metadata:</p>

      <section>
        <h4>Token Revocation Request</h4>

        <p>An example revocation request is below.</p>

        <pre class="example nohighlight">POST https://indieauth.example.com/revocation HTTP/1.1
Content-Type: application/x-www-form-urlencoded
Accept: application/json

token=xxxxxxxx</pre>

        <p>As described in [[!RFC7009]], the revocation endpoint responds with HTTP 200 for both the case where the token was successfully revoked, or if the submitted token was invalid.</p>
      </section>

      <p class="advisement">
        A previous version of the spec used the token endpoint as the revocation endpoint with the additional parameter <code>action=revoke</code>. Servers that wish to support older versions of clients may wish to retain this behavior for backwards compatibility.</p>
      </p>
    </section>


    <section>
      <h2>Accessing Protected Resources</h2>

      <p>The client accesses protected resources by presenting the access token to the resource server.  The resource server MUST validate the access token and ensure that it has not expired and that its scope covers the requested resource.</p>

      <section>
        <h3>Error Responses</h3>

        <p>When a request fails, the resource server responds using the appropriate HTTP status codes, and includes one of the following error codes in the response:</p>

        <ul>
          <li><code>"invalid_request"</code> - The request is not valid. The resource server SHOULD respond with HTTP 400</li>
          <li><code>"invalid_token"</code> - The access token provided is expired, revoked, or invalid. The resource server SHOULD respond with HTTP 401</li>
          <li><code>"insufficient_scope"</code> - The request requires higher privileges than provided. The resource server SHOULD respond with HTTP 403</li>
        </ul>

        <p>If the requests lacks any authentication information, the resource server SHOULD NOT include an error code or other information.</p>
      </section>
    </section>

    <section>
      <h2>User Information</h2>

      <p>A client may wish to refresh or receive additional information about the authenticated end user outside of the authorization response. To fetch the user's profile information, the client makes a GET request to the userinfo endpoint, providing an access token that was issued with the <code>profile</code> and/or <code>email</code> scopes.</p>

      <pre class="example nohighlight">GET /userinfo HTTP/1.1
Host: indieauth.example.com
Authorization: Bearer xxxxxxxxxxx</pre>

      <p>The authorization server returns a JSON [[!RFC7159]] object identical to the profile property identified in <a href="#profile-information">Profile Information</a> when a <code>response_type</code> value is used that results in an access token being issued and would require the same <code>profile</code> or <code>email</code> scopes. The considerations identified in <a href="#profile-information">Profile Information</a> regarding the non-authoritative nature of the information would also apply here.</p>
      <p>If the request lacks a provided access token, or the token does not contain appropriate scopes, the endpoint SHOULD respond with an error response as noted in <a href="#accessing-protected-resources">Accessing Protected Resources</a>.</p>
      <p>Like the return of profile information in the authorization response, implementation of the userinfo endpoint is entirely optional. If implemented, discovery would be through the <code>userinfo_endpoint</code> property in the authorization server metadata document.</p>

      <pre class="example nohighlight">HTTP/1.1 200 OK
Content-Type: application/json

{
    "name": "Example User",
    "url": "https://user.example.net/",
    "photo": "https://user.example.net/photo.jpg",
    "email": "user@example.net"
}</pre>

    </section>

    <section>
      <h2>Security Considerations</h2>

      <p>In addition to the security considerations in OAuth 2.0 Core [[RFC6749]] and OAuth 2.0 Threat Model and Security Considerations [[RFC6819]], the additional considerations apply.</p>

      <section>
        <h3>Preventing Phishing and Redirect Attacks</h3>

        <p>Authorization servers SHOULD fetch the <code>client_id</code> provided in the authentication or authorization request in order to provide users with additional information about the request, such as the application name and logo. If the server does not fetch the client information, then it SHOULD take additional measures to ensure the user is provided with as much information as possible about the request.</p>

        <p>The authorization server SHOULD display the full <code>client_id</code> on the authorization interface, in addition to displaying the fetched application information if any. Displaying the <code>client_id</code> helps users know that they are authorizing the expected application.</p>

        <p>Since IndieAuth clients are likely to be public clients (if no <a href="https://datatracker.ietf.org/doc/html/rfc7591#section-2"><code>jwks_uri</code></a> is advertised in the client metatadata), the only measure available to protect against some attacks described in [[RFC6819]] is strong verification of the client's <code>redirect_uri</code>. If the <code>redirect_uri</code> scheme, host or port differ from that of the <code>client_id</code>, then the authorization server MUST either verify the redirect URL as described in <a href="#redirect-url">Redirect URL</a>, or display the redirect URL to the user so they can inspect it manually.</p>
      </section>

    </section>

    <section>
      <h2>IANA Considerations</h2>

      <p>The link relation types below are documented to be registered by IANA per Section 6.2.1 of [[!RFC8288]]:</p>

      <dl>
        <dt>Relation Name:</dt>
        <dd>indieauth-metadata</dd>

        <dt>Description:</dt>
        <dd>Used for discovery of the OAuth 2.0 metadata document given an IndieAuth profile URL.</dd>

        <dt>Reference:</dt>
        <dd><a href="https://indieauth.spec.indieweb.org/">IndieAuth Specification (https://indieauth.spec.indieweb.org/)</a></dd>
      </dl>

      <dl>
        <dt>Relation Name:</dt>
        <dd>redirect_uri</dd>

        <dt>Description:</dt>
        <dd>Used for an authorization server to discover the OAuth 2.0 redirect URI for a client given the client's IndieAuth client ID.</dd>

        <dt>Reference:</dt>
        <dd><a href="https://indieauth.spec.indieweb.org/">IndieAuth Specification (https://indieauth.spec.indieweb.org/)</a></dd>
      </dl>
    </section>

    <!--
    <section class="appendix informative">
      <h2>Extensions</h2>

      <p>The following IndieAuth Extension Specifications have 2+ interoperable implementations live on the web and are thus listed here:</p>

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
        community and other implementers for their contributions, support, encouragement and enthusiasm,
        including but not limited to: Angelo Gladding, Amy Guy, Barnaby Walters, Benjamin Roberts, Bret Comnes, Christian Weiske, David Shanske, David Somers, Dmitri Shuralyov, Fluffy, François Kooman, Jamie Tanna, Jeena Paradies, Manton Reece, Martijn van der Ven, Sebastiaan Andeweg, Sven Knebel, and Tantek Çelik.</p>
    </section>

    <section class="appendix informative">
      <h2>Change Log</h2>

      <section>
        <h3>Changes from 26 November 2020 to this version</h3>
        <ul>
          <li>IndieAuth servers now use OAuth Server Metadata to publish their endpoints, and user profile URLs should link to the metadata document instead of the individual authorization endpoint and token endpoint</li>
          <li>Defines the revocation endpoint in the server metadata instead of overriding the token endpoint</li>
          <li>Defines token introspection as a new endpoint and new response format instead of overriding the token endpoint</li>
          <li>Adds a new <code>userinfo</code> endpoint to fetch updated profile data</li>
          <li>Adds the OAuth 2.0 <code>iss</code> parameter to the authorization response</li>
          <li>Adds a section describing refresh token usage. (Note that this was always possible in IndieAuth, but many people didn't know it was an option because it wasn't described here.)</li>
          <li>Fixed redirect URL example in Authorization Response</li>
          <li>Clarifications around the use of the profile scope in profile response and token response</li>
          <li>Note that the authorization server must not fetch the client_id URL if it's a localhost URL</li>
        </ul>
      </section>

      <section>
        <h3>Changes from 26 September 2020 to 26 November 2020</h3>
        <ul>
          <li>Remove same-domain requirement for entered and final profile URL by instead having the client <a href="#authorization-server-confirmation">confirm the authorization server</a></li>
          <li>Only the <code>me</code> value returned by the authorization server is a profile URL, do not refer to the user provided URL as such</li>
          <li>Editorial changes to use the term "host" instead of "domain" where appropriate</li>
        </ul>
      </section>

      <section>
        <h3>Changes from 09 August 2020 to 26 September 2020</h3>
        <ul>
          <li>Make the <code>me</code> parameter optional (but recommended) in the authorization request</li>
          <li>Add the option of returning profile information in the response as well as defining profile scopes</li>
          <li>Incorporate PKCE into the spec</li>
          <li>Fixed text about which URL to resolve relative authorization/token endpoint URLs from</li>
        </ul>
      </section>

      <section>
        <h3>Changes from 25 January 2020 to 09 August 2020</h3>
        <ul>
          <li>Use <code>response_type=code</code> and make it required, to bring it in line with OAuth 2.0</li>
          <li>Require <code>grant_type=authorization_code</code> when redeeming the authorization code at the authorization endpoint</li>
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
