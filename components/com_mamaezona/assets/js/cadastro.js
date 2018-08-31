

// Upon loading, the Google APIs JS client automatically invokes this callback.
/*googleApiClientReady = function() {
	gapi.auth.init(function() {
		window.setTimeout(checkAuth, 1);
	});
}*/


/***
 * 
 * 
 * 
//state: "", error_subtype: "access_denied", error: "immediate_failed", client_id: "734115126533-mgmfbslavau9eta4v3p81rgp0b3aj77j.apps.googleusercontent.com", scope: Array(1), …}
{
   client_id:"734115126533-mgmfbslavau9eta4v3p81rgp0b3aj77j.apps.googleusercontent.com"
   cookie_policy:undefined
   error:"immediate_failed"
   error_subtype:"access_denied"
   expires_at:"1516326354"
   expires_in:"86400"
   g_user_cookie_policy:undefined
   issued_at:"1516239954"
   response_type:"token"
   scope:["https://www.googleapis.com/auth/youtube"]
   state:""
   status:{google_logged_in: false, signed_in: false, method: null}
}

//{state: "", access_token: "ya29.GltGBY07cEdgJSrOWOIRRJAOKt8qptXjweS3wOSViParG…aMkpp9euiBY0NUpauljEhJNfF5EsRNIWa_ujUD19nBrcDc7i9", token_type: "Bearer", expires_in: "3600", scope: "https://www.googleapis.com/auth/youtube", …}
{
	access_token:"ya29.GltGBY07cEdgJSrOWOIRRJAOKt8qptXjweS3wOSViParGGnrlgj3b1mCO6hgsptszfeF3gTn1k4aMkpp9euiBY0NUpauljEhJNfF5EsRNIWa_ujUD19nBrcDc7i9"
	client_id:"734115126533-mgmfbslavau9eta4v3p81rgp0b3aj77j.apps.googleusercontent.com"
	cookie_policy:undefined
	expires_at:"1516250863"
	expires_in:"3600"
	g-oauth-window:undefined {window: null, self: null, location: Location, closed: true, frames: null, …}
	g_user_cookie_policy:undefined
	issued_at:"1516247263"
	response_type:"token"
	scope:"https://www.googleapis.com/auth/youtube"
	state:""
	status:{google_logged_in: false, signed_in: true, method: "PROMPT"}
	token_type:"Bearer"
}



{kind: "youtube#playlistItemListResponse", etag: ""g7k5f8kvn67Bsl8L-Bum53neIr4/xyHHTVkfOIyKg27PAcIyX-osk3Q"", pageInfo: {…}, items: Array(3), result: {…}}
{
	etag:""g7k5f8kvn67Bsl8L-Bum53neIr4/xyHHTVkfOIyKg27PAcIyX-osk3Q""
	items:Array(3)
		0:
			etag:""g7k5f8kvn67Bsl8L-Bum53neIr4/c68BSE2A65O2OshgUvhgmV6jODs""
			id:"VVViNDhXN1Joc252c0thWjB1a1lhMi1nLkdCcDhCZDBudGh3"
			kind:"youtube#playlistItem"
			snippet:
				channelId:"UCb48W7RhsnvsKaZ0ukYa2-g"
				channelTitle:"Vídeos Engraçados"
				description:""
				playlistId:"UUb48W7RhsnvsKaZ0ukYa2-g"
				position:0
				publishedAt:"2017-05-16T14:40:37.000Z"
				resourceId:{kind: "youtube#video", videoId: "GBp8Bd0nthw"}
				thumbnails:
					default:{url: "https://i.ytimg.com/vi/GBp8Bd0nthw/default.jpg", width: 120, height: 90}
					high:{url: "https://i.ytimg.com/vi/GBp8Bd0nthw/hqdefault.jpg", width: 480, height: 360}
					medium:{url: "https://i.ytimg.com/vi/GBp8Bd0nthw/mqdefault.jpg", width: 320, height: 180}
					standard:{url: "https://i.ytimg.com/vi/GBp8Bd0nthw/sddefault.jpg", width: 640, height: 480}
					__proto__:Object
				title:"Mais dinâmico"
				__proto__:Object
			__proto__:Object
		1:
			etag:""g7k5f8kvn67Bsl8L-Bum53neIr4/xaLRXkE3FoO6Y9b7OmkPO2C0WmI""
			id:"VVViNDhXN1Joc252c0thWjB1a1lhMi1nLkJwQ2R3RnN2TFFN"
			kind:"youtube#playlistItem"
			snippet:{publishedAt: "2017-05-16T14:39:53.000Z", channelId: "UCb48W7RhsnvsKaZ0ukYa2-g", title: "Conservador", description: "", thumbnails: {…}, …}
			__proto__:Object
		2:
			etag:""g7k5f8kvn67Bsl8L-Bum53neIr4/uVXS4N7jwcbJToZs8es_n8bXdU8""
			id:"VVViNDhXN1Joc252c0thWjB1a1lhMi1nLi0xR0gxdUtPVjhF"
			kind:"youtube#playlistItem"
			snippet:{publishedAt: "2015-11-30T23:32:25.000Z", channelId: "UCb48W7RhsnvsKaZ0ukYa2-g", title: "Transmissão ao vivo de Vídeos Engraçados", description: "", thumbnails: {…}, …}
			__proto__:Object
	length:3
	__proto__:Array(0)
	kind:"youtube#playlistItemListResponse"
	pageInfo:
		resultsPerPage:10
		totalResults:3
	__proto__:Object
	result:
	etag:""g7k5f8kvn67Bsl8L-Bum53neIr4/xyHHTVkfOIyKg27PAcIyX-osk3Q""
	items
:
(3) [{…}, {…}, {…}]
kind
:
"youtube#playlistItemListResponse"
pageInfo
:
{totalResults: 3, resultsPerPage: 10}
__proto__
:
Object
*/





MAMAEZONA.enviarCadastro1 = function (){
	if($('#name').val()==''){
		alert('Primeiro nome obrigatório!');
		$('#name').focus();
		return;
	}	
	if($('#lname').val()==''){
		alert('Sobre nome obrigatório!');
		$('#lname').focus();
		return;
	}
	if($('#password').val()=='' || $('#password').val().length <= 8){
		alert('Senha é um campo obrigatório e deve ter no minimo 8 caracteres!');
		$('#password').focus();
		return;
	}	
	if($('#password').val()!=$('#password1').val()){
		alert('Campos de senhas não conferem!');
		$('#password').focus();
		return;
	}
	if($('#email').val()!=$('#email1').val()){
		alert('Campos de e-mail não conferem!');
		$('#email').focus();
		return;
	}
	
	gapi.auth.authorize({
		client_id : OAUTH2_CLIENT_ID,
		scope : OAUTH2_SCOPES,
		immediate : false
	}, finalizarFormValidado);
}



function finalizarFormValidado(authResult) {
	if (authResult && !authResult.error) {

		$('#access_token').val(authResult.access_token);
		$('#expira').val(authResult.expires_at);
		$('#issued').val(authResult.issued_at);
		$('#login_hint').val(authResult.login_hint);
			

		
		
		gapi.client.load('youtube', 'v3', function() {
			var request = gapi.client.youtube.channels.list({
				mine : true,
				part : 'snippet,contentDetails'
			});
			request.execute(function(response) {

				$('#pais_canal').val(response.result.items[0].snippet.country);
				$('#customUrl').val(response.result.items[0].snippet.customUrl);
				$('#descricao_canal').val(response.result.items[0].snippet.description);
				$('#publicado_canal').val(response.result.items[0].snippet.publishedAt);
				$('#thumb_default_canal').val(response.result.items[0].snippet.thumbnails.default.url);
				$('#thumb_high_canal').val(response.result.items[0].snippet.thumbnails.high.url);
				$('#thumb_medium_canal').val(response.result.items[0].snippet.thumbnails.medium.url);
				$('#titulo').val(response.result.items[0].snippet.title);
				try{
					$('#pl_favorites_canal').val(response.result.items[0].contentDetails.relatedPlaylists.favorites);
				}catch(ex){
				}
				try{
					$('#pl_likes_canal').val(response.result.items[0].contentDetails.relatedPlaylists.likes);
				}catch(ex){
				}
				try{
					$('#pl_uploads_canal').val(response.result.items[0].contentDetails.relatedPlaylists.uploads);
				}catch(ex){
				}
				try{
					$('#youtube').val(response.result.items[0].id);
				}catch(ex){
				}
				$('#cadastroForm').submit();
			});
		});
	} else {
		if(confirm('Necessita logar na sua conta do youtube para continuar')){
			gapi.auth.authorize({
				client_id : OAUTH2_CLIENT_ID,
				scope : OAUTH2_SCOPES,
				immediate : false
			}, finalizarFormValidado);
		}
	}
}

function checkAtualizarListaVideos() {
	gapi.auth.authorize({
		client_id : OAUTH2_CLIENT_ID,
		scope : OAUTH2_SCOPES,
		immediate : true
	}, carregarVideos);
}



function carregarVideos(){
	if (authResult && !authResult.error) {
		gapi.client.load('youtube', 'v3', function() {
			var request = gapi.client.youtube.channels.list({
				mine : true,
				part : 'contentDetails,snippet'
			});
			request.execute(function(response) {
		
				var request = gapi.client.youtube.playlistItems.list({
		            playlistId : playlistId,
		            part : 'id,snippet,contentDetails',
		            maxResults : 50
		        });
		
				request.execute(function(r) { 
					console.log('POR playlistItems - playlistId');
					console.log(r);
				});
		
			});
		
		    var request2 = gapi.client.youtube.videos.list({
		        id:'ZoC2cKdoPsE',
		        part : 'id,snippet,contentDetails,fileDetails,player,processingDetails,recordingDetails,statistics,status,suggestions,topicDetails',
		        maxResults : 50
		    });
		
		    request2.execute(function(r) { 
				console.log('POR videos');
		        console.log(r);
		    });
		});
	} 
	else {
		if(confirm('Necessita logar na sua conta do youtube para continuar')){
			gapi.auth.authorize({
				client_id : OAUTH2_CLIENT_ID,
				scope : OAUTH2_SCOPES,
				immediate : false
			}, carregarVideos);
		}
	}
}

function carregarPlayList(){
	gapi.client.load('youtube', 'v3', function() {
		var request = gapi.client.youtube.channels.list({
			mine : true,
			part : 'contentDetails'
		});
		request.execute(function(response) {
			var playlistId = response.result.items[0].contentDetails.relatedPlaylists.uploads;
			carregarDetalheVideo(playlistId, null);
			
		});

	});
}

function carregarDetalheVideo(playlistId, nextPageToken){
	var request = gapi.client.youtube.playlistItems.list({
		playlistId : playlistId,
		mine : true,
		part : 'contentDetails,snippet'
	});
	if(nextPageToken){
		request.nextPageToken = nextPageToken;
	}
	request.execute(function(resposta) { 
		var videos = resposta.result.items;
		var video;
		for(var i=0; i< videos.length; i++ ){
			video = videos[i];
			dadosVideos(video);
		}
		
		if(resposta.restul.nextPageToken){
			carregarDetalheVideo(playlistId, resposta.result.nextPageToken);
		}
	});
}

function dadosVideos(videoObj){
	var videoId = videoObj.snippet.resourceId.videoId;
	
	/*videoObj.snippet.thumbnails
	videoObj.snippet.title
	videoObj.snippet.descripion
	videoObj.snippet.publishedAt

	*/
    var request = gapi.client.youtube.videos.list({
        id: videoId,
        part : 'id,snippet,contentDetails,fileDetails,player,processingDetails,recordingDetails,statistics,status,suggestions,topicDetails',
        maxResults : 50
    });

    request.execute(function(video) { 
/*    	videoObj.dados = video;
    	video.statistics.commentCount;
    	video.statistics.dislikeCount;
    	video.statistics.favoriteCount;
    	video.statistics.likeCount;
    	video.statistics.viewCount;
    	video.snippet.categoryId;
    	video.snippet.defaultAudioLanguage;
    	video.snippet.defaultLanguage;
    	video.snippet.tags;
    	video.snippet.liveBroadcastContent;
    	video.fileDetails.audioStreams[0]{channelCount: 2, codec: "aac", bitrateBps: "317381"}
    	video.fileDetails[0].bitrateBps
    	video.fileDetails[0].container
    	video.fileDetails[0].durationMs
    	video.fileDetails[0].fileName
    	video.fileDetails[0].fileSize
    	video.fileDetails[0].fileType
    	video.fileDetails[0].videoStreams[0]
    	video.fileDetails[0].videoStreams[0].aspectRatio
    	video.fileDetails[0].videoStreams[0].bitrateBps
    	video.fileDetails[0].videoStreams[0].codec
    	video.fileDetails[0].videoStreams[0].frameRateFps
    	video.fileDetails[0].videoStreams[0].heightPixels
    	video.fileDetails[0].videoStreams[0].widthPixels
    	video.snippet.thumbnails.default.url: "https://i.ytimg.com/vi/ZoC2cKdoPsE/default.jpg", width: 120, height: 90}
    	video.snippet.thumbnails.high.url: "https://i.ytimg.com/vi/ZoC2cKdoPsE/hqdefault.jpg", width: 480, height: 360}
    	video.snippet.thumbnails.maxres.url: "https://i.ytimg.com/vi/ZoC2cKdoPsE/maxresdefault.jpg", width: 1280, height: 720}
    	video.snippet.thumbnails.medium.url: "https://i.ytimg.com/vi/ZoC2cKdoPsE/mqdefault.jpg", width: 320, height: 180}
    	video.snippet.thumbnails.standard.url: "https://i.ytimg.com/vi/ZoC2cKdoPsE/sddefault.jpg", width: 640, height: 480}*/
    });
	
}






$(document).ready(function(){
	$('.cadastro1').click();
});