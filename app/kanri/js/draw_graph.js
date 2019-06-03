// real_result内のものと同じ関数

// drawSingleBar
//   draw 描画
//   Single 単独(勉強会全体と比較ではない)の
//   Bar 棒グラフ
//--------------
// _canvasId: 描画するcanvas要素のID
// _title: グラフ題(文字列)
// _label: 回答のラベル(文字列配列) 例 ["①0台", "②1～5台", "③6～9台", "④10台以上"]
// _data: 投票割合データ(整数配列) 例 [24, 12, 5 10]
// _sum: 投票数データ(整数配列) 例 [24, 12, 5 10]
// _selected: あなたの回答(整数/1～n)
function drawSingleBar(_canvasId, _title, _label, _data, _sum, _selected){

	const par1 = _data.map(function(v,i){return (i===_selected-1)?0:v;});
	const par2 = _data.map(function(v,i){return (i!==_selected-1)?0:v;});

	const ctx = document.getElementById(_canvasId);

	new Chart(ctx,{
		type: 'horizontalBar',
		data: {
			labels: _label,
			datasets: [
				{
					label: 'あなたの回答',
					data: par2,
					sum: _sum,
					backgroundColor: 'rgba(86, 206, 255, 0.2)',
					borderColor: 'rgba(86, 206, 255, 1)',
					borderWidth: 4,
					stack: 'Stack 1'
				},{
					label: 'この会場',
					data: par1,
					sum: _sum,
					backgroundColor: 'rgba(255, 206, 86, 0.2)',
					borderColor: 'rgba(255, 206, 86, 1)',
					borderWidth: 4,
					stack: 'Stack 1'
				}
			],
			selectedId: _selected-1 // 「あなたの回答」判別のために加えた独自のプロパティ
		},
		options: {
			layout: {
				padding: {
					right: 30
				}
			},
			animation: {
				duration: 0
			},
			scales: {
				xAxes: [{
					ticks: {
						display: false
					},
					gridLines: {
						drawBorder: false,
						color: '#333'
					}
				}],
				yAxes: [{
					ticks: {
						fontSize: 24,
						fontColor: '#000'
					},
					gridLines: {
						display:false,
						color: '#333'
					},
				}]
			},
			legend: {
				labels: {
					fontSize: 24,
					fontColor: '#000'
				}
			},
			tooltips: {
				enabled: false
			},
			title: {
				display: true,
				fontSize: 48,
				text: _title
			}
		},
		plugins: [
			{
				afterDatasetsDraw: function(chart) {
					var ctx = chart.ctx;
					chart.data.datasets.forEach(function(dataset, i) {
						var meta = chart.getDatasetMeta(i);
						if (!meta.hidden) {
							meta.data.forEach(function(element, index) {
								ctx.font = Chart.helpers.fontString(24, 'normal', 'Helvetica Neue');
								ctx.fillStyle = '#000';
								ctx.textAlign = 'center';
								ctx.textBaseline = 'middle';
								var selectedId = chart.data.selectedId; // 選んだ選択肢
								if(
									( i === 0 && index === selectedId ) ||
									( i === 1 && index !== selectedId )
								){
									var dataString = dataset.sum[index].toString()+'%';
									var position = element.tooltipPosition();
									ctx.fillText(dataString, position.x+16, position.y);
								}
							});
						}
					});
				}
			}
		]
	});

}
// drawComparisonBar
//   draw 描画
//   Comparison (勉強会全体と)比較の
//   Bar 棒グラフ
//--------------
// _canvasId: 描画するcanvas要素のID
// _title: グラフ題(文字列)
// _label: 回答のラベル(文字列配列) 例 ["①0台", "②1～5台", "③6～9台", "④10台以上"]
// _groupData: 勉強会全体投票割合データ(整数配列) 例 [24, 12, 5 10]
// _groupSum: 勉強会全体投票数データ(整数配列) 例 [24, 12, 5 10]
// _totalData: 会場全体投票割合データ(整数配列) 例 [24, 12, 5 10]
// _totalSum: 会場全体投票数データ(整数配列) 例 [24, 12, 5 10]
// _selected: あなたの回答(整数/1～n)
function drawComparisonBar(_canvasId, _title, _label, _groupData, _groupSum, _totalData, _totalSum, _selected){

	const d1 = _groupData.map(function(v,i){return (i===_selected-1)?0:v;});
	const d2 = _groupData.map(function(v,i){return (i!==_selected-1)?0:v;});

	const ctx = document.getElementById(_canvasId);
	if(ctx===null){
		console.error('"#'+_canvasId+'" は存在しません。');
		return false;
	}

	new Chart(ctx,{
		type: 'horizontalBar',
		data: {
			labels: _label,
			datasets: [
				{
					label: 'あなたの回答',
					data: d2,
					sum: _groupSum,
					backgroundColor: 'rgba(86, 206, 255, 0.2)',
					borderColor: 'rgba(86, 206, 255, 1)',
					borderWidth: 4,
					stack: 'Stack 1'
				},{
					label: 'この会場',
					data: d1,
					sum: _groupSum,
					backgroundColor: 'rgba(255, 206, 86, 0.2)',
					borderColor: 'rgba(255, 206, 86, 1)',
					borderWidth: 4,
					stack: 'Stack 1'
				},{
					label: '全国平均',
					data: _totalData,
					sum: _totalSum,
					backgroundColor: 'rgba(255, 99, 132, 0.2)',
					borderColor: 'rgba(255,99,132,1)',
					borderWidth: 4,
					stack: 'Stack 0'
				}
			],
			selectedId: _selected-1 // 「あなたの回答」判別のために加えた独自のプロパティ
		},
		options: {
			layout: {
				padding: {
					right: 30
				}
			},
			animation: {
				duration: 0
			},
			scales: {
				xAxes: [{
					ticks: {
						display: false
					},
					gridLines: {
						drawBorder: false,
						color: '#333'
					}
				}],
				yAxes: [{
					ticks: {
						fontSize: 24,
						fontColor: '#000'
					},
					gridLines: {
						display:false,
						color: '#333'
					},
				}]
			},
			legend: {
				labels: {
					fontSize: 24,
					fontColor: '#000'
				}
			},
			tooltips: {
				enabled: false
			},
			title: {
				display: true,
				fontSize: 48,
				fontColor: '#000',
				text: _title
			}
		},
		plugins: [
			{
				afterDatasetsDraw: function(chart) {
					var ctx = chart.ctx;
					chart.data.datasets.forEach(function(dataset, i) {
						// 「あなたの回答」「この会場」はスキップ
						if(i===0||i===1){
							return false;
						}
						var meta = chart.getDatasetMeta(i);
						if (!meta.hidden) {
							meta.data.forEach(function(element, index) {
								ctx.font = Chart.helpers.fontString(24, 'normal', 'Helvetica Neue');
								ctx.fillStyle = '#000';
								ctx.textAlign = 'center';
								ctx.textBaseline = 'middle';
								var selectedId = chart.data.selectedId; // 選んだ選択肢
								if(
									  i === 2 ||
									( i === 0 && index === selectedId ) ||
									( i === 1 && index !== selectedId )
								){
									var dataString = dataset.sum[index].toString()+'%';
									var position = element.tooltipPosition();
									if( position.x < 700 ) {
										ctx.fillText(dataString, position.x+50, position.y);
									} else {
										ctx.fillText(dataString, position.x-50, position.y);
									}
								}
							});
						}
					});
				}
			}
		]
	});

}

// drawComparisonBar2
//   draw 描画
//   Comparison (勉強会全体と)比較の
//   Bar 棒グラフ
//--------------
// _canvasId: 描画するcanvas要素のID
// _title: グラフ題(文字列)
// _label: 回答のラベル(文字列配列) 例 ["①0台", "②1～5台", "③6～9台", "④10台以上"]
// _groupData: 勉強会全体投票割合データ(整数配列) 例 [24, 12, 5 10]
// _groupSum: 勉強会全体投票数データ(整数配列) 例 [24, 12, 5 10]
// _totalData: 会場全体投票割合データ(整数配列) 例 [24, 12, 5 10]
// _totalSum: 会場全体投票数データ(整数配列) 例 [24, 12, 5 10]
// _selected: あなたの回答(配列)(整数/1～n)
function drawComparisonBar2(_canvasId, _title, _label, _groupData, _groupSum, _totalData, _totalSum, _selected){
	const d1 = _groupData.map(function(v,i){return (_selected.indexOf(i)!=-1)?0:v;});
	const d2 = _groupData.map(function(v,i){return (_selected.indexOf(i)==-1)?0:v;});

	const ctx = document.getElementById(_canvasId);
	if(ctx===null){
		console.error('"#'+_canvasId+'" は存在しません。');
		return false;
	}

	new Chart(ctx,{
		type: 'horizontalBar',
		data: {
			labels: _label,
			datasets: [
				{
					label: 'あなたの回答',
					data: d2,
					sum: _groupSum,
					backgroundColor: 'rgba(86, 206, 255, 0.2)',
					borderColor: 'rgba(86, 206, 255, 1)',
					borderWidth: 4,
					stack: 'Stack 1'
				},{
					label: 'この会場',
					data: d1,
					sum: _groupSum,
					backgroundColor: 'rgba(255, 206, 86, 0.2)',
					borderColor: 'rgba(255, 206, 86, 1)',
					borderWidth: 4,
					stack: 'Stack 1'
				},{
					label: '全国平均',
					data: _totalData,
					sum: _totalSum,
					backgroundColor: 'rgba(255, 99, 132, 0.2)',
					borderColor: 'rgba(255,99,132,1)',
					borderWidth: 4,
					stack: 'Stack 0'
				}
			],
			selectedId: _selected-1 // 「あなたの回答」判別のために加えた独自のプロパティ
		},
		options: {
			layout: {
				padding: {
					right: 30
				}
			},
			animation: {
				duration: 0
			},
			scales: {
				xAxes: [{
					ticks: {
						display: false
					},
					gridLines: {
						drawBorder: false,
						color: '#333'
					}
				}],
				yAxes: [{
					ticks: {
						fontSize: 24,
						fontColor: '#000'
					},
					gridLines: {
						display:false,
						color: '#333'
					},
				}]
			},
			legend: {
				labels: {
					fontSize: 24,
					fontColor: '#000'
				}
			},
			tooltips: {
				enabled: false
			},
			title: {
				display: true,
				fontSize: 48,
				fontColor: '#000',
				text: _title
			}
		},
		plugins: [
			{
				afterDatasetsDraw: function(chart) {
					var ctx = chart.ctx;
					chart.data.datasets.forEach(function(dataset, i) {
						var meta = chart.getDatasetMeta(i);
						if (!meta.hidden) {
							meta.data.forEach(function(element, index) {
								if(
									i === 2 // 全国平均
									// || ( i === 1 && !_selected.includes(index) ) // この会場     かつ 選んだ選択肢ではない
									// || ( i === 0 &&  _selected.includes(index) ) // あなたの回答 かつ 選んだ選択肢である
								) {
									ctx.font = Chart.helpers.fontString(24, 'normal', 'Helvetica Neue');
									ctx.fillStyle = '#000';
									ctx.textAlign = 'center';
									ctx.textBaseline = 'middle';
									var selectedId = chart.data.selectedId; // 選んだ選択肢
									var dataString = dataset.sum[index].toString()+'%';
									var position = element.tooltipPosition();
									if( position.x < 1400 ) {
										ctx.fillText(dataString, position.x+50, position.y);
									} else {
										ctx.fillText(dataString, position.x-50, position.y);
									}
								}
							});
						}
					});
				}
			}
		]
	});
}

// drawRadar
//   draw 描画
//   Radar レーダーチャート
//--------------
// _datasets: 描画する線の種別（連想配列）
// _canvasId: 描画するcanvas要素のID
// _title: グラフ題(文字列)
// _label: 回答のラベル(文字列配列) 例 ["①0台", "②1～5台", "③6～9台", "④10台以上"]
function drawRadar( _datasets, _canvasId, _title, _label){
	const ctx = document.getElementById(_canvasId);
//console.log(_datasets);
	new Chart(ctx,{
		type: 'radar',
		data: {
			labels: _label,
			datasets: _datasets
		},
		options: {
			animation: {
				duration: 0
			},
			scale: {
				ticks: {
					beginAtZero:true,
					stepSize: 1,
					max: 5,
					fontSize: 28,
					fontColor: '#000'
				},
				pointLabels: {
					fontSize: 28,
					fontColor: '#000'
				},
				gridLines: {
					color: '#333'
				},
				angleLines: {
					color: '#333'
				}
			},
			legend: {
				labels: {
					fontSize: 28,
					fontColor: '#000'
				}
			},
			title: {
				display: true,
				fontSize: 48,
				fontColor: '#000',
				text: _title
			}
		}
	});
}
