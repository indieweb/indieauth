<!DOCTYPE html>
<html>
  <head>
    <title>IndieAuth</title>
    <meta charset='utf-8'>
    <script src='https://www.w3.org/Tools/respec/respec-w3c-common' async class='remove'></script>
    <script src='../assets/add-paragraph-ids.js' class='remove'></script>
    <script class='remove'>
      var respecConfig = {
          useExperimentalStyles: true,
          publishDate: "2017-11-28",
          specStatus: "NOTE",
          shortName:  "indieauth",
          edDraftURI: "https://indieauth.net/spec/",
          /* testSuiteURI: "https://indieauth.rocks/", */
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
          postProcess: [addParagraphIDs],
          otherLinks: [{
            key: 'Repository',
            data: [
              {
                value: 'Github',
                href: 'https://github.com/aaronpk/indieauth.net'
              },
              {
                value: 'Issues',
                href: 'https://github.com/aaronpk/indieauth.net/issues'
              },
              {
                value: 'Commits',
                href: 'https://github.com/aaronpk/indieauth.net/commits/master'
              }
            ]
          }],
          localBiblio:  {
            "microformats2-parsing": {
              title: "Microformats2 Parsing",
              href: "http://microformats.org/wiki/microformats2-parsing",
              authors: [
                "Tantek Çelik"
              ],
              status:   "Living Specification",
              publisher:  "microformats.org"
            },
            "h-entry": {
              title: "h-entry",
              href: "http://microformats.org/wiki/h-entry",
              authors: [
                "Tantek Çelik"
              ],
              status:   "Living Specification",
              publisher:  "microformats.org"
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
    <link rel="pingback" href="https://indieauth.net/pingback.php">
    <link rel="webmention" href="https://indieauth.net/endpoint.php">
  </head>
  <body>
    <section id='abstract'>
      <p>
        IndieAuth is an identity layer on top of OAuth 2.0 [[!RFC6749]], primarily used to obtain
        an OAuth 2.0 Bearer Token [[!RFC6750]] for use by [[!Micropub]] clients. End-Users
        and Clients are all represented by URLs. IndieAuth enables Clients to
        verify the identity of an End-User, as well as to obtain an access
        token that can be used to access resources under the control of the
        End-User.
      </p>

      <section id="authorsnote" class="informative">
        <h2>Author's Note</h2>
        <p>This specification was contributed to the W3C from the
          <a href="https://indieweb.org/">IndieWeb</a> community. More
          history and evolution of Webmention can be found on the
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

      <section>
        <h3>OAuth 2.0 Extension</h3>

        <p>IndieAuth builds upon the OAuth 2.0 [[RFC6749]] Framework as follows</p>

        <ul>
          <li>Specifies a format for user identifiers (a resolvable URL)</li>
          <li>Specifies a method of discovering the authorization and token endpoints given a profile URL</li>
          <li>Specifies a format for the Client ID (a resolvable URL)</li>
          <li>All clients are public clients</li>
          <li>Client registration is not necessary</li>
          <li>Redirect URI registration happens by verifying data fetched at the Client ID URL</li>
          <li>Specifies a mechanism for returning user identifiers</li>
          <li>Specifies a mechanism for verifying authorization codes</li>
          <li>Specifies a mechanism for a token endpoint and authorization endpoint to communicate</li>
        </ul>
      </section>
    </section>

    <section>
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
          <p>An IndieAuth Authorization Endpoint is responsible for obtaining authorization or authentication consent from the end user and generating and verifying authorization codes.</p>
        </section>

        <section>
          <h4>Token Endpoint</h4>
          <p>An IndieAuth Token Endpoint is responsible for generating and verifying OAuth 2.0 Bearer Tokens.</p>
        </section>

        <section>
          <h4>Micropub Client</h4>
          <p>A Micropub client will attempt to obtain an OAuth 2.0 Bearer Token given an IndieAuth identity URL, and will use the token when making Micropub requests.</p>
        </section>

        <section>
          <h4>IndieAuth Client</h4>
          <p>An IndieAuth client is a client that is attempting to authenticate a user given their identity URL, but does not need an OAuth 2.0 Bearer Token.</p>
        </section>

      </section>
    </section>

    <section>
      <h2>Identifiers</h2>

      <section>
        <h3>User Identity URL</h3>

      </section>

      <section>
        <h3>Client Identifier</h3>

      </section>

      <section>
        <h3>Redirect URI Registration</h3>

      </section>

    </section>

    <section>
      <h2>Discovery</h2>

      <p>This specification uses the link rel registry as defined by [[!HTML5]]
        for both HTML and HTTP link relations.</p>



    </section>

    <section>
      <h2>Authentication</h2>

      <p>This section describes how to perform authentication using the Authorization Code Flow.</p>

      <pre class="example">    +--------+
    |        |
    |Resource|
    | Owner  |
    |        |
    +--------+


    +--------+                            +----------------+
    |        +-----(B) Client ID ------>  |                |
    |        |         & Redirect URI     |                |
    | User-  |                            | Authorization  |
    | Agent  |                            |     Server     |
    |        |                            |                |
    |        |                            |                |
    +-+------+                            +----------------+
      ^
      |
     (B)
      |                                   +---------------+
      |                                   |               |
    +-+------+                            |    Resource   |
    |        +-(A)Discover Authorization-->Owner's Website|
    | Client |             Endpoint       |               |
    |        |                            +---------------+
    +--------+
</pre>

      <ul>
        <li>The End-User enters their personal web address in the login form of the client and clicks "Sign in"</li>
        <li>(A) The client discovers the End-User's authorization endpoint by fetching the End-User's website and looking for the <code>rel=authorization_endpoint</code> value</li>
        <li>(B) The client redirects the user agent to the authorization endpoint, including its client identifier, local state, and a redirect URI</li>
        <li>The authorization endpoint verifies the End-User, e.g. by logging in, and establishes whether the End-User grants or denies the client's request</li>
        <li>The authorization endpoint redirects the End-User agent back to the client, including an authorization code</li>
        <li>The client verifies the authorization code by making a POST request to the authorization endpoint. The authorization endpoint validates the authorization code, and responds with the End-User's identity</li>
      </ul>

      <section>
        <h3>Discovery</h3>

        <p>After obtaining the End-User's identity URL, the client fetches the URL and looks for the following rel value on the page.</p>

        <pre class="example">&lt;link rel="authorization_endpoint" href="https://example.com/auth"&gt;</pre>
      </section>

      <section>
        <h3>Authorization Request</h3>

      </section>

      <section>
        <h3>Authorization Response</h3>

      </section>

      <section>
        <h3>Authorization Code Verification</h3>

      </section>
    </section>


    <section>
      <h2>Authorization</h2>

      <ul>
        <li>The End-User enters their personal web address in the login form of the client and clicks "Sign in"</li>
        <li>(A) The client discovers the End-User's authorization endpoint and token endpoint by fetching the End-User's website and looking for the <code>rel=authorization_endpoint</code> and <code>rel=token_endpoint</code> values</li>
        <li>(B) The client redirects the user agent to the authorization endpoint, including its client identifier, requested scope, local state, and a redirect URI</li>
        <li>The authorization endpoint verifies the End-User, e.g. by logging in, and establishes whether the End-User grants or denies the client's request</li>
        <li>The authorization endpoint redirects the End-User agent back to the client, including an authorization code</li>
        <li>The client exchanges the authorization code for an access token by making a POST request to the token endpoint. The token endpoint validates the authorization code, and responds with the End-User's identity and an access token</li>
      </ul>

      <section>
        <h3>Discovery</h3>

        <p>After obtaining the End-User's identity URL, the client fetches the URL and looks for the following rel values on the page.</p>

        <pre class="example">&lt;link rel="authorization_endpoint" href="https://example.com/auth"&gt;
&lt;link rel="token_endpoint" href="https://example.com/token"&gt;</pre>
      </section>

      <section>
        <h3>Authorization Endpoint</h3>

        <section>
          <h4>Authorization Request</h4>

        </section>

        <section>
          <h4>Authorization Response</h4>

        </section>
      </section>

      <section>
        <h3>Token Endpoint</h3>

        <section>
          <h4>Token Request</h4>

        </section>

        <section>
          <h4>Authorization Code Verification</h4>

        </section>

        <section>
          <h4>Access Token Response</h4>

        </section>
      </section>

    </section>


    <section>
      <h2>Security Considerations</h2>


    </section>

    <section>
      <h2>IANA Considerations</h2>
      
      <p>The link relation type below has been registered by IANA per Section 6.2.1 of [[!RFC5988]]:</p>
      
      <dl>
        <dt>Relation Name:</dt>
        <dd>authorization_endpoint</dd>
        
        <dt>Description:</dt>
        <dd>Used for discovery of the OAuth 2.0 authorization endpoint given an IndieAuth identity URL.</dd>
        
        <dt>Reference:</dt>
        <dd><a href="http://www.w3.org/TR/indieauth/">W3C IndieAuth
        Specification (http://www.w3.org/TR/indieauth/)</a></dd>
      </dl>

      <dl>
        <dt>Relation Name:</dt>
        <dd>token_endpoint</dd>
        
        <dt>Description:</dt>
        <dd>Used for discovery of the OAuth 2.0 token endpoint given an IndieAuth identity URL.</dd>
        
        <dt>Reference:</dt>
        <dd><a href="http://www.w3.org/TR/indieauth/">W3C IndieAuth
        Specification (http://www.w3.org/TR/indieauth/)</a></dd>
      </dl>

      <dl>
        <dt>Relation Name:</dt>
        <dd>redirect_uri</dd>
        
        <dt>Description:</dt>
        <dd>Used for discovery of the OAuth 2.0 redirect URI given an IndieAuth client ID.</dd>
        
        <dt>Reference:</dt>
        <dd><a href="http://www.w3.org/TR/indieauth/">W3C IndieAuth
        Specification (http://www.w3.org/TR/indieauth/)</a></dd>
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
          <li><a href="https://webmention.rocks">Test Suite and Debug Tool</a></li>
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
        including but not limited to: Amy Guy, Barnaby Walters, Benjamin Roberts, Bret Comnes, Christian Weiske, François Kooman, Jeena Paradies, Martijn van der Ven, Sebastiaan Andeweg, Sven Knebel, and Tantek Çelik.</p>
    </section>

    <!--
    <section class="appendix informative">
      <h2>Change Log</h2>

      <section>
        <h3>Changes from <a href="">00 November NOTE</a> to this version</h3>

        <ul>

        </ul>
      </section>
    </section>
    -->

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
