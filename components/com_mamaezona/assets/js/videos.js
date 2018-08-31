MAMAEZONA.playlistId=null;
MAMAEZONA.nextPageToken=null;
MAMAEZONA.prevPageToken=null;

$(document).ready(function(){
	gapi.auth.authorize({
		client_id : OAUTH2_CLIENT_ID,
		scope : OAUTH2_SCOPES,
		immediate : false
	}, carregarYoutubeAPI);
});

function carregarYoutubeAPI(){
	gapi.client.load('youtube', 'v3', function() {
		var request = gapi.client.youtube.channels.list({
			mine : true,
			part : 'contentDetails'
		});
		request.execute(function(response) {
			MAMAEZONA.playlistId = response.result.items[0].contentDetails.relatedPlaylists.uploads;
			carregarListaVideos(MAMAEZONA.playlistId, null);
		});
	});
}

function carregarListaVideos(playlistId, pageToken){
	var requestOptions = {
		playlistId : playlistId,
		part : 'snippet',
		maxResults : 50
	};
	if (pageToken) {
		    requestOptions.pageToken = pageToken;
	}
	var request2 = gapi.client.youtube.playlistItems.list(requestOptions);
	request2.execute(function(resultVideos) { 
		//resultVideos.pageInfo.totalResults
		
		MAMAEZONA.nextPageToken = response.result.nextPageToken;
	    MAMAEZONA.prevPageToken = response.result.prevPageToken
		
		var lista = resultVideos.result.items;
		for(var i=0; i< lista.length; i++){
			var video = lista[i];
			var id = video.id;
			var etag = video.etag;
			var channelId = video.snippet.channelId;
			var titulo = video.snippet.title
			video.snippet.thumbnails['default'].url;
			video.snippet.thumbnails['high'].url;
			video.snippet.thumbnails['maxres'].url;
			video.snippet.thumbnails['medium'].url;
			video.snippet.thumbnails['standard'].url;
			video.snippet.publishedAt
			video.snippet.resourceId.videoId
			video.snippet.description
			//<iframe width="560" height="315" src="https://www.youtube.com/embed/ZoC2cKdoPsE" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
		}
	});
}