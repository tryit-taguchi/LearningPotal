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
	// セーブ１
	$("#dataSave1").bind("click", function(){
		dataSave();
	});
	// セーブ２
	$("#dataSave2").bind("click", function(){
		dataSave();
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

	$("#dataImport").val("インポート中");
	$("#dataImport").prop("disabled", true);

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
			$("#dataImport").val("登録");
			$("#dataImport").prop("disabled", false);
			return;
		}
		if( obj.status == "success" ) {
			$("#dataImport").val("登録");
			$("#dataImport").prop("disabled", false);
			alert("アップロードが完了しました。\n" + obj.result.importCount + "人の有効データをインポートしました。" );
			$("#dataImportRes").html(obj.result.lastUpdate);
		}
	//	$("#lectureNameRes").html(obj.result.lastUpdate);

	//	console.log(text);
	});
}
// データエクスポート
function dataExport() {
//	location.href = "?"+"nextProcess=dataExport&dateS="+$("#dataDateS").val()+"&dateE="+$("#dataDateE").val();
	location.href = "?"+"nextProcess=dataExport&expLectureAtr="+$("#expLectureAtr").val();
}
// 編集一覧
function dataList() {
//	location.href = "?"+"nextProcess=dataExport&dateS="+$("#dataDateS").val()+"&dateE="+$("#dataDateE").val();
	location.href = "?"+"editLectureAtr="+$("#editLectureAtr").val();
}

// データ保存
function dataSave() {
	var form = $('#editForm').get()[0];
	var fd = new FormData(form);
	fd.append("nextProcess","dataSave");
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
			console.log(obj.result.form);
		} catch(e) {
			$("#systemErrorMessage").html("JSONエラー<br>"+json);
		}
		if( obj.status == "error" ) {
			alert(obj.result.errorMessage);
			console.log(obj.result.errorMessage);
			return;
		}
		if( obj.status == "success" ) {
			alert("保存が完了しました。");
			$("#dataImportRes").html(obj.result.lastUpdate);
		}
		$("#lectureNameRes").html(obj.result.lastUpdate);
	});
}

