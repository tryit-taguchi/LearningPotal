$(function() {
	// -- イベント設定
	// 研修名 - 保存
	$("#lectureNameSave").bind("click", function(){
		lectureNameSave();
	});
	// 管理メモ - 保存
	$("#lectureMemoSave").bind("click", function(){
		lectureMemoSave();
	});
});

// 研修名 - 保存
function lectureNameSave() {
	var val = $("#lectureName").val();
	if( val == "" ) {
		$("#lectureName").focus();
		alert("未入力での保存はできません。");
		return;
	}

	var result = window.confirm('検収名を変更しても宜しいですか？');
	if( result ) {

    }
    else {
		return;
    }

	var postData = {
		"nextProcess":"lectureNameSave",
		"val":val
	};
	// 保存して返り値を得る
	$.post(
		"", // 空文字で自分自身
		postData,
		function(json){
			alert("更新が完了しました。");
			obj = JSON.parse(json);
			$("#lectureNameRes").html(obj.result.lastUpdate);
		}
	);
}

// 管理メモ - 保存
function lectureMemoSave() {
	var val = $("#lectureMemo").val();
	if( val == "" ) {
		$("#lectureMemo").focus();
		alert("未入力での保存はできません。");
		return;
	}
	var postData = {
		"nextProcess":"lectureMemoSave",
		"val":val
	};
	// 保存して返り値を得る
	$.post(
		"", // 空文字で自分自身
		postData,
		function(json){
			alert("更新が完了しました。");
			obj = JSON.parse(json);
			$("#lectureMemoRes").html(obj.result.lastUpdate);
		}
	);
}
