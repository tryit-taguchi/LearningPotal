$(function() {

	// -- イベント設定
	// インポート
	$("#dataImport").bind("click", function(){
		dataImport();
	});
	// エクスポート
	$("#dataExport").bind("click", function(){
		dataExport();
	});
});

// データインポート
function dataImport() {
	var fd = new FormData();
	if ($("input[name='dataFile']").val()!== '') {
		fd.append( "file", $("input[name='dataFile']").prop("files")[0] );
	} else {
		alert("ファイルを指定して下さい。");
		return;
	}
	fd.append("nextProcess","dataImport");
	var postData = {
		type : "POST",
		dataType : "text",
		data : fd,
		processData : false,
		contentType : false
	};
	$.ajax(
		"", postData // 空文字で自分自身
	).done(function( json ){
		try {
			obj = JSON.parse(json);
		} catch(e) {
			$("#systemErrorMessage").html("JSONエラー<br>"+json);
		}
		if( obj.status == "error" ) {
			alert(obj.result.errorMessage);
			console.log(obj.result.errorMessage);
			return;
		}
		if( obj.status == "success" ) {
			alert("アップロードが完了しました。");
			$("#dataImportRes").html(obj.result.lastUpdate);
		}
	});
}
// データエクスポート
function dataExport() {
	location.href = "?"+"nextProcess=dataExport";
}
