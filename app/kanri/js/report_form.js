$(function() {
	// -- イベント設定
	// エクスポート
	$("#resultView").bind("click", function(){
		reportResult();
		$("#resultView").val("レポート生成中");
	});
});
function reportResult() {
	$('#form').submit();
}

