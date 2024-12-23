(function() {

    var replaceables = document.querySelectorAll('#episodes pre');

    for (var i = replaceables.length - 1; i >= 0; i--) {
        var replaceableElement = replaceables[i];
        var innerText = replaceableElement.innerText;
        var embeddableIframeContainer = document.createElement('div');
        var newInnerHTML = '';

        if (innerText.match(/\[id:\s*(\d+)\]/)) {
            var applePodcastId = innerText.match(/\[id:\s*(\d+)\]/)[1];

            embeddableIframeContainer.className = 'apple-podcast';
            newInnerHTML = '<iframe height="175" width="100%" title="Media player" src="https://embed.podcasts.apple.com/us/podcast/id279525447?i=';
            newInnerHTML += String(applePodcastId).trim();
            newInnerHTML += '&amp;theme=light" id="embedPlayer" sandbox="allow-forms allow-popups allow-same-origin allow-scripts allow-top-navigation-by-user-activation" allow="autoplay *; encrypted-media *; clipboard-write" style="border: 0px; border-radius: 12px; width: 100%; height: 175px; max-width: 100%;"></iframe>';
        }

        embeddableIframeContainer.innerHTML = newInnerHTML;
        replaceableElement.parentNode.replaceChild(embeddableIframeContainer, replaceableElement);
    }

})();
