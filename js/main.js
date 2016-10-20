(function() {

    var mixcloudElements = document.querySelectorAll('#episodes pre');

    for (var i = mixcloudElements.length - 1; i >= 0; i--) {
        var mixcloudElement = mixcloudElements[i];

        var mixcloudIframeContainer = document.createElement('div');
        mixcloudIframeContainer.className = 'mixcloud';
        mixcloudIframeContainer.innerHTML = '<iframe width="100%" height="120" src="https://www.mixcloud.com/widget/iframe/?feed=' + encodeURIComponent('https://www.mixcloud.com/matkovsky/' + mixcloudElements[i].innerText) + '/&hide_cover=1&hide_artwork=1&light=1" frameborder="0"></iframe>';
        mixcloudElement.parentNode.replaceChild(mixcloudIframeContainer, mixcloudElement);
    }

})();
