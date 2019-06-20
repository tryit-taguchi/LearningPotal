<template>
	<div class="app-inner">
		<app-header />
		<main>
			<transition name="fade" mode="out-in">
				<router-view />
			</transition>
		</main>
	</div>
</template>

<style scoped lang="scss">
.app-inner{
	position: relative;
	width: 100%;
	height: 100%;
	background-color: #000;
	color: #fff;
	overflow: auto;
}
main{
	width: 1024px;
	max-width: 1024px;
	margin: 130px auto 0;
	position: relative;
}
</style>

<script>
export default {
	data: function(){
		return {
		}
	},
	created: function () {
		console.log(document.body.setAttribute('data-theme','default'))
	},
	methods: {
	}
}
</script>
