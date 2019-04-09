
//alert("test");

export default {
	created: function () {
		//this.mtd('mixin')
	},
    methods: {
		getJson: function(url,collback) {
			var $this = this;
			$this.$axios.get(url).then(function (response) {
				//console.log(response.data);
				collback(response.data);
				//console.log(retObj.questions);
				//$this.$set($this,valueName,response.data);
			});
		}
	}
}
/*


		getJson: function(url,valueName) {
			var $this = this;
			$this.$axios.get(url).then(function (response) {


				console.log(response.data);
				//console.log(retObj.questions);
				$this.$set($this,valueName,response.data);
//				retObj.questions = response.data;
				//console.log(retObj.questions);

			});
		}

var ajax         = new Object;

ajax.getJson = function(url,$obj) {
	fetch(url).then(function (response) {
		return response.json();
	}).then(function (json) {
		$obj.questions = json;
	});
}
*/
/*
function getJson2(url,$obj) {
	fetch(url).then(function (response) {
		return response.json();
	}).then(function (json) {
		$obj.questions = json;
	});
}
*/
// https://aloerina01.github.io/blog/2018-12-25-1
/*
Vue.mixin({
  created: function () {
    var myOption = this.$options.myOption
    if (myOption) {
      console.log(myOption)
    }
  }
})
*/
