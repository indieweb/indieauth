function indiewebCleanup() {
	// Replace W3C logo with IndieWebCamp logo
	$(".head .logo img").attr("src", "https://spec.indieweb.org/index_files/logo.svg");
	$(".head .logo img").attr("width", "120").attr("height", "100");

	// Replace subhead
	var time = $(".head h2 time").html();
	$(".head h2").html("IndieWeb Living Standard "+time);

	// CC0
	$(".copyright").html("<b>License:</b> Per <a href=\"https://creativecommons.org/publicdomain/zero/1.0/\">CC0</a>, to the extent possible under law, the editor(s) and contributors have waived all copyright and related or neighboring rights to this work. In addition, the editor(s) and contributors have made this specification available under the <a href=\"http://www.openwebfoundation.org/legal/the-owf-1-0-agreements/owfa-1-0\">Open Web Foundation Agreement Version 1.0</a>.");

	// Replace SOTD section
	$("#sotd p").remove();
	$("#sotd").append("<p>This document was published by the IndieWeb community as a Living Standard.</p>");
	$("#sotd").append("<p>Per <a href=\"https://creativecommons.org/publicdomain/zero/1.0/\">CC0</a>, to the extent possible under law, the editor(s) and contributors have waived all copyright and related or neighboring rights to this work. In addition, the editor(s) and contributors have made this specification available under the <a href=\"http://www.openwebfoundation.org/legal/the-owf-1-0-agreements/owfa-1-0\">Open Web Foundation Agreement Version 1.0</a>.</p>");

	// Replace "this version" link
	$(".head dl dd:first a").text(respecConfig.lsURI).attr("href", respecConfig.lsURI);

	// Replace "previous version" link
	$(".head dl dd:nth-of-type(4) a").text(respecConfig.previousVersionURL).attr("href", respecConfig.previousVersionURL);

	// Remove "latest published version" link
	$(".head dl dt:nth-of-type(2)").remove();
	$(".head dl dd:nth-of-type(2)").remove();

	$("body").css("background-image", "url(https://spec.indieweb.org/INDIEWEB-LIVING-STANDARD.svg)");
}
